<?PHP
    
namespace YouTube;
class CryptoStreamer extends \YouTube\YouTubeStreamer{

  public function __construct(){
    $this->crypto = new Crypto();
  }
  public function bodycallback($ch,$data){
    $data = $this->crypto->encrypt($data);
    if(true){
      echo $data;
      flush();
    }
    return strlen($data);
  }
}

?>