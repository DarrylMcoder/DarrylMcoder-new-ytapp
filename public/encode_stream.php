<?PHP

set_time_limit(0);

require('../vendor/autoload.php');
require('../src/CryptoStreamer.php');
    
$url = isset($_GET['url']) ? $_GET['url'] : null;

if(!isset($url)){
  die('No url provided!');
}
$crypto_streamer = new CryptoStreamer();
$crypto_streamer->stream($url);

?>