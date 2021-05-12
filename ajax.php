<?php
include("database.php");
if (isset($_POST["next"])) {
    $siguiente = $_POST["next"];
    if ($siguiente != "") {
        $sql = "SELECT * FROM musica WHERE id = '$siguiente'";
        $do = mysqli_query($link, $sql);
        $result = mysqli_fetch_assoc($do);
        if ($result["reproducida"] == 1) {
            echo "terminada";
            exit;
        } else {
        }
    }
}
if (isset($_POST["nuevo"])) {
    $nuevo = $_POST["nuevo"];
    $cache = explode(";", $nuevo);
    $sql = "SELECT * FROM musica WHERE reproducida = 0 LIMIT 4";
    $do = mysqli_query($link, $sql);
    if ($do->num_rows > 0) {
        
        $video = 0;
        while ($resultado = mysqli_fetch_assoc($do)) {
            $yaesta = false;
            if ($nuevo != "") {
                if ($video > 0) {
                    for ($i = 0; $i < count($cache); $i++) {
                        if ($cache[$i] == $resultado["id"]) {
                            $yaesta = true;
                        }
                    }
                    if ($yaesta == false) {
                        echo 'nuevo';
                        exit;
                    }
                } 
            } else if ($video > 0) {
                echo 'nuevo';
                exit;
            }
            $video++;
        }
    }
}
