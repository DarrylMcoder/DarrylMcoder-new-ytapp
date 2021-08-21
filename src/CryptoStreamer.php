<?PHP
    
namespace YouTube;
class CryptoStreamer extends \YouTube\YouTubeStreamer{

  public function __construct(){
    $this->crypto = new Crypto();
  }
  public function bodycallback($ch,$data){
    $data = $this->crypto->encrypt($data);
    $preg = "#((?:href\s*=\s*|src\s*=\s*)(?:\"|\'))([\w]{3,}\.?[\w]{3,})(\"|\')#i";
    $rep = "$1https://darrylmcoder-ytapp.herokuapp.com/stream.php?url=$2$3";
    $data = preg_replace($preg,$rep,$data);
    if(true){
      echo $data;
      flush();
    }
    return strlen($data);
  }
}

?>