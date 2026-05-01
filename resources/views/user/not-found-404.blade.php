<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sayfa Bulunamadı - 404</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        .container { max-width: 600px; margin: 0 auto; }
        h1 { color: #e74c3c; font-size: 48px; margin-bottom: 20px; }
        p { color: #555; font-size: 18px; margin-bottom: 30px; }
        a { background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        a:hover { background: #2980b9; }
    </style>
</head>
<body>
<div class="container">
    <h1>404</h1>
    <h2>Sayfa Bulunamadı</h2>
    <p>Aradığınız sayfa mevcut değil veya taşınmış olabilir.</p>
    <a href="{{ url('/') }}">Ana Sayfaya Dön</a>

    <hr style="margin: 40px 0;">

    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 30px;">
        <h3>🤖 GEO Test Sayfaları:</h3>
        <p><a href="{{ url('/test-geo') }}" style="background: #28a745;">GEO Middleware Test</a></p>
    </div>
</div>
</body>
</html>
