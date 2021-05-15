<?php
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
            $ultima_html = '<div class="centered"><h1 id="siguiente_texto"></h1></div>';
        } else {
            $ultima_html = "";
        }

        echo '<div class="container"><img class="fadeIn siguiente" id="img" src="' . $thumbnail . '" height="auto" width="100%" alt="">' . $ultima_html . '</div><br>';
        $video++;
    }
}
if (isset($_POST["anuncio"])) {
    //echo '<h1>Troleado papa</h1><img src="https://i.pinimg.com/originals/bb/90/0c/bb900cfc6d9d71d976f9f5d409412aa1.jpg" width="100%"><audio autoplay="" loop=""><source src="https://www.myinstants.com/media/sounds/broma-gemidos-epica.mp3" type="audio/mpeg"></audio>';
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
        echo '<div class="video-tile">
            <div class="videoDiv container">
                <form action="" method="post">
                    <input type="hidden" name="videoid" value="' . $videoId . '">
                    <input type="hidden" name="title" value="' . $title . '">
                    <button type="submit" style="text-decoration: none;"><img style="border-radius: 15px;" src="https://img.youtube.com/vi/' . $videoId . '/mqdefault.jpg" height="auto" width="100%" alt=""></button><div class="centered"><a class="fav" href="#" ';
        echo 'onclick="addfav(';
        echo "'$videoId')";
        if ($do3->num_rows > 0) {
            echo "><i class='fas fa-star'></i></a></div>";
        }else{
            echo "><i class='far fa-star'></i></a></div>";
        }
        echo '</form>
        </div>
        <div class="videoInfo">
            <div class="videoTitle"><b>' . $title . '</b></div>
            <div class="videoDesc">@' . $description . '</div>
        </div>
    </div>';
        
    }
}

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
