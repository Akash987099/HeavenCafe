<?php

$endpoint = 'https://awcai.cloud/script/cafe/rider/login';
$response = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = [
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
    ];

    $ch = curl_init($endpoint);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($payload),
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
        ],
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
    }

    curl_close($ch);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Login Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 40px 16px;
        }
        .card {
            max-width: 640px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid #d0d7de;
            border-radius: 8px;
            box-sizing: border-box;
        }
        button {
            background: #0d6efd;
            color: #fff;
            border: 0;
            padding: 12px 18px;
            border-radius: 8px;
            cursor: pointer;
        }
        pre {
            background: #111827;
            color: #f9fafb;
            padding: 16px;
            border-radius: 10px;
            overflow: auto;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Rider Login API Test</h2>
        <p>Endpoint: <?php echo htmlspecialchars($endpoint, ENT_QUOTES, 'UTF-8'); ?></p>

        <form method="post">
            <input type="email" name="email" placeholder="Enter rider email" required>
            <input type="text" name="password" placeholder="Enter password if needed">
            <button type="submit">Call API</button>
        </form>

        <?php if ($error): ?>
            <pre><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></pre>
        <?php endif; ?>

        <?php if ($response !== null): ?>
            <pre><?php echo htmlspecialchars($response, ENT_QUOTES, 'UTF-8'); ?></pre>
        <?php endif; ?>
    </div>
</body>
</html>
