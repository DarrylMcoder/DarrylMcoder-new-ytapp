<?PHP
    
$video = isset($_REQUEST['video']) ? $_REQUEST['video'] : null;

$audio = isset($_REQUEST['audio']) ? $_REQUEST['audio'] : null;

$name = isset($_REQUEST['name']) ? $_REQUEST['name'].".mp4" : "output.mp4";

if(!isset($video,$audio)){
  die('Video or audio not found!');
}

$cmd = "ffmpeg -i '$video' -i '$audio' -c:v copy -c:a aac '$name'";

echo shell_exec($cmd);