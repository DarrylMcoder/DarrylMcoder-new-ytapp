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
       <?=$formats[0]->qualityLabel?> video with audio. <?php echo round($formats[0]->contentLength / 1000000,1)."mb"; ?>
        <a href="download.php?n=<?=$name?>&url=<?=urlencode($formats[0]->url)?>">
          <button class="go">
            Download
          </button>
        </a>
      </div>
      <div class="listitem">
        Medium quality audio, <?php echo round($formats[19]->contentLength / 1000000,1)."mb"; ?>
        <a href="download.php?n=<?=$name?>&url=<?=urlencode($formats[19]->url)?>">
          <button class="go">
            Download
          </button>
        </a>
      </div>
      <div class="listitem">
        <?=$formats[1]->qualityLabel?> video, <?php echo round($formats[1]->contentLength / 1000000,1)."mb"; ?>
        <a href="download.php?n=<?=$name?>&url=<?=urlencode($formats[1]->url)?>">
          <button class="go">
            Download
          </button>
        </a>
      </div>
      <div class="morebox">
       Show full list
        <div class="morecontent">
      <?php
  foreach($formats as $key=>$format){
    if($key < 1){continue;}
    echo"<div class='listitem'>";
    preg_match("#^(.*?);#i",$format->mimeType,$m);
    echo $m[1];
    if(isset($format->qualityLabel)){
      echo $format->qualityLabel;
    }else{
      echo "";
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
      </div>
    </div>
    <?php echo file_get_contents("http://static.darrylmcoder.epizy.com/assets/footer.html"); ?>
  </body>
</html>
