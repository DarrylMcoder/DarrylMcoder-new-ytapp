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

$playlist = new YouTube\PlaylistViewer();
$json = $playlist->getYtConfig($url);
header("Content-type: application/json");
echo $json;