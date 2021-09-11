<?PHP
    

set_time_limit(0);

require('../vendor/autoload.php');

$url = isset($_GET['url']) ? $_GET['url'] : null;

if(isset($_GET['crypt']) && $_GET['crypt'] == 'on'){
$url = base64_decode($url);
}

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
      
      #mainitem{
        background-color: white;
        border-radius:50px;
        color:black;
        vertical-align:center;
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
        Video Downloader
      </div>

<?php
      
if(isset($url) :

$youtube = new \YouTube\YouTubeDownloader();
try{
  $links = $youtube->getDownloadLinks($url);
} catch(\YouTube\Exception\TooManyRequestsException $e){
  $fixie = getenv('FIXIE_URL');
  $youtube->getBrowser()->setProxy($fixie);
  $links = $youtube->getDownloadLinks($url);
}
  $formats = $links->getAllFormats();
  $info = $links->getInfo();
  $name = $info->getTitle();
  $combined = $links->getFirstCombinedFormat();
  $best = $links->getSplitFormats("high");
  echo "<img src='stream.php?url=".$info->videoDetails['thumbnail']['thumbnails'][0]['url']."' width='100%' ><br>";
echo "<h3>".$name."</h3><br>";
    ?>
      <a href="download.php?n=<?=$name?>&url=<?=urlencode($combined->url)?>">
        <div class="listitem" id="mainitem">
          <b>
            Download <?=$combined->qualityLabel?> video with audio: <?php echo round($combined->contentLength / 1000000,1)."mb"; ?>
          </b>
        </div>
      </a>
      <a href="download.php?n=<?=$name?>&url=<?=urlencode($best->audio->url)?>">
        <div class="listitem" id="mainitem">
          <b>
            Download highest quality audio: <?php echo round($best->audio->contentLength / 1000000,1)."mb"; ?>
          </b>
        </div>
      </a>
      <a href="download.php?n=<?=$name?>&url=<?=urlencode($best->video->url)?>">
        <div class="listitem" id="mainitem">
          <b>
            Download <?=$best->video->qualityLabel?> video: <?php echo round($formats[1]->contentLength / 1000000,1)."mb"; ?>
          </b>
        </div>
      </a><br>
      <div class="morebox">
        <button class="morebtn">
          Show full list
        </button>
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
      <?php elseif(!isset($url)) : ?>

      
    <a href="https://t.me/joinchat/zGTCgHpvKN9iMWYx">
      <img src="stream.php?url=http://ytapp.darrylmcoder.epizy.com/img/telegram.png" width="50" height="50"/><img src="stream.php?url=http://ytapp.darrylmcoder.epizy.com/img/cloudveil.png" width="50" height="50"><br>
    Join our Telegram group!
    </a><br><br>
    <form action="" method="get">
  <input type="text" value= "<?=$_GET['url']; ?>" class="input" id="txt_url" name="url"><br>
  <button type="button" class="go" id="btn_fetch" value="Fetch">
  Fetch
  </button>
  <button type="submit" class="go">
    Save
  </button>
</form>
    <h3 id="name">
      
    </h3>

  <video width="100%" controls>
    <source src="" type="video/mp4"/>
    <em>Sorry, your browser doesn't support HTML5 video.</em>
</video>
    
    <p id="description">
      
    </p>


<script>
    $(function () {

        $("#btn_fetch").click(function () {

            var url = $("#txt_url").val();

            var oThis = $(this);
            oThis.attr('disabled', true);

            $.get('video_info.php', {url: url}, function (data) {

                console.log(data);

                oThis.attr('disabled', false);

                var links = data['links'];
                var error = data['error'];
                var name = data['name'];
                var description = data['description'];
              
                $("#name").html(name);
                $("#description").html(description);

                if (error) {
                    alert('Error: ' + error);
                    return;
                }

                // first link with video
                var first = links[0];

                if (typeof first === 'undefined') {
                    alert('No video found!');
                    return;
                }

                var stream_url = 'stream.php?url=' + encodeURIComponent(first);

                var video = $("video");
                video.attr('src', stream_url);
                video[0].load();
               var today = new Date();
          var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
          var time = today.getHours() + ":" + today.getMinutes();
          var dateTime = date+' '+time;
          
          
              $.post("log.php",{
                download_date: dateTime,
                name: name,
                url: url
              },function(data) {
                //process result here
              });
            });

        });

    });
  
  function isPlaylist(url){
    if(url.includes('/playlist')){
      return true;
    }else{
      return false;
    }
  }
  
    
  function isVideo(url){
    if(url.includes('/watch')){
      return true;
    }else{
      return false;
    }
  }
</script>
      
      
      <?php endif; ?>
      
    </div>
    <?php echo file_get_contents("http://static.darrylmcoder.epizy.com/assets/footer.html"); ?>
  </body>
</html>
