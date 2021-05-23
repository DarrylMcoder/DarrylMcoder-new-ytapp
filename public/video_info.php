
<?php

require('../vendor/autoload.php');

$url = isset($_GET['url']) ? $_GET['url'] : null;

function send_json($data)
{
    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);
    exit;
}

function sendCombinedFormats($youtube,$url){
  $links = $youtube->getDownloadLinks($url);

  $best = $links->getFirstCombinedFormat();
  
  $info = $links->getInfo();

  if ($best) {
        send_json([
            'links' => [$best->url],
            'name'  => [$info->getTitle()]
            'description' => [$info->getShortDescription()]
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
    
  sendCombinedFormats($youtube,$url);
  
} catch (\YouTube\Exception\YouTubeException $e) {

    send_json([
        'error' => $e->getMessage()
    ]);
} catch (\YouTube\Exception\TooManyRequestsException $e)
{

  $fixie = getenv(FIXIE_URL);
  $youtube->client->setProxy($fixie);
  sendCombinedFormats($youtube,$url);

}
