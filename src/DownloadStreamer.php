<?PHP
    
namespace YouTube;
class YouTubeQuickStreamer extends YouTubeStreamer{
  
  protected $filename;
  protected $partLength = 50000;
  protected $iteration = 0;
  protected $length = $this->partLength++;
  
  public function setDownloadedFileName($name){
    $this->filename = $name;
  }
  
  public function download($url){
    $this->stream($url);
  }
  
  protected function headerCallback($c,$data){
    if($this->iteration === 0){
      $preg = "#Content-Range:\sbytes\s[0-9]*?-[0-9]*?/([0-9]*?)#i";
      preg_match($preg,$data,$m);
      $this->length = $m[1];
      if(!$this->name_set){
        $this->sendHeader("Content-Disposition: attachment; filename=\"".$this->filename."\"");
        $this->name_set = true;
      }
      echo $data;
      flush();
    }
    return strlen($data);
  }
    public function stream($url)
    {
        $this->url = $url;
        $ch = curl_init();

        // otherwise you get weird "OpenSSL SSL_read: No error"
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_BUFFERSIZE, $this->buffer_size);
        curl_setopt($ch, CURLOPT_URL, $url);

        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        // we deal with this ourselves
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_HEADERFUNCTION, [$this, 'headerCallback']);

        // if response is empty - this never gets called
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, [$this, 'bodyCallback']);

        $headers = array();
        if (isset($_SERVER['HTTP_RANGE'])) {
          $headers[] = 'Range: ' . $_SERVER['HTTP_RANGE'];
          $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:49.0) Gecko/20100101 Firefox/49.0';
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_exec($ch);
        }else{
          $start = 0;
          $end = $start + $this->partLength;
          while(true){
            $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:49.0) Gecko/20100101 Firefox/49.0';
            $headers[] = "Range: bytes=$start-$end";
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_exec($ch);
            $start += $this->partLength;
            $end   += $this->partLength;
            $end = ($end < $this->length) ? $end : "";
            $this->iteration++;
            if($start > $this->length){
              break;
            }
          }
        }

        // TODO: $this->logError($ch);
        $error = ($ret === false) ? sprintf('curl error: %s, num: %s', curl_error($ch), curl_errno($ch)) : null;
        curl_close($ch);
     

        // if we are still here by now, then all must be okay
        return true;
    }
  
  protected function getLength($url){
    $c = curl_init($url);
    curl_setopt($c,CURLOPT_NOBODY,true);
    curl_setopt($c,CURLOPT_HEADER,true);
    curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
    $result = curl_exec($c);
    $preg = "#Content-length:(.*?)\s#i";
    preg_match($preg,$result,$m);
    return $m[1];
  }
}
    
?>
