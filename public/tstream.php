
<?php

require('../vendor/autoload.php');

use transloadit\Transloadit;

$url = isset($_GET['url']) ? $_GET['url'] : null;

function send_json($data)
{
    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);
    exit;
}

if (!$url) {
    send_json([
        'error' => 'No URL provided!'
    ]);
}

$youtube = new \YouTube\YouTubeDownloader();

try {
    
  sendCombinedFormats($youtube,$url);
  
} catch (\YouTube\Exception\TooManyRequestsException $e)
{

  $fixie = getenv('FIXIE_URL');
  $youtube->getBrowser->setProxy($fixie);
  sendCombinedFormats($youtube,$url);

} catch (\YouTube\Exception\YouTubeException $e) {

    send_json([
        'error' => $e->getMessage()
    ]);
} 

//functions

function sendCombinedFormats($youtube,$url){
  
  $transloadit = new Transloadit([
  "key" => "b11c6880bb4f4496ac43ecd0404eef87",
  "secret" => "6f92e4f32a94667ab352fdb7b5ec8bd21c149016",
]);
  
  $links = $youtube->getDownloadLinks($url);

  $splitStream = $links->getSplitFormats();
  
  $info = $links->getInfo();
  
  $name = $info->getTitle();
  
  $description = $info->getShortDescription();
  
  $files = [];

    $response = $transloadit->createAssembly([
      "files" => $files,
      "params" => [
        "template_id" => "119a9a6abffc4c33928464d7c2b7fe1c",
        "steps" => [
          "audio" => [
            "url" => $splitStream->audio
          ],
          "video" => [
            "url" => $splitStream->video
          ]
        ]
      ]
]);
    
    send_json([
      'links' => [
        "audio" => $splitStream->audio,
        "video" => $splitStream->video
      ],
      'name'  => [$name],
      'description' => [$description],
    ]);
  

}
