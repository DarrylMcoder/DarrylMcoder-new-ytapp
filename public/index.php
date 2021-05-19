<?php 
$url = isset($_GET['url']) ? $_GET['url'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>| my-ytapp |</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://static.rafled.com/rafled.com.css">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Matomo -->
<script type="text/javascript">
  var _paq = window._paq = window._paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="https://darrylmcoder.matomo.cloud/";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '1']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.src='//cdn.matomo.cloud/darrylmcoder.matomo.cloud/matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Code -->
  
</head>
<body>
  <br>
  <br>
  <br>
  <br>
  <h1>my-ytapp |<br> <a href="https://rafled.com">rafled.com</a></h1>
 <center>
<form action="download.php" method="get">
  <input class="input-res" type="text" name="url" placeholder="paste video url here" size="80" id="txt_url" value="<?= $url; ?>" required/><br>
    <button class="go" type="button" id="btn_fetch" onclick="unhidelol()">
      Download
  </button>
  <button class="go" type="submit"  id="btn_save" value="Save to Files">
      Save to Files
  </button>
  <a href="" id="save-link" download>
    link
  </a>
     </form>
<div style="display:none;" id="hiddenlol">
    <center>
      <div class="info" id="info">
      
      </div>
      <h3 id="title">
        
      </h3>
<video width="100%" controls>
    <source src="" type="video/mp4"/>
    <em>Sorry, your browser doesn't support HTML5 video.</em>
</video>
    
    </center>
   </div>
    <hr>
  <hr>
<script type="text/javascript">
    function unhidelol() {
   document.getElementById('hiddenlol').style.display = "block";
}
    </script>
<script>
    $(function () {
        $("#btn_fetch").click(function () {
            var url = $("#txt_url").val();
            //comment next line to disable encryption
            var url = btoa(url);
            var oThis = $(this);
            oThis.attr('disabled', true);
            $.get('video_info.php?crypt=on', {url: url}, function (data) {
                console.log(data);
                oThis.attr('disabled', false);
                var links = data['links'];
                var error = data['error'];
                var info  = data['info'];
                var name  = data['name'];
            if (error) {
                    alert('Error: ' + error);
                    return;
            }
            if(info){
                  document.getElementById("info").innerHTML = info;
            }
              
            if(name){
              var title = document.getElementById('title');
              title.innerHTML = name;
            }
                // first link with video
                var first = links.find(function (link) {
                    return link['format'].indexOf('video') !== -1;
                });
                if (typeof first === 'undefined') {
                    alert('No video found!');
                    return;
                }
                var code_url = first['url'];
                //comment next line to disable encryption
                var code_url = btoa(first['url']);
                var stream_url = 'stream.php?crypt=on&url=' + encodeURIComponent(code_url);
                var video = $("video");
                video.attr('src', stream_url);
                video[0].load();
            });
        });
    });
  
</script>

</body>
</html>

