<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>{{ App\Helpers\ArabicShaper::shape('تقرير أمني رسمي - ' . $user->name) }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap');
        body {
            font-family: 'Cairo', 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        @page {
            margin: 1.5cm;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px double #d32f2f;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo-title {
            text-align: center;
        }
        .logo-title h1 {
            margin: 0;
            font-size: 20px;
            color: #d32f2f;
            font-weight: bold;
        }
        .logo-title h2 {
            margin: 5px 0 0 0;
            font-size: 13px;
            color: #1a237e;
            font-weight: normal;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            background-color: #f5f5f5;
            border: 1px solid #e0e0e0;
            padding: 8px;
            font-size: 10px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1a237e;
            border-bottom: 1px solid #1a237e;
            padding-bottom: 5px;
            margin-top: 15px;
            margin-bottom: 10px;
            text-align: right;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .data-table th, .data-table td {
            border: 1px solid #e0e0e0;
            padding: 6px 10px;
            text-align: right;
            vertical-align: middle;
        }
        .data-table th {
            background-color: #f8f9fa;
            color: #555;
            width: 30%;
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 3px;
        }
        .badge-success { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
        .badge-danger { background-color: #ffebee; color: #c62828; border: 1px solid #ef9a9a; }
        .badge-primary { background-color: #e8eaf6; color: #1a237e; border: 1px solid #9fa8da; }
        
        .map-section {
            background-color: #fffde7;
            border: 1px solid #fff59d;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .map-link {
            display: inline-block;
            background-color: #1a237e;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 4px;
            font-size: 11px;
            margin-top: 5px;
        }
        .footer {
            position: fixed;
            bottom: -40px;
            left: 0px;
            right: 0px;
            height: 30px;
            text-align: center;
            font-size: 9px;
            color: #777;
            border-top: 1px solid #e0e0e0;
            padding-top: 5px;
        }
        .page-break {
            page-break-after: always;
        }
        .doc-container {
            margin-bottom: 20px;
            text-align: center;
            page-break-inside: avoid;
        }
        .doc-title {
            font-size: 11px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            text-align: right;
            background-color: #eee;
            padding: 5px 10px;
            border-right: 4px solid #d32f2f;
        }
        .doc-img {
            max-width: 90%;
            max-height: 380px;
            border: 1px solid #ccc;
            padding: 5px;
            background-color: white;
        }
        .avatar-img {
            width: 90px;
            height: 90px;
            border: 2px solid #1a237e;
            border-radius: 4px;
        }
        .profile-container {
            width: 100%;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="width: 25%; text-align: right; vertical-align: middle; font-size: 10px;">
                <strong>{{ App\Helpers\ArabicShaper::shape('شقشق كار (Shashak Car)') }}</strong><br>
                {{ App\Helpers\ArabicShaper::shape('نظام إدارة النقل الذكي') }}<br>
                {{ App\Helpers\ArabicShaper::shape('تاريخ الطباعة: ' . date('Y-m-d H:i')) }}
            </td>
            <td style="width: 50%;" class="logo-title">
                <h1>{{ App\Helpers\ArabicShaper::shape('مستند أمني رسمي لحالة مستخدم') }}</h1>
                <h2>Official Security & Identity Report</h2>
            </td>
            <td style="width: 25%; text-align: left; vertical-align: middle; font-size: 10px; direction: ltr;">
                <strong>EMERGENCY STATUS</strong><br>
                Confidential & Certified<br>
                For Official Use Only
            </td>
        </tr>
    </table>

    <!-- Metadata Table -->
    <table class="meta-table">
        <tr>
            <td style="width: 35%;"><strong>{{ App\Helpers\ArabicShaper::shape('رقم التقرير (Report ID):') }}</strong> SEC-{{ $user->id }}-{{ date('Ymd') }}</td>
            <td style="width: 30%; text-align: center;"><strong>{{ App\Helpers\ArabicShaper::shape('المصدر: إدارة العمليات والأمن (Operations)') }}</strong></td>
            <td style="width: 35%; text-align: left; direction: ltr;"><strong>Target:</strong> Official Police Inquiry</td>
        </tr>
    </table>

    <!-- User Profile & Summary Info -->
    <div class="section-title">{{ App\Helpers\ArabicShaper::shape('البيانات الأساسية للمستخدم (Basic Profile Details)') }}</div>
    
    <table class="profile-container">
        <tr>
            <td style="vertical-align: top; width: 80%;">
                <table class="data-table">
                    <tr>
                        <th>{{ App\Helpers\ArabicShaper::shape('الاسم الكامل (Full Name)') }}</th>
                        <td><strong>{{ App\Helpers\ArabicShaper::shape($user->name) }}</strong></td>
                    </tr>
                    <tr>
                        <th>{{ App\Helpers\ArabicShaper::shape('نوع الحساب (Account Type)') }}</th>
                        <td>
                            @if($type === 'driver')
                                <span class="badge badge-primary">{{ App\Helpers\ArabicShaper::shape('سائق كابتن (Driver Captain)') }}</span>
                            @else
                                <span class="badge badge-success">{{ App\Helpers\ArabicShaper::shape('عميل راكب (Client Passenger)') }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ App\Helpers\ArabicShaper::shape('رقم الهاتف (Phone Number)') }}</th>
                        <td>{{ $user->phone_number }}</td>
                    </tr>
                    <tr>
                        <th>{{ App\Helpers\ArabicShaper::shape('البريد الإلكتروني (Email)') }}</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>{{ App\Helpers\ArabicShaper::shape('الدولة والمدينة (Country & City)') }}</th>
                        <td>
                            {{ App\Helpers\ArabicShaper::shape(($user->country->name ?? '-') . ' - ' . ($user->city->name ?? '-')) }}
                        </td>
                    </tr>
                    <tr>
                        <th>{{ App\Helpers\ArabicShaper::shape('تاريخ التسجيل (Register Date)') }}</th>
                        <td>{{ $user->created_at }}</td>
                    </tr>
                </table>
            </td>
            <td style="vertical-align: top; width: 20%; text-align: center; padding-right: 15px;">
                @if($avatarBase64)
                    <img src="{{ $avatarBase64 }}" class="avatar-img" alt="Profile Picture">
                @else
                    <div style="width: 90px; height: 90px; border: 2px dashed #ccc; line-height: 90px; text-align: center; color: #aaa; background-color: #fafafa; font-size: 10px;">No Image</div>
                @endif
            </td>
        </tr>
    </table>

    @if($type === 'driver' && $profile)
        <!-- Driver & Vehicle Details Section -->
        <div class="section-title">{{ App\Helpers\ArabicShaper::shape('بيانات السائق والسيارة المعتمدة (Driver & Vehicle Specifications)') }}</div>
        <table class="data-table">
            <tr>
                <th style="width: 25%;">{{ App\Helpers\ArabicShaper::shape('الرقم القومي (National ID)') }}</th>
                <td style="width: 25%;">{{ $profile->id_number }}</td>
                <th style="width: 25%;">{{ App\Helpers\ArabicShaper::shape('تاريخ الميلاد (Date of Birth)') }}</th>
                <td style="width: 25%;">{{ $profile->birth_date }}</td>
            </tr>
            <tr>
                <th>{{ App\Helpers\ArabicShaper::shape('نوع الخدمة (Service Type)') }}</th>
                <td>{{ App\Helpers\ArabicShaper::shape($profile->service->title ?? '-') }}</td>
                <th>{{ App\Helpers\ArabicShaper::shape('حالة السائق (Driver Status)') }}</th>
                <td>
                    @if($profile->status === 'active')
                        <span class="badge badge-success">{{ App\Helpers\ArabicShaper::shape('نشط ومفعل (Active)') }}</span>
                    @elseif($profile->status === 'blocked')
                        <span class="badge badge-danger">{{ App\Helpers\ArabicShaper::shape('محظور (Blocked)') }}</span>
                    @else
                        <span class="badge badge-danger">{{ App\Helpers\ArabicShaper::shape('معلق للمراجعة (Pending)') }}</span>
                    @endif
                </td>
            </tr>
            @if($profile->driver_cars)
                <tr>
                    <th>{{ App\Helpers\ArabicShaper::shape('ماركة وموديل السيارة (Car Model)') }}</th>
                    <td>{{ App\Helpers\ArabicShaper::shape(($profile->driver_cars->brand->title ?? '-') . ' - ' . ($profile->driver_cars->model->model_name ?? '-')) }}</td>
                    <th>{{ App\Helpers\ArabicShaper::shape('سنة الصنع ولون السيارة') }}</th>
                    <td>{{ $profile->driver_cars->release_year ?? '-' }} - {{ App\Helpers\ArabicShaper::shape($profile->driver_cars->color ?? '-') }}</td>
                </tr>
                <tr>
                    <th>{{ App\Helpers\ArabicShaper::shape('رقم لوحة السيارة (License Plate)') }}</th>
                    <td colspan="3"><strong>{{ App\Helpers\ArabicShaper::shape($profile->driver_cars->car_number ?? '-') }}</strong></td>
                </tr>
            @endif
        </table>
    @endif

    <!-- Last Known Location Section -->
    <div class="section-title">{{ App\Helpers\ArabicShaper::shape('آخر موقع جغرافي تم رصده (Last Known GPS Location)') }}</div>
    <div class="map-section">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 60%; vertical-align: top; border: none; padding: 0;">
                    <p style="margin: 0 0 8px 0; font-size: 11px;">
                        {{ App\Helpers\ArabicShaper::shape('تم رصد آخر إحداثيات جغرافية لهذا المستخدم من خلال نظام التتبع الفعلي (Real-time tracking).') }}
                    </p>
                    <table style="width: 100%; border: none; font-size: 11px;">
                        <tr>
                            <td style="width: 40%; padding: 4px 0;"><strong>{{ App\Helpers\ArabicShaper::shape('خط العرض (Latitude):') }}</strong></td>
                            <td style="padding: 4px 0;">{{ $latitude ?? 'Not Available' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0;"><strong>{{ App\Helpers\ArabicShaper::shape('خط الطول (Longitude):') }}</strong></td>
                            <td style="padding: 4px 0;">{{ $longitude ?? 'Not Available' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0;"><strong>{{ App\Helpers\ArabicShaper::shape('وقت التحديث (Last Update):') }}</strong></td>
                            <td style="padding: 4px 0;">{{ $user->updated_at }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 40%; vertical-align: middle; text-align: left; border: none; padding: 0;">
                    @if($mapLink)
                        <a href="{{ $mapLink }}" class="map-link" target="_blank">{{ App\Helpers\ArabicShaper::shape('رابط خرائط جوجل (Google Maps)') }}</a>
                        <br>
                        <span style="font-size: 9px; color: #555; display: inline-block; margin-top: 5px;">
                            {{ App\Helpers\ArabicShaper::shape('اضغط على الرابط لعرض الموقع الفعلي على الخريطة مباشرة.') }}
                        </span>
                    @else
                        <span style="color: #c62828; font-weight: bold; font-size: 10px;">{{ App\Helpers\ArabicShaper::shape('تعذر تحديد الموقع الجغرافي حالياً') }}</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Official Footer Note on first page -->
    <div style="margin-top: 20px; font-size: 10px; color: #555; border: 1px solid #ccc; padding: 10px; background-color: #fafafa;">
        <strong>{{ App\Helpers\ArabicShaper::shape('إشعار قانوني هام (Legal Disclaimer):') }}</strong><br>
        {{ App\Helpers\ArabicShaper::shape('هذا المستند تم توليده تلقائياً من قاعدة بيانات شقشق كار استجابة لطلب أمني عاجل. أي تلاعب أو تزوير في البيانات الواردة في هذا التقرير يعرض فاعله للمساءلة القانونية الجنائية طبقاً لقوانين مكافحة جرائم تقنية المعلومات.') }}
    </div>

    <!-- Footer for page 1 -->
    <div class="footer">
        {{ App\Helpers\ArabicShaper::shape('شقشق كار - مستند أمني رسمي سري وخاص') }}
    </div>

    @if(count($documents) > 0)
        <!-- Page Break before Documents -->
        <div class="page-break"></div>

        <!-- Section: Uploaded Verification Documents -->
        <div class="section-title">{{ App\Helpers\ArabicShaper::shape('الملفات والمستندات الثبوتية المرفوعة (Uploaded Verification & Identity Files)') }}</div>
        <p style="font-size: 11px; margin-bottom: 15px; text-align: right;">
            {{ App\Helpers\ArabicShaper::shape('المستندات التالية هي المستندات الرسمية التي قام المستخدم برفعها على التطبيق لتوثيق حسابه ومراجعتها من قبل الإدارة.') }}
        </p>

        @foreach($documents as $index => $doc)
            @if($index > 0)
                <div class="page-break"></div>
            @endif
            
            <div class="doc-container">
                <div class="doc-title">
                    {{ App\Helpers\ArabicShaper::shape('مستند رقم ' . ($index + 1) . ': ' . $doc['name']) }}
                </div>
                @if($doc['image'])
                    <img src="{{ $doc['image'] }}" class="doc-img" alt="{{ $doc['name'] }}">
                @else
                    <div style="width: 100%; height: 200px; border: 1px dashed #ccc; line-height: 200px; text-align: center; color: #999; background-color: #fafafa;">
                        {{ App\Helpers\ArabicShaper::shape('الملف غير متوفر أو لم يتم رفعه بصيغة صورة صالحة (File not available or corrupted)') }}
                    </div>
                @endif
            </div>
        @endforeach
    @endif

</body>
</html>
