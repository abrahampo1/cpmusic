<?
$allow = array("92.176.79.233", "222.222.222", "333.333.333");
if (in_array ($_SERVER['REMOTE_ADDR'], $allow)) {
   header("location: https://info.asorey.net/");
   exit();
}
echo $_SERVER['REMOTE_ADDR'];
$servername = "localhost";
$database = "cpmusic";
$username = "root";
$password = "";
// Create connection
$link = mysqli_connect($servername, $username, $password, $database);
// Check connection
if (!$link) {
      die("Connection failed: " . mysqli_connect_error());
}