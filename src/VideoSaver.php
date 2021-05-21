<?PHP
    
namespace YouTube;
class VideoSaver extends YouTubeStreamer {
  
  protected $file_name;
  
  public function headerCallback($ch,$data){
    parent::headerCallback($ch,$data);
    $name = $this->file_name;
    $this->sendHeader("Content-Disposition: attachment; filename=\"".$name."\"");
  }
  
  public function setDownloadedFileName($name){
    $this->file_name = $name;
  }
  
  public function download($url){
    parent::stream($url);
  }
}
    
?>
