<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتيجة الدفع - شكشك</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f8c01d; /* Shakshak Yellow */
            --success: #2ecc71;
            --danger: #e74c3c;
            --dark: #1e1e1e;
        }
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #121212 0%, #1a1a1a 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }
        .card {
            background: rgba(40, 40, 40, 0.8);
            backdrop-filter: blur(10px);
            padding: 3rem;
            border-radius: 24px;
            text-align: center;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            border: 1px solid rgba(255,255,255,0.1);
            animation: fadeIn 0.6s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .icon {
            font-size: 80px;
            margin-bottom: 1.5rem;
            display: inline-block;
        }
        .icon.success { color: var(--success); }
        .icon.error { color: var(--danger); }
        
        h1 { font-size: 1.8rem; margin: 0 0 1rem; }
        p { color: #aaa; margin-bottom: 2rem; }
        
        .btn {
            background: var(--primary);
            color: var(--dark);
            border: none;
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn:active { transform: scale(0.95); }
        .btn:hover { box-shadow: 0 0 15px rgba(248, 192, 29, 0.4); }

        /* Animation for Success/Failure */
        .check-container {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            position: relative;
        }
        .check-circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: var(--success);
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }
        .cross-circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: var(--danger);
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }
        @keyframes stroke {
            100% { stroke-dashoffset: 0; }
        }
    </style>
</head>
<body>
    <div class="card">
        @if($success)
            <div class="check-container">
                <svg viewBox="0 0 52 52">
                    <circle class="check-circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="check-circle" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" style="animation-delay: 0.3s"/>
                </svg>
            </div>
            <h1>تمت العملية بنجاح!</h1>
            <p>تم حفظ بيانات الدفع بنجاح. يمكنك الآن العودة للتطبيق.</p>
        @else
            <div class="check-container">
                <svg viewBox="0 0 52 52">
                    <circle class="cross-circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="cross-circle" fill="none" d="M16 16 36 36 M36 16 16 36" style="animation-delay: 0.3s"/>
                </svg>
            </div>
            <h1>عذراً، فشلت العملية</h1>
            <p>حدث خطأ أثناء معالجة عملية الدفع ({{ $data['message'] ?? 'خطأ غير معروف' }}). يرجى المحاولة مرة أخرى.</p>
        @endif

        <a href="javascript:window.close();" class="btn">إغلاق الصفحة</a>
    </div>

    <script>
        // Auto-close redirection for some mobile bridge scenarios
        setTimeout(() => {
            // Signal to mobile app if needed (e.g. by setting location to a custom scheme)
            // window.location.href = "shakshak://payment-finished?success={{ $success ? 'true' : 'false' }}";
        }, 3000);
    </script>
</body>
</html>
