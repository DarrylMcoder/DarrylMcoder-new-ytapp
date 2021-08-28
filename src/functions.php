<?PHP
    
function is_abs($url){
  return (strpos($url,"http") < 5);
}

function absify($url,$abs){
  if(strpos($url,"http") < 5){
    return $url;
  }elseif(str_starts_with($url,"//")){
    return "https:".$url;
  }else{
    return $abs.ltrim($url,"/");
  }
}