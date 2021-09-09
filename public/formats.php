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
    <div class="content">
      <div class="pagetitle">
        Download Options
      </div>
      <p>
        Select your desired download quality. <br>Remember that higher quality means considerably larger file sizes.
      </p>
<?php
  $info = $links->getInfo();
  echo "<h3>".$info->getTitle()."</h3><br>";
  //echo "<img src='stream.php?url=".."' width='100%' >";
  foreach($formats as $format){
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
