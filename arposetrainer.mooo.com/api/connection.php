<?php
class dbObj
{
  var $servername = "localhost";
  var $username = "mdmrah77_posedb";
  var $password = "M123456Mm@";
  var $dbname = "mdmrah77_posedb";
  var $conn;

  function getConnstring()
  {
    $con = mysqli_connect($this->servername, $this->username, $this->password, $this->dbname) or die("Connection failed: " . mysqli_connect_error());
    /* check connection */
    if (mysqli_connect_errno()) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      exit();
    } else {
      $this->conn = $con;
    }
    return $this->conn;
  }
}
?>