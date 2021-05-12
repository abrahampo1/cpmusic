<?php
function generateRandomString($length = 20)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
if (isset($_POST["api"])) {
    include("database.php");
    $token = $_POST["api"];
    $sql = "SELECT * FROM api WHERE BINARY token = '$token'";
    if ($do = mysqli_query($link, $sql)) {
        if ($do->num_rows > 0) {
            if (isset($_POST["miniatura"])) {
                $miniatura = $_POST["miniatura"];
                $url = $_POST["url"];
                $titulo = $_POST["titulo"];
                $random = generateRandomString();
                $con = true;
                while ($con == true) {
                    $coincidencia = "SELECT * FROM musica WHERE BINARY miniatura = '$random'";
                    $do = mysqli_query($link, $coincidencia);
                    if ($do->num_rows > 0) {
                        $con = true;
                        $random = generateRandomString();
                    } else {
                        $con = false;
                    }
                }
                $output = "temp/".$random.".png";
                file_put_contents($output, file_get_contents($miniatura));
                $sql = "UPDATE `musica` SET `miniatura` = '$random', `titulo` = '$titulo'  WHERE `musica`.`urlspoti` = '$url';";
                $do = mysqli_query($link, $sql);
            } else if (isset($_POST["terminado"])) {
                $url = $_POST["terminado"];
                $sql = "UPDATE `musica` SET `reproducida` = '1' WHERE `musica`.`urlspoti` = '$url';";
                $do = mysqli_query($link, $sql);
            } else {
                $sql = "SELECT * FROM musica WHERE reproducida = 0 LIMIT 1";
                $do = mysqli_query($link, $sql);
                if (!$do->num_rows == 0) {
                    $result = mysqli_fetch_assoc($do);
                    echo $result["urlspoti"];
                } else {
                    echo "";
                }
            }
        } else {
            echo "";
        }
    }
}
