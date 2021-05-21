<?PHP
    
set_time_limit(0);

require('../vendor/autoload.php');

$url = isset($_GET['url']) ? $_GET['url'] : null;

if ($url == false) {
    die("No url provided");
}

$youtube = new \YouTube\YouTubeDownloader();

$links = $youtube->getDownloadLinks($url);

$vid_url = $links->getFirstCombinedFormat()->url;

$videoSaver = new \YouTube\VideoSaver();

$videoSaver->download($vid_url);

    
?>
