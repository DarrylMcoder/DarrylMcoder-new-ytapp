<?PHP
    
$streamer = "https://darrylmcoder-ytapp.herokuapp.com/stream.php?url=";

var_dump($_REQUEST['video']);

$video = $streamer.$_REQUEST['video'][0];

$audio = $streamer.$_REQUEST['audio'][0];

$name = "output.mp4";

if(!isset($video,$audio)){
  die('Video or audio not found!');
}

$cmd = "ffmpeg -i '$video' -i '$audio' -c:v copy -c:a aac '$name'";

echo "Output:".shell_exec($cmd);