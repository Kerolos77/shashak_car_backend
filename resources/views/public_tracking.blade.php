<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تتبع الشحنة - شكشك</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #f8c01d; /* Shakshak Yellow */
            --primary-hover: #e0ab15;
            --dark: #121212;
            --card-bg: rgba(30, 30, 30, 0.75);
            --text: #ffffff;
            --text-muted: #aaaaaa;
            --success: #2ecc71;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            width: 100%;
            background: var(--card-bg);
            backdrop-filter: blur(15px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            padding: 30px;
            text-align: center;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            font-size: 32px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            letter-spacing: 1px;
        }

        .logo i {
            font-size: 28px;
        }

        .title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        /* Order Details Card */
        .info-card {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            text-align: right;
        }

        .info-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .info-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            font-size: 14px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 600;
        }

        .status-badge {
            background: rgba(46, 204, 113, 0.15);
            color: var(--success);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        /* Main CTA Button */
        .btn-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: var(--primary);
            color: var(--dark);
            border: none;
            padding: 15px 25px;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 8px 20px rgba(248, 192, 29, 0.2);
            margin-bottom: 30px;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(248, 192, 29, 0.35);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Store Download Badges */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: var(--text-muted);
            font-size: 12px;
            margin-bottom: 25px;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .divider:not(:empty)::before {
            margin-left: .5em;
        }

        .divider:not(:empty)::after {
            margin-right: .5em;
        }

        .store-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .store-btn {
            display: flex;
            align-items: center;
            background: #000000;
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 10px 20px;
            border-radius: 12px;
            text-decoration: none;
            color: white;
            transition: all 0.2s ease;
            text-align: right;
            justify-content: center;
            gap: 15px;
        }

        .store-btn:hover {
            background: #111111;
            border-color: var(--primary);
        }

        .store-btn i {
            font-size: 28px;
        }

        .store-btn-text {
            display: flex;
            flex-direction: column;
        }

        .store-btn-tag {
            font-size: 10px;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .store-btn-name {
            font-size: 15px;
            font-weight: 700;
        }

        .footer {
            margin-top: 30px;
            font-size: 11px;
            color: var(--text-muted);
        }

        /* Error state styling */
        .error-icon {
            font-size: 60px;
            color: #e74c3c;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo">
        <i class="fa-solid fa-car-side"></i>
        <span>شَكشَك</span>
    </div>

    @if($order)
        <h2 class="title">تتبع شحنتك المباشر</h2>
        <p class="subtitle">لتتبع مسار السائق والشحنة مباشرة على الخريطة، يرجى فتح الطلب في التطبيق. إذا لم يكن لديك التطبيق، يمكنك تحميله من الروابط أدناه.</p>

        <div class="info-card">
            <div class="info-item">
                <span class="info-label"><i class="fa-solid fa-hashtag text-warning"></i> رقم الشحنة:</span>
                <span class="info-value">#{{ $order->id }}</span>
            </div>
            <div class="info-item">
                <span class="info-label"><i class="fa-solid fa-spinner text-warning"></i> حالة الطلب:</span>
                <span class="info-value">
                    @if($order->status == 'on_trip')
                        <span class="status-badge">جاري التوصيل</span>
                    @elseif($order->status == 'completed')
                        <span class="status-badge" style="background: rgba(46, 204, 113, 0.15); color: #2ecc71;">تم التسليم</span>
                    @elseif($order->status == 'arrived')
                        <span class="status-badge" style="background: rgba(52, 152, 219, 0.15); color: #3498db;">وصل السائق للراسل</span>
                    @elseif($order->status == 'driver_on_a_way')
                        <span class="status-badge" style="background: rgba(241, 196, 15, 0.15); color: #f1c40f;">السائق في الطريق للاستلام</span>
                    @else
                        <span class="status-badge" style="background: rgba(255, 255, 255, 0.1); color: white;">{{ $order->status }}</span>
                    @endif
                </span>
            </div>
            @if($order->driver)
                <div class="info-item">
                    <span class="info-label"><i class="fa-solid fa-id-card text-warning"></i> اسم السائق:</span>
                    <span class="info-value">{{ $order->driver->full_name }}</span>
                </div>
            @endif
            @if($order->user)
                <div class="info-item">
                    <span class="info-label"><i class="fa-solid fa-user text-warning"></i> اسم الراسل:</span>
                    <span class="info-value">{{ $order->user->full_name }}</span>
                </div>
            @endif
        </div>

        <a href="shakshak://track/{{ $order->id }}" class="btn-primary">
            <i class="fa-solid fa-location-arrow"></i>
            <span>تتبع في التطبيق الآن</span>
        </a>
    @else
        <div class="error-icon">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h2 class="title">عذراً، الشحنة غير موجودة</h2>
        <p class="subtitle" style="margin-bottom: 25px;">لم نتمكن من العثور على تفاصيل الشحنة المطلوبة. قد يكون الرابط غير صحيح، أو تم حذف الطلب.</p>
    @endif

    <div class="divider">تحميل تطبيق شكشك</div>

    <div class="store-buttons">
        <a href="{{ $playStoreUrl }}" target="_blank" class="store-btn">
            <i class="fa-brands fa-google-play"></i>
            <div class="store-btn-text">
                <span class="store-btn-tag">GET IT ON</span>
                <span class="store-btn-name">Google Play</span>
            </div>
        </a>
        <a href="{{ $appStoreUrl }}" target="_blank" class="store-btn">
            <i class="fa-brands fa-apple"></i>
            <div class="store-btn-text">
                <span class="store-btn-tag">Download on the</span>
                <span class="store-btn-name">App Store</span>
            </div>
        </a>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} شكشك لتوصيل الركاب والشحنات. جميع الحقوق محفوظة.
    </div>
</div>

<script>
    // Automatic redirection attempt if order exists
    @if($order)
        window.addEventListener('DOMContentLoaded', () => {
            // Attempt to trigger the deep link
            setTimeout(() => {
                window.location.href = "shakshak://track/{{ $order->id }}";
            }, 500);
        });
    @endif
</script>

</body>
</html>
