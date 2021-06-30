<?PHP
    
$streamer = "https://darrylmcoder-ytapp.herokuapp.com/stream.php?url=";

$video = isset($_REQUEST['video']) ? $streamer.$_REQUEST['video'] : null;

$audio = isset($_REQUEST['audio']) ? $streamer.$_REQUEST['audio'] : null;

$name = isset($_REQUEST['name']) ? $_REQUEST['name'].".mp4" : "output.mp4";

if(!isset($video,$audio)){
  die('Video or audio not found!');
}

$cmd = "ffmpeg -i '$video' -i '$audio' -c:v copy -c:a aac '$name'";

echo shell_exec($cmd);