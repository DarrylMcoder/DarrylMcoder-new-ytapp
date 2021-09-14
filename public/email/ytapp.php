<?PHP
    
require('../../vendor/autoload.php');
$imap_driver = new \YouTube\imap_driver();
$imap_driver->full_debug = true;

$imap_driver = new imap_driver();
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
  if (($headers = $imap_driver->get_headers_from_uid($uid)) === false) {
    echo "get_headers_by_uid() failed: " . $imap_driver->error . "\n";
    return false;
}
  var_dump($headers);
  $from = $headers['from'];
}