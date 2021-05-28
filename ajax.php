<?php
session_start();
include("database.php");
if (isset($_POST["next"])) {
    $siguiente = $_POST["next"];
    if ($siguiente != "") {
        $sql = "SELECT * FROM musica WHERE id = '$siguiente'";
        $do = mysqli_query($link, $sql);
        $result = mysqli_fetch_assoc($do);
        if ($result["reproducida"] == 1) {
            $sql = "SELECT * FROM musica WHERE reproducida = 0 and datos = 1";
            $do = mysqli_query($link, $sql);
            if ($do->num_rows > 0) {
                echo 'terminada';
                exit;
            }
            $sql = "SELECT * FROM musica WHERE reproducida = 0 and datos = 0";
            $do = mysqli_query($link, $sql);
            if ($do->num_rows == 0) {
                echo 'terminada';
                exit;
            }
            exit;
        } else {
        }
    } else {
        $sql = "SELECT * FROM musica WHERE reproducida = 0 and datos = 1";
        $do = mysqli_query($link, $sql);
        if ($do->num_rows > 0) {
            echo 'terminada';
            exit;
        }
    }
}
if (isset($_POST["nuevo"])) {
    $sql = "SELECT * FROM musica WHERE reproducida = 0 and datos = 0 LIMIT 3";
    $do = mysqli_query($link, $sql);
    if ($do->num_rows > 0) {
        //echo '<h1 style="width:100%" class="siguiente_text"></h1>';
    }
    $video = 1;
    while ($video_query = mysqli_fetch_assoc($do)) {
        $video_id = explode("?v=", $video_query["urlspoti"]);
        $video_id = $video_id[1];
        $thumbnail = "https://img.youtube.com/vi/" . $video_id . "/mqdefault.jpg";
        if ($video == 1) {
            $ultima_html = '<div class="slide-in-right centered"><h1 id="siguiente_texto" onload="siguiente_en()"></h1></div>';
        } else {
            $ultima_html = "";
        }

        echo '<div class="container"><img style="z-index: 1;" class="siguiente siguiente-img" id="img-'.$video.'" src="' . $thumbnail . '" height="auto" width="100%" alt="">' . $ultima_html . '</div><br>';
        $video++;
    }
}
if (isset($_POST["anuncio"])) {
    $sql = "SELECT * FROM ajustes WHERE nombre = 'anuncio_active'";
    $do = mysqli_query($link, $sql);
    $value = mysqli_fetch_assoc($do);
    if ($value["value"] == 1) {
        $sql = "SELECT * FROM ajustes WHERE nombre = 'anuncio'";
        $do = mysqli_query($link, $sql);
        $value = mysqli_fetch_assoc($do);
        echo $value["value"];
    }

    exit;
}

if (isset($_POST["anteriores"])) {
    $sql = "SELECT * FROM musica WHERE reproducida = 1 ORDER BY id DESC LIMIT 15";
    $do = mysqli_query($link, $sql);
    echo "<h2>15 Anteriores</h2>";
    while ($video = mysqli_fetch_assoc($do)) {
        $videoId = explode("?v=", $video["urlspoti"]);
        $videoId = $videoId[1];
        $title = $video["titulo"];
        $title = str_replace('"', "", $title);
        $title = str_replace("&", "&amp;", $title);
        $description = $video["insta"];
        $description = str_replace("@", "", $description);
        if ($description == "") {
            $description = "franciscoasorey";
        }
        $url = "https://www.youtube.com/watch?v=" . $videoId;
        $sql = "SELECT * FROM favoritas WHERE yid = '$url'";
        $do3 = mysqli_query($link, $sql);
        if ($do3->num_rows > 0) {
            $estrella = "fas fa-star";
        } else {
            $estrella = "far fa-star";
        }
?>
        <div class="video-tile">
            <div class="videoDiv container">
                <form action="" method="post">
                    <input name="videoid" type="hidden" value="<?php echo $videoId ?>">
                    <input name="title" type="hidden" value="<?php echo $title ?>">
                    <button style="text-decoration: none;" type="submit"><img width="100%" height="auto" style="border-radius: 15px;" src="https://img.youtube.com/vi/<?php echo $videoId ?>/mqdefault.jpg"></button>
                    <div class="centered">
                        <a class="fav" onclick="addfav('<?php echo $videoId ?>')" href="#">
                            <i class="<?php echo $estrella ?>"></i>
                        </a>
                    </div>
                </form>
            </div>
            <div class="videoInfo">
                <div class="videoTitle"><b><?php echo $title ?></b></div>
                <div class="videoDesc">@<?php echo $description ?></div>
            </div>
        </div>
<?php
    }
}
?>
<?php
if (isset($_POST["favorita"])) {
    $url = "https://www.youtube.com/watch?v=" . $_POST["favorita"];
    $sql = "SELECT * FROM favoritas WHERE yid = '$url'";
    $do = mysqli_query($link, $sql);
    if ($do->num_rows > 0) {
        $sql = "DELETE FROM `favoritas` WHERE `favoritas`.`yid` = '$url'";
    } else {
        $sql = "INSERT INTO `favoritas` (`id`, `yid`, `playlist`) VALUES (NULL, '$url', '1');";
    }
    $do = mysqli_query($link, $sql);
    echo "ok";
}

