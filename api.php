<?php
header("Access-Control-Allow-Origin: *");
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
include("database.php");
if(isset($_POST["getapi"])){
    $api = generateRandomString(30);
    $sql = "INSERT INTO `api` (`id`, `token`) VALUES (NULL, '$api');";
    if(mysqli_query($link, $sql)){
        echo $api;
    }
}
if (isset($_POST["api"])) {
    $sql = "SELECT * FROM ajustes WHERE nombre = 'timer'";
    $do = mysqli_query($link, $sql);
    $result = mysqli_fetch_assoc($do);
    $timer = 0;
    $stop = 23;
    $start = 0;
    if ($result["value"] == 1) {
        $timer = 1;
        $sql = "SELECT * FROM ajustes WHERE nombre = 'start_hour'";
        $do = mysqli_query($link, $sql);
        $result = mysqli_fetch_assoc($do);
        $start = $result["value"];
        $sql = "SELECT * FROM ajustes WHERE nombre = 'stop_hour'";
        $do = mysqli_query($link, $sql);
        $result = mysqli_fetch_assoc($do);
        $stop = $result["value"];
    }
    $hora = date('G', time());
    $token = $_POST["api"];
    $sql = "SELECT * FROM api WHERE BINARY token = '$token'";
    if ($do = mysqli_query($link, $sql)) {
        if ($do->num_rows > 0) {
            if(isset($_POST["test"])){
                echo "OK";
                exit;
            }
            if ($hora < $stop && $hora > $start || $timer == 0) {
                if (isset($_POST["necesito"])) {
                    $sql = "UPDATE `ajustes` SET `value` = 'play' WHERE `ajustes`.`nombre` = 'status';";
                    mysqli_query($link, $sql);
                    $sql = "SELECT * FROM musica WHERE reproducida = 0 LIMIT 1";
                    $do = mysqli_query($link, $sql);
                    if (!$do->num_rows == 0) {
                        $result = mysqli_fetch_assoc($do);
                        echo $result["urlspoti"];
                    } else {
                        $sql = "SELECT * FROM ajustes WHERE nombre = 'playlist'";
                        $do = mysqli_query($link, $sql);
                        $result = mysqli_fetch_assoc($do);
                        $playlist_sel = $result["value"];
                        $sql = "SELECT * FROM ajustes WHERE nombre = 'playlist_active'";
                        $do = mysqli_query($link, $sql);
                        $result = mysqli_fetch_assoc($do);
                        $playlist_active = $result["value"];
                        if ($playlist_active == 1) {
                            $sql = "SELECT * FROM favoritas WHERE playlist = '$playlist_sel' ORDER BY RAND() LIMIT 1";
                            if ($do = mysqli_query($link, $sql)) {
                                if ($do->num_rows >= 1) {
                                    while ($randvideo = mysqli_fetch_assoc($do)) {
                                        $match = false;
                                        while ($match == false) {
                                            $randvideo = $randvideo["yid"];
                                            $sql = "SELECT * FROM musica ORDER BY id DESC LIMIT 15";
                                            $busqueda =  mysqli_query($link, $sql);
                                            while ($videomatch = mysqli_fetch_assoc($busqueda)) {
                                                if ($videomatch["urlspoti"] == $randvideo) {
                                                    $match = true;
                                                }
                                            }
                                            if ($match == false) {
                                                break;
                                            }
                                            if ($match == true) {
                                                $sql = "SELECT * FROM favoritas WHERE playlist = '$playlist_sel' ORDER BY RAND() LIMIT 1";
                                                $nuevo = mysqli_query($link, $sql);
                                                $randvideo = mysqli_fetch_assoc($nuevo);
                                                $match = false;
                                            }
                                        }
                                        $sql = "SELECT * FROM musica WHERE urlspoti = '$randvideo'";
                                        $busq = mysqli_query($link, $sql);
                                        $result_video = mysqli_fetch_assoc($busq);
                                        $insta = $result_video["insta"];

                                        $sql = "INSERT INTO `musica` (`id`, `urlspoti`, `miniatura`, `titulo`, `reproducida`, `video`, `insta`, `tiempo`, `auto`) VALUES (NULL, '$randvideo', '', '', '0', '', '$insta', 0, 1);";
                                        if (mysqli_query($link, $sql)) {
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (isset($_POST["necesito_discord"])) {
                $sql = "SELECT * FROM musica WHERE reproducida = 0 and datos = 1 LIMIT 1";
                $do = mysqli_query($link, $sql);
                if ($do->num_rows == 1) {
                    $result = mysqli_fetch_assoc($do);
                    echo $result["id"] . ";,;" . $result["tiempo"] . ";,;" . $result["urlspoti"];
                }
            }
            if (isset($_POST["tiempo"])) {
                $tiempo = $_POST["tiempo"];
                $url = $_POST["url"];
                $total_tiempo = $_POST["total"];
                $sql = "UPDATE `musica` SET `tiempo` = '$tiempo', `total_tiempo` = '$total_tiempo' WHERE `musica`.`urlspoti` = '$url';";
                if ($do = mysqli_query($link, $sql)) {
                    $sql = "SELECT * FROM ajustes WHERE nombre = 'status'";
                    $so = mysqli_query($link, $sql);
                    $result = mysqli_fetch_assoc($so);
                    echo $result["value"];
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
        }else{
            if(isset($_POST["test"])){
                echo "NO";
                exit;
            }
        }
    } else {
        echo "";
    }
}

if (isset($_POST["proponer"])) {
    $url = $_POST["proponer"];
    $insta = $_POST["insta"];
    $sql = "INSERT INTO `musica` (`id`, `urlspoti`, `miniatura`, `titulo`, `reproducida`, `video`, `insta`, `tiempo`, `auto`) VALUES (NULL, '$url', '', '', '0', '', '$insta', 0, 1);";
    if (mysqli_query($link, $sql)) {
        echo "Se ha añadido " . $url . " correctamente a la cola!";
    }
}
if (isset($_GET["getplaydata"])) {
    $sql = "SELECT * FROM musica WHERE reproducida = 0 and datos = 1";
    $do = mysqli_query($link, $sql);
    $result = mysqli_fetch_assoc($do);
    if ($result["insta"] == "") {
        $insta = "franciscoasorey";
    } else {
        $insta = $result["insta"];
    }
    $player = (object)array();
    $player->title = $result["titulo"];
    $player->miniatura = $result["miniatura"];
    $player->instagram = $insta;
    echo json_encode($player);
}

if (isset($_GET["next"])) {
    $api = $_GET["next"];
    $sql = "SELECT * FROM ajustes WHERE BINARY value = '$api'";
    if ($do = mysqli_query($link, $sql)) {

        $player = (object)array();
        if ($do->num_rows != 0) {
            $sql = "UPDATE `ajustes` SET `value` = 'next' WHERE `ajustes`.`nombre` = 'status';";
            if (mysqli_query($link, $sql)) {
                if (isset($_GET["url"])) {
                    $rickroll = $_GET["url"];
                    $sql = "INSERT INTO `musica` (`id`, `urlspoti`, `miniatura`, `titulo`, `reproducida`, `video`, `insta`, `tiempo`, `auto`) VALUES (NULL, '$rickroll', '', '', '0', '', '$insta', 0, 1);";
                    if (mysqli_query($link, $sql)) {
                        $player->error = "OK";
                        $player->message = "Acabo de chipear todas las pantallas. je. je. je.";
                    } else {
                        $player->error = "NOT OK";
                        $player->message = "Error en la base de datos.";
                    }
                } else {
                    $player->error = "OK";
                    $player->message = "Se ha cambiado la canción.";
                }
            } else {
                $player->error = "NOT OK";
                $player->message = "Error en la base de datos.";
            }
        } else {
            $player->error = "NOT OK";
            $player->message = "La clave API es incorrecta.";
        }

        echo json_encode($player);
    } else {
        echo mysqli_error($link);
    }
}

if (isset($_GET["volume"])) {
    $api = $_GET["volume"];
    $volume = $_GET["value"];
    $sql = "SELECT * FROM ajustes WHERE BINARY value = '$api'";
    if ($do = mysqli_query($link, $sql)) {

        $player = (object)array();
        if ($do->num_rows != 0) {
            $sql = "UPDATE `ajustes` SET `value` = '$volume' WHERE `ajustes`.`nombre` = 'volume';";
            if (mysqli_query($link, $sql)) {
                $player->error = "OK";
                $player->message = "Se ha ajustado el volumen la canción.";
            } else {
                $player->error = "NOT OK";
                $player->message = "Error en la base de datos.";
            }
        } else {
            $player->error = "NOT OK";
            $player->message = "La clave API es incorrecta.";
        }

        echo json_encode($player);
    } else {
        echo mysqli_error($link);
    }
}
