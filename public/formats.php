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
      .mainitem{
        background-color:purple;
        color:white;
        border-radius:5px;
        padding:3px;
        text-decoration:none;
      }
    </style>
    <link rel="stylesheet" href="http://static.darrylmcoder.epizy.com/assets/style.css"/>
    <script defer src="http://static.darrylmcoder.epizy.com/assets/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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
  $combined = $links->getFirstCombinedFormat();
  $best = $links->getSplitFormats("high");
  echo "<img src='stream.php?url=".$info->videoDetails['thumbnail']['thumbnails'][0]['url']."' width='100%' ><br>";
echo "<h3>".$name."</h3><br>";
    ?>
      <a href="download.php?n=<?=$name?>&url=<?=urlencode($combined->url)?>">
        <div class="listitem mainitem">
            Download <?=$combined->qualityLabel?> video with audio: <?php echo round($combined->contentLength / 1000000,1)."mb"; ?>
        </div>
      </a>
      <a href="download.php?n=<?=$name?>&url=<?=urlencode($best->audio->url)?>">
        <div class="listitem mainitem">
            Download highest quality audio: <?php echo round($best->audio->contentLength / 1000000,1)."mb"; ?>
        </div>
      </a>
      <a href="download.php?n=<?=$name?>&url=<?=urlencode($best->video->url)?>">
        <div class="listitem mainitem">
            Download <?=$best->video->qualityLabel?> video: <?php echo round($formats[1]->contentLength / 1000000,1)."mb"; ?>
        </div>
      </a>
      <div class="morebox">
       Show full list
        <div class="morecontent">
      <?php
  foreach($formats as $key=>$format){
    echo"<div class='listitem'>";
    preg_match("#^(.*?);#i",$format->mimeType,$m);
    echo "<b>Type: ".$m[1]."<br>";
    if(isset($format->qualityLabel)){
      echo $format->qualityLabel." video<br> ";
    }else{
      echo " audio only<br>";
    }
    if(isset($format->audioQuality)){
      echo $format->audioQuality." <br>";
    }else{
      echo " No audio<br> ";
    }
    echo round($format->contentLength / 1000000,1)."mb</b><br>";
    
    echo"<a href='download.php?n=".$info->getTitle()."&url=".urlencode($format->url)."'><button class='go'>Download</button></a>";
    echo"</div>";
  }
?>
        </div>
      </div>
    </div>
    <?php echo file_get_contents("http://static.darrylmcoder.epizy.com/assets/footer.html"); ?>
  </body>
</html>
