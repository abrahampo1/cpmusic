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
        //echo '<h1 style="width:100%" class="siguiente_text">Siguiente:</h1>';
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
    //echo "anuncio:<h1>Suspensos todos</h1>";
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

        echo '<div class="video-tile">
            <div class="videoDiv container">
                <form action="" method="post">
                    <input type="hidden" name="videoid" value="' . $videoId . '">
                    <input type="hidden" name="title" value="' . $title . '">
                    <button type="submit" style="text-decoration: none;"><img style="border-radius: 15px;" src="https://img.youtube.com/vi/' . $videoId . '/mqdefault.jpg" height="auto" width="100%" alt=""><div class="centered"><a class="fav" href="index.php?f='.$videoId.'"><i class="far fa-star"></i></a></div></button>
                </form>
            </div>
            <div class="videoInfo">
                <div class="videoTitle"><b>' . $title . '</b></div>
                <div class="videoDesc">@' . $description . '</div>
            </div>
        </div>';
    }
}
