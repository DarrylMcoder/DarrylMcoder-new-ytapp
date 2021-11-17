<?PHP

ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 1); 
set_time_limit(0);
    
$streamer = "https://darrylmcoder-ytapp.herokuapp.com/stream.php?url=";

var_dump($_REQUEST['video']);

$video = $streamer.$_REQUEST['video'];

$audio = $streamer.$_REQUEST['audio'];

$name = "output.mp4";
var_dump($video);

if(!isset($video,$audio)){
  die('Video or audio not found!');
}

$cmd = "ffmpeg -i '$video' -i '$audio' -c:v copy -c:a aac '$name'";

echo "Output:".shell_exec($cmd);