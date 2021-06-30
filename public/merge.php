<?PHP
    
$streamer = "https://darrylmcoder-ytapp.herokuapp.com/stream.php?url=";

$video = isset($_POST['video']) ? $streamer.$_POST['video'] : null;

echo $video."\n";

foreach($video as $val){
  echo $val."\n\n\n";
}

$audio = isset($_POST['audio']) ? $streamer.$_POST['audio'] : null;

echo $audio;

$name = "output.mp4";

if(!isset($video,$audio)){
  die('Video or audio not found!');
}

$cmd = "ffmpeg -i '$video' -i '$audio' -c:v copy -c:a aac '$name'";

shell_exec($cmd);