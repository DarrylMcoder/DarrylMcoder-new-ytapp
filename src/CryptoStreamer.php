<?PHP
    
namespace YouTube;
class CryptoStreamer extends \YouTube\YouTubeStreamer{
  
  protected $body;

  public function __construct(){
    $this->crypto = new Crypto();
  }
  public function bodycallback($ch,$data){
    if(true){
      $this->body .= $data;
    }
    return strlen($data);
  }
  
  public function parseAndSend(){
    $data = $this->body;
    $preg = "#((?:href\s*=\s*|src\s*=\s*)(?:\"|\'))([\w]{3,}\.?[\w]{3,})(\"|\')#i";
    $rep = "$1https://darrylmcoder-ytapp.herokuapp.com/stream.php?url=$2$3";
    $data = preg_replace($preg,$rep,$data);
    $data = $this->crypto->encrypt($data);
    if(true){
      echo $data;
      flush();
    }
  }
  
  public function stream($url){
    parent::stream($url);
    $this->parseAndSend();
  }
}

?>