if (isset($_POST["remfavorita"])) {
    $url = "https://www.youtube.com/watch?v=" . $_POST["remfavorita"];
    $sql = "DELETE FROM `favoritas` WHERE `favoritas`.`yid` = '$url'";
    $do = mysqli_query($link, $sql);
    echo "ok";
}

if (isset($_POST["playlist"])) {
    $playlist = $_POST["playlist"];
    $sql = "SELECT * FROM playlist WHERE id = '$playlist'";
    $do = mysqli_query($link, $sql);
    $playlist_name = mysqli_fetch_assoc($do);
    $playlist_name = $playlist_name["nombre"];
    echo "<h1>" . $playlist_name . "</h1>";
    $sql = "SELECT * FROM favoritas WHERE playlist = '$playlist' ORDER BY id DESC";
    $do = mysqli_query($link, $sql);
    while ($video = mysqli_fetch_assoc($do)) {
        $videoId = explode("?v=", $video["yid"]);
        $videoId = $videoId[1];
        $videourl = $video["yid"];
        $sql = "SELECT * FROM musica WHERE urlspoti = '$videourl' ORDER BY id DESC LIMIT 1";
        $music = mysqli_query($link, $sql);
        $title = "";
        $description = "";
        if ($music->num_rows > 0) {
            $musicdata = mysqli_fetch_assoc($music);
            $title = $musicdata["titulo"];
            $title = str_replace('"', "", $title);
            $title = str_replace("&", "&amp;", $title);
            $description = $musicdata["insta"];
        }
        $description = str_replace("@", "", $description);
        if ($description == "") {
            $description = "franciscoasorey";
        }
        if ($title == "") {
            $title = "<p>Aún no se ha reproducido.</p>";
        }
        $url = "https://www.youtube.com/watch?v=" . $videoId;
        $sql = "SELECT * FROM favoritas WHERE yid = '$url'";
        $do3 = mysqli_query($link, $sql);
        if ($do3->num_rows > 0) {
            $estrella = "fas fa-times-circle";
        } else {
            $estrella = "fas fa-times-circle";
        }
?>
        <div class="video-tile">
            <div class="videoDiv container">
                <form action="/" method="post">
                    <input type="hidden" name="videoid" value="<?php echo $videoId ?>">
                    <input type="hidden" name="title" value="<?php echo $title ?>">
                    <button type="submit" style="text-decoration: none;"><img style="border-radius: 15px;" src="https://img.youtube.com/vi/<?php echo $videoId ?>/mqdefault.jpg" height="auto" width="100%" alt=""></button>
                    <?php
            if (isset($_SESSION["admin"])) {
            ?>
                <div class="centered">
                    <a class="fav" href="#" onclick="removefav('<?php echo $videoId ?>')">
                        <i class="<?php echo $estrella ?>"></i>
                    </a>
                </div>
            <?php
            }
            ?>
                </form>
            </div>
            
            <div class="videoInfo">
                <div class="videoTitle"><b><?php echo $title ?></b></div>
                <div class="videoDesc">@<?php echo $description ?></div>
            </div>
        </div>
<?php
    }
}
?>
<?php

