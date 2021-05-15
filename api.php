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
            if (isset($_POST["necesito"])) {
                $sql = "SELECT * FROM musica WHERE reproducida = 0 LIMIT 1";
                $do = mysqli_query($link, $sql);
                if (!$do->num_rows == 0) {
                    $result = mysqli_fetch_assoc($do);
                    echo $result["urlspoti"];
                } else {
                    $sql = "SELECT * FROM favoritas ORDER BY RAND() LIMIT 1";
                    if($do = mysqli_query($link, $sql)){
                        if($do->num_rows ==1){
                            $randvideo = mysqli_fetch_assoc($do);
                            $randvideo = $randvideo["yid"];
                            $sql = "INSERT INTO `musica` (`id`, `urlspoti`, `miniatura`, `titulo`, `reproducida`, `video`, `insta`, `tiempo`) VALUES (NULL, '$randvideo', '', '', '0', '', '', 0);";
                            if (mysqli_query($link, $sql)) {
                                echo $randvideo;
                            }
                        }
                    }
                    
                }
            }
            if (isset($_POST["tiempo"])) {
                $tiempo = $_POST["tiempo"];
                $url = $_POST["url"];
                $total_tiempo = $_POST["total"];
                $sql = "UPDATE `musica` SET `tiempo` = '$tiempo', `total_tiempo` = '$total_tiempo' WHERE `musica`.`urlspoti` = '$url';";
                if ($do = mysqli_query($link, $sql)) {
                    echo 'WEB: Gracias por los datos bot-chan. >///<';
                }
            }
            if (isset($_POST["miniatura"])) {
                $miniatura = $_POST["miniatura"];
                $url = $_POST["url"];
                $titulo = $_POST["titulo"];
                $videourl = $_POST["video"];
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
                $output = "temp/" . $random . ".png";
                file_put_contents($output, file_get_contents($miniatura));
                $titulo = str_replace("'", " ", $titulo);
                $sql = "SELECT * FROM musica WHERE urlspoti = '$url' and reproducida = 0 LIMIT 1;";
                $do = mysqli_query($link, $sql);
                $result = mysqli_fetch_assoc($do);
                $video_id = $result["id"];
                $sql = "UPDATE `musica` SET `miniatura` = '$random', `titulo` = '$titulo', `video` = '$videourl', `datos` = 1  WHERE `musica`.`id` = '$video_id';";
                if ($do = mysqli_query($link, $sql)) {
                    echo 'WEB: Gracias por los datos bot-chan. >///<';
                } else {
                    echo mysqli_error($link);
                }
            }
            if (isset($_POST["terminado"])) {
                $url = $_POST["terminado"];
                $sql = "SELECT * FROM musica WHERE urlspoti = '$url' and reproducida = 0 LIMIT 1;";
                $do = mysqli_query($link, $sql);
                $result = mysqli_fetch_assoc($do);
                $video_id = $result["id"];
                $sql = "UPDATE `musica` SET `reproducida` = '1' WHERE `musica`.`id` = '$video_id';";
                if ($do = mysqli_query($link, $sql)) {
                    echo 'WEB: Terminado recibido correctamente.';
                }
            }
        }
    } else {
        echo "";
    }
}
