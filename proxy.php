<?php
// Obtener la URL desde GET
if (!isset($_GET['url']) || !filter_var($_GET['url'], FILTER_VALIDATE_URL)) {
    http_response_code(400);
    exit("URL inválida.");
}

$url = $_GET['url'];

// Obtener contenido con contexto que simule navegador
$opts = [
    "http" => [
        "header" => [
            "User-Agent: Mozilla/5.0",
            "Referer: https://la12hd.com/"
        ]
    ]
];
$context = stream_context_create($opts);
$contents = @file_get_contents($url, false, $context);

if ($contents === false) {
    http_response_code(500);
    exit("No se pudo cargar el contenido.");
}

// Si es un M3U8, reescribir las URLs internas para que también pasen por proxy.php
if (strpos($url, '.m3u8') !== false) {
    $base = dirname($url) . '/';
    $contents = preg_replace_callback('/^(?!#)([^\s]+)/m', function ($matches) use ($base) {
        $fullUrl = $base . $matches[1];
        return "proxy.php?url=" . urlencode($fullUrl);
    }, $contents);

    header("Content-Type: application/vnd.apple.mpegurl");
} elseif (strpos($url, '.ts') !== false) {
    header("Content-Type: video/MP2T");
}

echo $contents;
