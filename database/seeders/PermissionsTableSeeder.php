<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'auth_profile_edit',
            ],
            [
                'id'    => 2,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 3,
                'title' => 'permission_create',
            ],
            [
                'id'    => 4,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 5,
                'title' => 'permission_show',
            ],
            [
                'id'    => 6,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 7,
                'title' => 'permission_access',
            ],
            [
                'id'    => 8,
                'title' => 'role_create',
            ],
            [
                'id'    => 9,
                'title' => 'role_edit',
            ],
            [
                'id'    => 10,
                'title' => 'role_show',
            ],
            [
                'id'    => 11,
                'title' => 'role_delete',
            ],
            [
                'id'    => 12,
                'title' => 'role_access',
            ],
            [
                'id'    => 13,
                'title' => 'user_create',
            ],
            [
                'id'    => 14,
                'title' => 'user_edit',
            ],
            [
                'id'    => 15,
                'title' => 'user_show',
            ],
            [
                'id'    => 16,
                'title' => 'user_delete',
            ],
            [
                'id'    => 17,
                'title' => 'user_access',
            ],
            [
                'id'    => 18,
                'title' => 'coupon_create',
            ],
            [
                'id'    => 19,
                'title' => 'coupon_edit',
            ],
            [
                'id'    => 20,
                'title' => 'coupon_show',
            ],
            [
                'id'    => 21,
                'title' => 'coupon_delete',
            ],
            [
                'id'    => 22,
                'title' => 'coupon_access',
            ],
            [
                'id'    => 23,
                'title' => 'airport_create',
            ],
            [
                'id'    => 24,
                'title' => 'airport_edit',
            ],
            [
                'id'    => 25,
                'title' => 'airport_show',
            ],
            [
                'id'    => 26,
                'title' => 'airport_delete',
            ],
            [
                'id'    => 27,
                'title' => 'airport_access',
            ],
            [
                'id'    => 28,
                'title' => 'cms_page_create',
            ],
            [
                'id'    => 29,
                'title' => 'cms_page_edit',
            ],
            [
                'id'    => 30,
                'title' => 'cms_page_show',
            ],
            [
                'id'    => 31,
                'title' => 'cms_page_delete',
            ],
            [
                'id'    => 32,
                'title' => 'cms_page_access',
            ],
            [
                'id'    => 33,
                'title' => 'currency_create',
            ],
            [
                'id'    => 34,
                'title' => 'currency_edit',
            ],
            [
                'id'    => 35,
                'title' => 'currency_show',
            ],
            [
                'id'    => 36,
                'title' => 'currency_delete',
            ],
            [
                'id'    => 37,
                'title' => 'currency_access',
            ],
            [
                'id'    => 38,
                'title' => 'document_create',
            ],
            [
                'id'    => 39,
                'title' => 'document_edit',
            ],
            [
                'id'    => 40,
                'title' => 'document_show',
            ],
            [
                'id'    => 41,
                'title' => 'document_delete',
            ],
            [
                'id'    => 42,
                'title' => 'document_access',
            ],
            [
                'id'    => 43,
                'title' => 'driver_document_create',
            ],
            [
                'id'    => 44,
                'title' => 'driver_document_edit',
            ],
            [
                'id'    => 45,
                'title' => 'driver_document_show',
            ],
            [
                'id'    => 46,
                'title' => 'driver_document_delete',
            ],
            [
                'id'    => 47,
                'title' => 'driver_document_access',
            ],
            [
                'id'    => 48,
                'title' => 'driver_rule_create',
            ],
            [
                'id'    => 49,
                'title' => 'driver_rule_edit',
            ],
            [
                'id'    => 50,
                'title' => 'driver_rule_show',
            ],
            [
                'id'    => 51,
                'title' => 'driver_rule_delete',
            ],
            [
                'id'    => 52,
                'title' => 'driver_rule_access',
            ],
            [
                'id'    => 53,
                'title' => 'driver_user_create',
            ],
            [
                'id'    => 54,
                'title' => 'driver_user_edit',
            ],
            [
                'id'    => 55,
                'title' => 'driver_user_show',
            ],
            [
                'id'    => 56,
                'title' => 'driver_user_delete',
            ],
            [
                'id'    => 57,
                'title' => 'driver_user_access',
            ],
            [
                'id'    => 58,
                'title' => 'faq_create',
            ],
            [
                'id'    => 59,
                'title' => 'faq_edit',
            ],
            [
                'id'    => 60,
                'title' => 'faq_show',
            ],
            [
                'id'    => 61,
                'title' => 'faq_delete',
            ],
            [
                'id'    => 62,
                'title' => 'faq_access',
            ],
            [
                'id'    => 63,
                'title' => 'freight_vehicle_create',
            ],
            [
                'id'    => 64,
                'title' => 'freight_vehicle_edit',
            ],
            [
                'id'    => 65,
                'title' => 'freight_vehicle_show',
            ],
            [
                'id'    => 66,
                'title' => 'freight_vehicle_delete',
            ],
            [
                'id'    => 67,
                'title' => 'freight_vehicle_access',
            ],
            [
                'id'    => 68,
                'title' => 'intercity_service_create',
            ],
            [
                'id'    => 69,
                'title' => 'intercity_service_edit',
            ],
            [
                'id'    => 70,
                'title' => 'intercity_service_show',
            ],
            [
                'id'    => 71,
                'title' => 'intercity_service_delete',
            ],
            [
                'id'    => 72,
                'title' => 'intercity_service_access',
            ],
            [
                'id'    => 73,
                'title' => 'language_create',
            ],
            [
                'id'    => 74,
                'title' => 'language_edit',
            ],
            [
                'id'    => 75,
                'title' => 'language_show',
            ],
            [
                'id'    => 76,
                'title' => 'language_delete',
            ],
            [
                'id'    => 77,
                'title' => 'language_access',
            ],
            [
                'id'    => 78,
                'title' => 'on_boarding_create',
            ],
            [
                'id'    => 79,
                'title' => 'on_boarding_edit',
            ],
            [
                'id'    => 80,
                'title' => 'on_boarding_show',
            ],
            [
                'id'    => 81,
                'title' => 'on_boarding_delete',
            ],
            [
                'id'    => 82,
                'title' => 'on_boarding_access',
            ],
            [
                'id'    => 83,
                'title' => 'order_create',
            ],
            [
                'id'    => 84,
                'title' => 'order_edit',
            ],
            [
                'id'    => 85,
                'title' => 'order_show',
            ],
            [
                'id'    => 86,
                'title' => 'order_delete',
            ],
            [
                'id'    => 87,
                'title' => 'order_access',
            ],
            [
                'id'    => 88,
                'title' => 'orders_intercity_create',
            ],
            [
                'id'    => 89,
                'title' => 'orders_intercity_edit',
            ],
            [
                'id'    => 90,
                'title' => 'orders_intercity_show',
            ],
            [
                'id'    => 91,
                'title' => 'orders_intercity_delete',
            ],
            [
                'id'    => 92,
                'title' => 'orders_intercity_access',
            ],
            [
                'id'    => 93,
                'title' => 'referral_create',
            ],
            [
                'id'    => 94,
                'title' => 'referral_edit',
            ],
            [
                'id'    => 95,
                'title' => 'referral_show',
            ],
            [
                'id'    => 96,
                'title' => 'referral_delete',
            ],
            [
                'id'    => 97,
                'title' => 'referral_access',
            ],
            [
                'id'    => 98,
                'title' => 'review_customer_create',
            ],
            [
                'id'    => 99,
                'title' => 'review_customer_edit',
            ],
            [
                'id'    => 100,
                'title' => 'review_customer_show',
            ],
            [
                'id'    => 101,
                'title' => 'review_customer_delete',
            ],
            [
                'id'    => 102,
                'title' => 'review_customer_access',
            ],
            [
                'id'    => 103,
                'title' => 'review_driver_create',
            ],
            [
                'id'    => 104,
                'title' => 'review_driver_edit',
            ],
            [
                'id'    => 105,
                'title' => 'review_driver_show',
            ],
            [
                'id'    => 106,
                'title' => 'review_driver_delete',
            ],
            [
                'id'    => 107,
                'title' => 'review_driver_access',
            ],
            [
                'id'    => 108,
                'title' => 'service_create',
            ],
            [
                'id'    => 109,
                'title' => 'service_edit',
            ],
            [
                'id'    => 110,
                'title' => 'service_show',
            ],
            [
                'id'    => 111,
                'title' => 'service_delete',
            ],
            [
                'id'    => 112,
                'title' => 'service_access',
            ],
            [
                'id'    => 113,
                'title' => 'so_create',
            ],
            [
                'id'    => 114,
                'title' => 'so_edit',
            ],
            [
                'id'    => 115,
                'title' => 'so_show',
            ],
            [
                'id'    => 116,
                'title' => 'so_delete',
            ],
            [
                'id'    => 117,
                'title' => 'so_access',
            ],
            [
                'id'    => 118,
                'title' => 'tax_create',
            ],
            [
                'id'    => 119,
                'title' => 'tax_edit',
            ],
            [
                'id'    => 120,
                'title' => 'tax_show',
            ],
            [
                'id'    => 121,
                'title' => 'tax_delete',
            ],
            [
                'id'    => 122,
                'title' => 'tax_access',
            ],
            [
                'id'    => 123,
                'title' => 'vehicle_type_create',
            ],
            [
                'id'    => 124,
                'title' => 'vehicle_type_edit',
            ],
            [
                'id'    => 125,
                'title' => 'vehicle_type_show',
            ],
            [
                'id'    => 126,
                'title' => 'vehicle_type_delete',
            ],
            [
                'id'    => 127,
                'title' => 'vehicle_type_access',
            ],
            [
                'id'    => 128,
                'title' => 'wallet_transaction_create',
            ],
            [
                'id'    => 129,
                'title' => 'wallet_transaction_edit',
            ],
            [
                'id'    => 130,
                'title' => 'wallet_transaction_show',
            ],
            [
                'id'    => 131,
                'title' => 'wallet_transaction_delete',
            ],
            [
                'id'    => 132,
                'title' => 'wallet_transaction_access',
            ],
            [
                'id'    => 133,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 134,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 135,
                'title' => 'chat_create',
            ],
            [
                'id'    => 136,
                'title' => 'chat_edit',
            ],
            [
                'id'    => 137,
                'title' => 'chat_show',
            ],
            [
                'id'    => 138,
                'title' => 'chat_delete',
            ],
            [
                'id'    => 139,
                'title' => 'chat_access',
            ],
            [
                'id'    => 140,
                'title' => 'thread_access',
            ],
            [
                'id'    => 141,
                'title' => 'setting_access',
            ],
            [
                'id'    => 142,
                'title' => 'orders_m_access',
            ],
        ];

        Permission::insert($permissions);
    }
}
