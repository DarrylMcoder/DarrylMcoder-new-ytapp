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

function crypt_enable(){
  if($_COOKIE['crypt_enabled'] === "on"){
    //javascript request
    $opts = array('http' =>
    array(
        'header'  => 'User-agent: Mozilla/5.0 (iPhone; CPU iPhone OS 14_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.3 Mobile/15E148 Safari/604.1\r\n'
      .'Cookie: crypt_enabled=off\r\n',
     
        'ignore_errors' => true,
    )
);
    $context = stream_context_create($opts);
    //stream($url, $context);
    $result = file_get_contents(getURL(),false,$context);
    $crypto = new \YouTube\Crypto();
    echo $crypto->encrypt($result);
    exit;
    
  }elseif($_COOKIE['crypt_enabled'] === "off"){
    //encoding proxy request
    return;
  }elseif(!isset($_COOKIE['crypt_enabled'])){
    //new client request. 
    //Send javascript encoding functions
    echo "<!DOCTYPE html>
<html>
  <head>
   
  </head>
  <body>
    <script type=\"text/javascript\">
     window.onload = (function() {
        var x = new XMLHttpRequest();
        x.open(\"GET\",location.href);
        x.setRequestHeader(\"Cookie\",\"crypt_enabled=on;\");
        
        x.onreadystatechange = function() {
          if(x.readyState === 4) {
            if(x.status !== 200) {
              alert(x.status);
            }
            document.write(decrypt(x.responseText,\"WERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm1234567890Q\"));
            
          }
        };
        x.send();
        
        function decrypt(crypted,key) {
        var alpha = \"QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm1234567890\";
        var decrypted_str = \"\";
        var found = false;
        for(var i = 0; i < crypted.length; i++) {
          var crypted_val = crypted.charAt(i);
          for(var j = 0; j < key.length; j++){
            var key_val = key.charAt(j);
            var alpha_val = alpha.charAt(j);
            if(key_val == crypted_val) {
              decrypted_str += alpha_val;
              found = true;
            }
          }
          if(found != true) {
            decrypted_str += crypted_val;
          }
          found = false;
        }
        
        return decrypted_str;
      }
      })
    </script>
  </body>
</html>
";
    exit;
  }
}


function getURL(){
  if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
    $url = "https://";   
  else  
    $url = "http://";   
  // Append the host(domain name, ip) to the URL.   
  $url.= $_SERVER['HTTP_HOST'];   
    
  // Append the requested resource location to the URL   
  $url.= $_SERVER['REQUEST_URI'];
  return $url;
}