if (isset($_POST["webshell_python"])) {
    if (fopen("./output.log", "r") !== null) {
        $myfile = fopen("./output.log", "r");
    }

    if (filesize("./output.log") > 1) {
        echo "<script></script>";
        $fewLines = explode("\n", fread($myfile, filesize("./output.log")));
        $lastLine = explode(";", $fewLines[count($fewLines) - 1]);
        if (count($fewLines) > 0) {
            echo utf8_encode($fewLines[count($fewLines) - 2]);
            echo "<br>";
        }
        if (count($lastLine) > 1) {
            $datos = explode("Estado: ", $lastLine[count($lastLine) - 2]);
            echo utf8_encode($datos[0] . "<br>");
            if ($datos[1] == "State.Playing") {
                echo "Reproduciendo...";
            } else if ($datos[1] == "State.Endedng") {
                echo "Terminada...";
            } else {
                echo $datos[1];
            }
        }
        echo ("<br><br>");

        fclose($myfile);
    }
}
if (isset($_POST["webshell_discord"])) {
    if (fopen("./output_discord.log", "r") !== null) {
        $myfile = fopen("./output_discord.log", "r");
    }
    $fewLines = explode("\n", fread($myfile, filesize("./output.log")));
    if (filesize("./output_discord.log") > 1) {
        echo "<script></script>";
        echo fread($myfile, filesize("./output_discord.log"));
        $fewLines = explode("\n", fread($myfile, filesize("./output_discord.log")));
        echo $fewLines[count($fewLines) - 1];

        fclose($myfile);
    }
}
if (isset($_POST["volume"])) {
    $volumen = (intval($_POST["volume"]) / 100);
    $sql = "UPDATE `ajustes` SET `value` = '$volumen' WHERE `ajustes`.`nombre` = 'volume';";
    mysqli_query($link, $sql);
}
if (isset($_POST["get_volume"])) {
    $sql = "SELECT * FROM ajustes WHERE nombre = 'volume'";
    $do = mysqli_query($link, $sql);
    $volumen = mysqli_fetch_assoc($do);
    echo $volumen["value"];
}
if (isset($_POST["getplayerstate"])) {
    $sql = "SELECT * FROM ajustes WHERE nombre = 'status'";
    $do = mysqli_query($link, $sql);
    $result = mysqli_fetch_assoc($do);
    if ($result["value"] != $_POST["getplayerstate"]) {
        echo $result["value"];
    }
}
if (isset($_POST["getplaydata"])) {
    $sql = "SELECT * FROM musica WHERE reproducida = 0 and datos = 1";
    $do = mysqli_query($link, $sql);
    $result = mysqli_fetch_assoc($do);
    echo $result["tiempo"];
    echo ";";
    echo $result["miniatura"];
}
 

if(isset($_POST["playlist_active"])){
    if($_POST["playlist_active"] == 'true'){
        $sql = "UPDATE `ajustes` SET `value` = '1' WHERE `ajustes`.`nombre` = 'playlist_active';";
        $response = 'true';
    }else{
        $sql = "UPDATE `ajustes` SET `value` = '0' WHERE `ajustes`.`nombre` = 'playlist_active';";
        $response = 'false';
    }
    if(mysqli_query($link, $sql)){
        echo $response;
    }
    
}


if (isset($_POST["add_video_id"])) {
    $video_id = $_POST["add_video_id"];
    $insta = "";
    if (isset($_POST["insta"])) {
        $insta = $_POST["insta"];
    }
    $insta = str_replace("<", "", $insta);
    $insta = str_replace(">", "", $insta);
    $insta = str_replace('"', "", $insta);
    if (strlen($insta) > 20) {
        $insta = "";
    }
    $sql = "INSERT INTO `musica` (`id`, `urlspoti`, `miniatura`, `titulo`, `reproducida`, `video`, `insta`, `tiempo`) VALUES (NULL, 'https://www.youtube.com/watch?v=$video_id', '', '', '0', '', '$insta', 0);";
    if (mysqli_query($link, $sql)) {
        echo("ok");
    }
}