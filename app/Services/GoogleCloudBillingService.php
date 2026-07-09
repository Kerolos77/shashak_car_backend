<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Expense;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleCloudBillingService
{
    /**
     * Fetch billing data from Google Cloud BigQuery and store in expenses table.
     */
    public function fetchBilling(): array
    {
        $setting = Setting::first();
        if (!$setting || empty($setting->gcp_service_account_json)) {
            Log::warning('Google Cloud Billing Service: Service Account JSON is not configured.');
            return ['success' => false, 'message' => 'GCP Service Account JSON is missing.'];
        }

        $serviceAccount = json_decode($setting->gcp_service_account_json, true);
        if (json_last_error() !== JSON_ERROR_NONE || empty($serviceAccount)) {
            Log::error('GCP Service Account JSON is invalid.');
            return ['success' => false, 'message' => 'Invalid JSON for Service Account.'];
        }

        $projectId = $serviceAccount['project_id'] ?? null;
        if (!$projectId) {
            return ['success' => false, 'message' => 'Project ID is missing from Service Account JSON.'];
        }

        $accessToken = $this->getGoogleAccessToken($serviceAccount);
        if (!$accessToken) {
            return ['success' => false, 'message' => 'Failed to obtain Google Access Token.'];
        }

        $exchangeRate = $setting->usd_to_egp_exchange_rate ?? 50.00;

        try {
            $billingAccountId = str_replace('-', '_', strtolower($setting->gcp_billing_account_id ?? ''));
            if (empty($billingAccountId)) {
                return ['success' => false, 'message' => 'GCP Billing Account ID is missing in settings.'];
            }

            // Google Cloud Billing Export default dataset name can be custom, 
            // but standard is gcp_billing_export.
            $tableName = "`{$projectId}.gcp_billing_export.gcp_billing_export_v1_{$billingAccountId}`";

            $query = "
                SELECT 
                    invoice.month as invoice_month,
                    service.description as service_desc,
                    sum(cost) as total_cost,
                    currency
                FROM {$tableName}
                WHERE _PARTITIONTIME >= TIMESTAMP_SUB(CURRENT_TIMESTAMP(), INTERVAL 60 DAY)
                GROUP BY invoice_month, service_desc, currency
                ORDER BY invoice_month DESC
            ";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post("https://bigquery.googleapis.com/bigquery/v2/projects/{$projectId}/queries", [
                'query' => $query,
                'useLegacySql' => false
            ]);

            if (!$response->successful()) {
                Log::error('GCP BigQuery API call failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return [
                    'success' => false, 
                    'message' => 'BigQuery query failed. Verify if Billing Export to BigQuery dataset name is gcp_billing_export.'
                ];
            }

            $results = $response->json();
            $rows = $results['rows'] ?? [];
            $importedCount = 0;

            foreach ($rows as $row) {
                // BigQuery row structure f => [{v: val}, {v: val}, ...]
                $invoiceMonth = $row['f'][0]['v'] ?? '';
                $serviceName = $row['f'][1]['v'] ?? 'Google Cloud';
                $cost = floatval($row['f'][2]['v'] ?? 0);
                $currency = $row['f'][3]['v'] ?? 'USD';

                if ($cost <= 0.01) continue;

                // Reference ID to prevent double logs
                $refId = 'gcp_' . str_replace(' ', '_', strtolower($serviceName)) . '_' . $invoiceMonth;
                $description = "تكلفة استهلاك جوجل كلاود ({$serviceName}) لشهـر {$invoiceMonth} (القيمة: \${$cost})";
                $expenseDate = date('Y-m-t', strtotime($invoiceMonth . '-01')); // End of month

                $expense = Expense::updateOrCreate([
                    'category' => 'google_cloud',
                    'reference_id' => $refId,
                ], [
                    'amount' => $cost,
                    'currency' => $currency,
                    'exchange_rate' => $exchangeRate,
                    'amount_egp' => $cost * $exchangeRate,
                    'description' => $description,
                    'expense_date' => $expenseDate,
                    'is_automated' => true,
                ]);

                if ($expense->wasRecentlyCreated) {
                    $importedCount++;
                }
            }

            return [
                'success' => true,
                'message' => "Successfully processed GCP Billing. Updated {$importedCount} entries.",
                'imported_count' => $importedCount
            ];

        } catch (\Exception $e) {
            Log::error('Exception in GoogleCloudBillingService: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    private function getGoogleAccessToken(array $serviceAccount): ?string
    {
        $privateKey = $serviceAccount['private_key'] ?? null;
        $clientEmail = $serviceAccount['client_email'] ?? null;

        if (!$privateKey || !$clientEmail) {
            return null;
        }

        $now = time();
        $payload = [
            'iss' => $clientEmail,
            'scope' => 'https://www.googleapis.com/auth/cloud-platform',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ];

        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode(json_encode($payload));

        $signature = '';
        $success = openssl_sign(
            $base64UrlHeader . '.' . $base64UrlPayload,
            $signature,
            $privateKey,
            'SHA256'
        );

        if (!$success) {
            return null;
        }

        $base64UrlSignature = $this->base64UrlEncode($signature);
        $jwt = $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if (!$response->successful()) {
            Log::error('Google OAuth exchange failed', ['body' => $response->body()]);
            return null;
        }

        return $response->json('access_token');
    }

    private function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}
