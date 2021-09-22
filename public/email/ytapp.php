<?PHP
    
require('../../vendor/autoload.php');
$imap_driver = new \YouTube\imap_driver();
$imap_driver->full_debug = true;
if ($imap_driver->init('ssl://imap.gmail.com', 993) === false) {
    echo "init() failed: " . $imap_driver->error . "\n";
    exit;
}

if ($imap_driver->login('darrylmcoder.ytapp@gmail.com', 'ytapp@ytapp') === false) {
    echo "login() failed: " . $imap_driver->error . "\n";
    exit;
}

if ($imap_driver->select_folder("INBOX") === false) {
    echo "select_folder() failed: " . $imap_driver->error . "\n";
    return false;
}

$ids = $imap_driver->get_uids_by_search('UNSEEN');
if ($ids === false)
{
    echo "get_uids_failed: " . $imap_driver->error . "\n";
    exit;
}

foreach($ids as $uid){
  $headers = $imap_driver->get_these_headers($uid, "FROM SUBJECT");
  if($headers === false){
    echo "get_these_headers() failed: " . $imap_driver->error. "\n";
    exit;
  }
  var_dump($headers);
  $from = $headers['from'];
  $subj = $headers['subject'];
  $url = $subj;
  $browser = new \YouTube\Browser();
  $page = $browser->get($url);
  if(mail($from,"Email Browser",$page->body) === false){
    echo "mail() failed:";
    exit;
  }
}