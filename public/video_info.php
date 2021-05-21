
<?php

require('../vendor/autoload.php');

$url = isset($_GET['url']) ? $_GET['url'] : null;

function send_json($data)
{
    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);
    exit;
}

function sendCombinedFormats($youtube){
  $links = $youtube->getDownloadLinks($url);

  $best = $links->getFirstCombinedFormat();

  if ($best) {
        send_json([
            'links' => [$best->url]
        ]);
  } else {
        send_json(['error' => 'No links found']);
  }

}

if (!$url) {
    send_json([
        'error' => 'No URL provided!'
    ]);
}

$youtube = new \YouTube\YouTubeDownloader();

try {
    
  sendCombinedFormats($youtube);
  
} catch (\YouTube\Exception\YouTubeException $e) {

    send_json([
        'error' => $e->getMessage()
    ]);
} catch (\YouTube\Exception\TooManyRequestsException $e)
{

  $fixie = getenv(FIXIE_URL);
  $youtube->client->setProxy($fixie);
  sendCombinedFormats($youtube);

}
