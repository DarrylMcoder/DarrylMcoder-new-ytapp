<?PHP
    
namespace YouTube;
class Download{
  protected $filename = "unnamed Darrylmcoder file";
  protected $url;
  protected $partLength = 50000;
  protected $c;
  protected $length;
  
  public function __construct($url){
    $this->url = $url;
    $this->c = curl_init($url);
    curl_setopt($this->c,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($this->c,CURLOPT_HEADER,false);
    
  }
  
  protected function getRange($start,$end = ""){
    $headers = array();
    $headers[] = "Range: bytes=$start-$end";
    curl_setopt($this->c,CURLOPT_HTTPHEADER,$headers);
    return curl_exec($this->c);
  }
  
  public function setName($name){
    $this->filename = $name;
  }
  
  public function stream(){
    header("HTTP/1.1 200 OK");
    header("Content-disposition: attachment; filename='{$this->filename}'");
    $this->length = $this->getLength();
    header("Content-length: {$this->length}");
    
    $start = 0;
    $end = $start + $this->partLength - 1;
    while(true){
      $part = $this->getRange($start,$end);
      echo $part;
      flush();
      $start += $this->partLength;
      $end   += $this->partLength;
      $end = ($end < $this->length) ? $end : "";
      if($start > $this->length){
        break;
      }
    }
  }
  
  public function __destruct(){
    curl_close($this->c);
  }
}