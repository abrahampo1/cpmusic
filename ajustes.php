<?php
function ajuste($name){
    include("database.php");
    $sql = "SELECT * FROM ajustes WHERE nombre = '$name'";
    $do = mysqli_query($link, $sql);
    $result = mysqli_fetch_assoc($do);
    return $result["value"];
}