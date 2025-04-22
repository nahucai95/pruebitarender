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

// Mostrar solo el <script> con la variable
header("Content-Type: text/html; charset=UTF-8");
echo '<script>
var playbackURL = "' . htmlspecialchars($proxyURL, ENT_QUOTES, 'UTF-8') . '";
</script>';
