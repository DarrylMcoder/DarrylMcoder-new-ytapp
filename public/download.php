<?PHP
    
set_time_limit(0);

require('../vendor/autoload.php');

$url = isset($_GET['url']) ? $_GET['url'] : null;

if ($url == false) {
    die("No url provided");
}

$youtube = new \YouTube\YouTubeDownloader();

$links = $youtube->getDownloadLinks($url);

$formats = $links->getFirstCombinedFormat();

$name = $links->getInfo()->getTitle();

$vid_url = $formats->url;

$videoSaver = new \YouTube\VideoSaver();

$videoSaver->setDownloadedFileName($name);

$videoSaver->download($vid_url);

    
?>
