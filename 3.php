<?php
// Paso 1: obtener el playbackURL desde la página de la12hd
$canalUrl = "https://la12hd.com/vivo/canales.php?stream=disney7";

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
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reproductor ESPN Premium</title>
</head>
<body style="margin:0;background:black;display:flex;align-items:center;justify-content:center;height:100vh;">
    <video id="video" controls autoplay width="100%" height="auto" style="max-width: 900px;"></video>

    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <script>
    const video = document.getElementById('video');
    const hls = new Hls();
    const url = "proxy.php?url=<?= urlencode($playbackURL) ?>";
    hls.loadSource(url);
    hls.attachMedia(video);
    hls.on(Hls.Events.MANIFEST_PARSED, function () {
        video.play();
    });
    </script>
</body>
</html>
