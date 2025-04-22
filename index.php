<?php
if (!isset($_GET['url']) || empty($_GET['url'])) {
    die("Canal no especificado");
}

$canalID = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['url']);
$canalUrl = "https://la12hd.com/vivo/canales.php?stream=" . $canalID;

$options = [
    "http" => [
        "header" => [
            "User-Agent: Mozilla/5.0",
            "Referer: https://la12hd.com/"
        ]
    ]
];

$context = stream_context_create($options);
$response = @file_get_contents($canalUrl, false, $context);

if (!$response || !preg_match('/var\s+playbackURL\s*=\s*"([^"]+)"/', $response, $match)) {
    die("No se pudo obtener el playbackURL");
}

$playbackURL = $match[1];
$proxyURL = 'proxy.php?url=' . urlencode($playbackURL);

// Mostramos el enlace en pantalla
echo '<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Enlace Proxy</title></head>
<body style="font-family:Arial;padding:20px;">
    <p>Enlace listo para usar en el reproductor:</p>
    <a href="' . htmlspecialchars($proxyURL) . '" target="_blank">' . htmlspecialchars($proxyURL) . '</a>
</body>
</html>';
