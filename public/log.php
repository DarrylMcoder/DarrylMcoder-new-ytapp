<?PHP
    
include('database_config.php');

$download_date = isset($_POST['download_date']) ? $_POST['download_date'] : null;

$name = isset($_POST['name']) ? $_POST['name'] : null;

$url = isset($_POST['url']) ? $_POST['url'] : null;

$sql = "CREATE TABLE IF NOT EXISTS Videos(
  ID INT AUTO_INCREMENT,
  download_date VARCHAR(255),
  name VARCHAR(255) UNIQUE,
  downloads INT,
  url VARCHAR(1000) UNIQUE,
  PRIMARY KEY(ID)
)";

$mysqli->query($sql);

$sql = "INSERT INTO Videos(
download_date,name,downloads,url) 
VALUES('$download_date','$name',1,'$url') ON DUPLICATE KEY UPDATE downloads = downloads + 1,download_date = '$download_date'";

$mysqli->query($sql);