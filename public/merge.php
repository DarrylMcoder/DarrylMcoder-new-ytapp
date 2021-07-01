<?PHP
    
$streamer = "https://darrylmcoder-ytapp.herokuapp.com/stream.php?url=";

$video = $streamer.$_POST['video'];

echo $video."\n";

$audio = $streamer.$_POST['audio'];

echo $audio;

$name = "output.mp4";

if(!isset($video,$audio)){
  die('Video or audio not found!');
}

$cmd = "ffmpeg -i '$video' -i '$audio' -c:v copy -c:a aac '$name'";

shell_exec($cmd);