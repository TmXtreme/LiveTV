<?php
// Check if the request is for the proxy
if (isset($_GET['url'])) {
    // Set headers to allow cross-origin requests (CORS)
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");

    // Get the URL of the HLS stream from query parameters
    $url = $_GET['url'];

    // Fetch HLS stream
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects if any

    $response = curl_exec($ch);

    if(curl_errno($ch)) {
        http_response_code(500);
        die('Error fetching HLS stream: ' . curl_error($ch));
    }

    curl_close($ch);

    // Output HLS stream
    header('Content-Type: application/x-mpegURL');
    echo $response;
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Video Player</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #000;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .video-container {
            width: 100%;
            max-width: 800px;
            margin: auto;
            text-align: center;
            position: relative;
        }
        #player {
            width: 100%;
            height: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }
        .title {
            position: absolute;
            top: 50px;
            left: 10px;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 18px;
            z-index: 10;
        }
        .unmute-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #FFFFFF;
            color: #000000;
            border: none;
            padding: 10px 20px;
            font-size: 17px;
            border-radius: 10px;
            cursor: pointer;
            display: visible;
            z-index: 10;
        }
        footer {
            margin-top: 20px;
            text-align: center;
        }
        a {
            color: #1e90ff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: none;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/clappr@latest/dist/clappr.min.js"></script>
</head>
<body>
    <div class="video-container">
        <div class="title">JamunaTV Live by Team XTREME</div>
        <div id="player"></div>
        <button class="unmute-button" id="unmuteButton">Tap to Unmute</button>
    </div>
    <footer>
        Powered by <a href="https://t.me/tm_xtreme" target="_blank">Team XTREME</a>
    </footer>
    <script>
        var player = new Clappr.Player({
            source: 'tv.php?url=http://113.212.111.246:8080/hls//col12.m3u8',
            parentId: '#player',
            autoPlay: true,
            mute: true,
            height: '100%',
            width: '100%',
            title: 'JamunaTV Live by Team XTREME'
        });

        var unmuteButton = document.getElementById('unmuteButton');

        player.on(Clappr.Events.PLAYER_PLAY, function() {
            if (player.isMuted()) {
                unmuteButton.style.display = 'block';
            }
        });

        unmuteButton.addEventListener('click', function() {
            player.setVolume(100);
            player.unmute();
            unmuteButton.style.display = 'none';
        });
    </script>
</body>
</html>
