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
    
    $data = preg_replace_callback( "#((?:src|(?<!a )href|action|data)\s?=\s?)(\"|')(.*?)\2#i", array("CryptoStreamer", "proxify"), $data);
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
  
  public function proxify($matches){
    $abs_url = is_absolute($matches[3]) ? $matches[3] : absify($matches[3],$this->base());
    $url = "https://darrylmcoder-ytapp.herokuapp.com";
    $url.= "/stream.php?url=";
    $url.= urlencode($abs_url);
    $return = $matches[1].$matches[2].$url.$matches[2];
    echo "\n\n<h1>".$return."</h1>\n\n\n";
    return $return;
  }
  
  protected function base(){
    $url = $this->url;
    $file_info = pathinfo($url);
    return isset($file_info['extension'])
        ? str_replace($file_info['filename'] . "." . $file_info['extension'], "", $url)
        : $url;
  }
}

?>