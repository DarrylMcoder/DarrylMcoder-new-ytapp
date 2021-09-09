<?PHP
    

set_time_limit(0);

require('../vendor/autoload.php');

$url = isset($_GET['url']) ? $_GET['url'] : null;

if(isset($_GET['crypt']) && $_GET['crypt'] == 'on'){
$url = base64_decode($url);
}

if ($url == false) {
    die("No url provided");
}

$youtube = new \YouTube\YouTubeDownloader();
try{
  $links = $youtube->getDownloadLinks($url);
} catch(\YouTube\Exception\TooManyRequestsException $e){
  $fixie = getenv('FIXIE_URL');
  $youtube->getBrowser()->setProxy($fixie);
  $links = $youtube->getDownloadLinks($url);
}
$formats = $links->getAllFormats();

?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=320, initial-scale=1">
    <meta charset="utf-8">
    <style>
      body, html {
        min-width: 100%;
        min-height: 100%;
        margin: 0;
        padding: 0;
        font: Arial 14px;
      }
    </style>
    <link rel="stylesheet" href="http://static.darrylmcoder.epizy.com/assets/style.css"/>
    <script defer src="http://static.darrylmcoder.epizy.com/assets/script.js"></script>
  </head>
  <body>
    <?php echo file_get_contents("http://static.darrylmcoder.epizy.com/assets/header.html"); ?>
    <div class="content"><br>
      <div class="pagetitle">
        Download Options
      </div>

<?php
  $info = $links->getInfo();
  $name = $info->getTitle();
  echo "<img src='stream.php?url=".$info->videoDetails['thumbnail']['thumbnails'][0]['url']."' width='100%' ><br>";
echo "<h3>".$name."</h3><br>";
    ?>
      <div class="listitem">
        Normal video with audio: <?=$formats[0]->qualityLabel; ?>
        <a href="download.php?n=<?=$name?>&url=<?=$formats[0]->url?>">
          <button class="go">
            Download
          </button>
        </a>
      </div>
      <h3>
        More options:
      </h3>
      <?php
  foreach($formats as $key=>$format){
    if($key < 1){continue;}
    echo"<div class='opts'>";
    preg_match("#^(.*?);#i",$format->mimeType,$m);
    echo"<h3>".$m[1]."</h3><br>";
    if(isset($format->qualityLabel)){
      echo"<p>".$format->qualityLabel." video<br></p>";
    }else{
      echo "<p>Audio only<br></p>";
    }
    if(isset($format->audioQuality)){
      echo $format->audioQuality."<br>";
    }else{
      echo "No audio<br>";
    }
    echo round($format->contentLength / 1000000,1)."mb<br>";
    
    echo"<a href='download.php?n=".$info->getTitle()."&url=".urlencode($format->url)."'><button class='go'>Download</button></a>";
    echo"</div>";
  }
?>
    </div>
    <?php echo file_get_contents("http://static.darrylmcoder.epizy.com/assets/footer.html"); ?>
  </body>
</html>
