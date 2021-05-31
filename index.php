<?php
session_start();
include("database.php");
define("MAX_RESULTS", 5);

if (isset($_POST['submit'])) {
    if (!isset($_COOKIE["delay"])) {
        setcookie("delay", "si", time() + 15);
    }
    $keyword = $_POST['keyword'];
    if (isset($_COOKIE["delay"])) {
        $response = array(
            "type" => "error",
            "message" => "No puedes spamear el botón."
        );
    }
    if (empty($keyword) || strlen($keyword) < 3) {
        $response = array(
            "type" => "error",
            "message" => "El campo de busqueda no puede estar vacio o tener menos de 3 digitos."
        );
    }
}
if (isset($_SESSION["admin"])) {
    unset($_SESSION["admin"]);
}

?>

<html>

<head>
    <title>Musica</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="iconos/css/all.css" rel="stylesheet">
    <style>
        * {
            font-family: 'Montserrat', sans-serif;
        }
        .rotate-out-2-cw{-webkit-animation:rotate-out-2-cw .6s cubic-bezier(.25,.46,.45,.94) both;animation:rotate-out-2-cw .6s cubic-bezier(.25,.46,.45,.94) both}
        @-webkit-keyframes rotate-out-2-cw{0%{-webkit-transform:rotate(0);transform:rotate(0);opacity:1}100%{-webkit-transform:rotate(45deg);transform:rotate(45deg);opacity:0}}@keyframes rotate-out-2-cw{0%{-webkit-transform:rotate(0);transform:rotate(0);opacity:1}100%{-webkit-transform:rotate(45deg);transform:rotate(45deg);opacity:0}}
        button {
            background: none;
            color: inherit;
            border: none;
            padding: 0;
            font: inherit;
            cursor: pointer;
            outline: inherit;
        }

        body {
            width: 99%;
            padding: 10px;
            text-align: center;
        }

        .search-form-container {
            background: #F0F0F0;
            border: #e0dfdf 1px solid;
            padding: 20px;
            border-radius: 2px;
        }

        .input-row {
            margin-bottom: 20px;
        }

        .input-field {
            width: 100%;
            border-radius: 30px;
            padding: 10px;
            text-align: center;
            font-size: 20px;
            border: #e0dfdf 1px solid;
        }

        .btn-submit {
            background: #333;
            border: #1d1d1d 1px solid;
            color: #f0f0f0;
            font-size: 0.9em;
            width: auto;
            height: 50px;
            border-radius: 20px;
            font-size: 20px;
            cursor: pointer;
        }

        .videos-data-container {
            padding: 20px;
            border-radius: 2px;
        }

        .response {
            padding: 10px;
            margin-top: 10px;
            border-radius: 2px;
        }

        .error {
            background: #fdcdcd;
            border: #ecc0c1 1px solid;
        }

        .success {
            background: #c5f3c3;
            border: #bbe6ba 1px solid;
        }

        .result-heading {
            margin: 20px 0px;
            padding: 20px 10px 5px 0px;
            border-bottom: #e0dfdf 1px solid;
        }

        iframe {
            border: 0px;
        }

        .video-tile {
            display: inline-block;
            margin: 10px 10px 20px 10px;
        }

        .videoDiv {
            width: 250px;
            height: 150px;
            display: inline-block;
        }

        .videoTitle {
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        .videoDesc {
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        .videoInfo {
            width: 250px;
        }

        .inicio {
            padding: 10px;
        }

        .video-tile {
            animation: fadeInAnimation ease 1s;
            animation-iteration-count: 1;
            animation-fill-mode: forwards;
        }

        @keyframes fadeInAnimation {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .container {
            position: relative;
            text-align: center;
            color: white;
        }

        .centered {
            position: absolute;
            top: 80%;
            left: 90%;
            transform: translate(-50%, -50%);
        }

        .fav {
            text-decoration: none;
            background-color: whitesmoke;
            border-radius: 100%;
            padding: 5px;
            color: #222222;
        }

        .media {
            margin-left: -5px;
            margin-right: -5px;
            font-size: 25px;
            margin: 10px;
            padding: 5px;
        }

        .media.off {
            border-radius: 25px 0px 0px 25px;
            margin-right: 0px !important;

        }

        nav {
            height: 60px;
            text-align: center;
            background-color: white;
        }

        nav img {
            height: 50px;
        }

        .admin {
            position: fixed;
            left: 0;
            top: 0;
        }
        .slide-top {
	-webkit-animation: slide-top 1s cubic-bezier(0.600, -0.280, 0.735, 0.045) 1 both;
	        animation: slide-top 1s cubic-bezier(0.600, -0.280, 0.735, 0.045) 1 both;
}
@-webkit-keyframes slide-top {
  0% {
    -webkit-transform: translateY(0);
            transform: translateY(0);
  }
  100% {
    -webkit-transform: translateY(-800px);
            transform: translateY(-800px);
  }
}
@keyframes slide-top {
  0% {
    -webkit-transform: translateY(0);
            transform: translateY(0);
  }
  100% {
    -webkit-transform: translateY(-800px);
            transform: translateY(-800px);
  }
}

    </style>

</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.0/color-thief.umd.js"></script>

<script>
    function addqueue() {
        var url = document.getElementById("url").value;
        var insta = document.getElementById("insta").value;
        $.ajax({

            type: 'post',
            url: 'ajax.php',
            data: {
                add_video_id: url,
                insta: insta,
            },
            success: function(response) {
                if (response == "ok") {
                    document.getElementById("image-preview").classList.add("slide-top");
                    setTimeout(function() {
                            location.replace("./");
                        }, 1200);
                }
                if (response == "notok") {
                    document.getElementById("image-preview").classList.add("rotate-out-2-cw");
                    setTimeout(function() {
                            location.replace("./");
                        }, 1200);
                }
            },
            error: function() {}
        });
    }
</script>
<body>
    <?php
    if (isset($_GET["e"])) {
        echo '<h2>El video se ha puesto a la cola.</h2><br><br><a class="btn-submit inicio" style="text-decoration:none" type="submit" name="submit" href="./" value="Inicio">Inicio</a>';
        exit;
    }

    if (isset($_POST["videoid"])) {
        $videoid = $_POST["videoid"];
        $title = $_POST["title"];
        echo '<h2>Has seleccionado ' . $title . '</h2><br><br>';
        echo '<img style="border-radius:20px" id="image-preview" src="http://img.youtube.com/vi/' . $videoid . '/mqdefault.jpg" height="auto" width="40%">';
        echo '<br><br><p style="font-size:25px">Opcional<p><hr>';
        echo '<div class="input-row"><input id="insta" name="insta" class="input-field" type="text" placeholder="Escribe tu @ de insta"></div><input type="hidden" name="video_id" id="url" value="' . $videoid . '">';
        echo '<button id="sub-insta" class="btn-submit" type="button" onclick="addqueue()" >→</button>';
        exit;
    }
    ?>
    <nav>
        <a href="./"><img style="display: inline;" src="logo.png" height="100%" alt=""></a>
        <form class="admin" method="POST" action="./admin">
            <button class="media off" value="paquete"><i class="fas fa-user"></i></button>
        </form>
    </nav>

    <h2>Pon tu canción favorita en el hilo</h2>

    <div class="">
        <form id="keywordForm" method="post" action="">
            <div class="input-row">
                <input class="input-field" type="search" id="keyword" name="keyword" placeholder="Buscar">
            </div>

            <input class="btn-submit" type="submit" name="submit" value="Buscar en YouTube →"><br><br>
            
        </form>
        <a href="playlist?p=1" class="btn-submit inicio" style="text-decoration: none; margin-top:15px">Playlist de Favoritas</a>
    </div>

    <?php if (!empty($response)) { ?>
        <div class="response <?php echo $response["type"]; ?>"> <?php echo $response["message"]; ?> </div>
    <?php
        exit;
    } ?>
    <?php
    if (isset($_POST['submit'])) {

        if (!empty($keyword)) {
            $sql = "SELECT * FROM ajustes WHERE nombre = 'googleapi'";
            $do = mysqli_query($link, $sql);
            $apikey = mysqli_fetch_assoc($do);
            $apikey = $apikey["value"];
            $keyword = urlencode($keyword);
            $i = 1;
            function cargarapi($i, $apikey, $keyword)
            {

                $apis = explode(";", $apikey);
                if ($i >= count($apis)) {
                    exit;
                }
                $googleApiUrl = 'https://youtube.googleapis.com/youtube/v3/search?part=snippet&maxResults=5&q=' . $keyword . '&safeSearch=strict&type=video&videoCategoryId=10&key=' . $apis[$i];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_VERBOSE, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);
                $data = json_decode($response);
                $value = json_decode(json_encode($data), true);
                if (isset($value["error"]["code"])) {

                    if ($i == count($apis)) {
                        echo "Error con las APIS de google";
                        exit;
                    }
                    //echo $apis[$i];
                    //print_r($value);
                    //echo "<br>";

                    $i++;
                    $value = cargarapi($i, $apikey, $keyword);
                }
                return $value;
            }
            if (!isset($_COOKIE["delay"])) {
                $value = cargarapi($i, $apikey, $keyword);
            }


    ?>

            <div class="result-heading">Sobre <?php echo MAX_RESULTS; ?> Resultados</div>
            <div class="videos-data-container" id="SearchResultsDiv">

                <?php

                for ($i = 0; $i < MAX_RESULTS; $i++) {
                    if (isset($value['items'][$i]['id']['videoId'])) {
                        $videoId = $value['items'][$i]['id']['videoId'];
                        $title = $value['items'][$i]['snippet']['title'];
                        $description = $value['items'][$i]['snippet']['channelTitle'];
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
                                    <input type="hidden" name="videoid" value="<?php echo $videoId ?>">
                                    <input type="hidden" name="title" value="<?php echo $title ?>">
                                    <button type="submit" style="text-decoration: none;"><img style="border-radius: 15px;" src="http://img.youtube.com/vi/<?php echo $videoId ?>/mqdefault.jpg" height="auto" width="100%" alt=""></button>
                                    <div class="centered">
                                        <a class="fav" href="#" onclick="addytfav('<?php echo $videoId ?>', this)">
                                            <i class="<?php echo $estrella ?>"></i>
                                        </a>
                                    </div>
                                </form>
                            </div>
                            <div class="videoInfo">
                                <div class="videoTitle"><b><?php echo $title; ?></b></div>
                                <div class="videoDesc"><?php echo $description; ?></div>
                            </div>
                        </div>
        <?php
                    }
                }
            }
        }
        ?>

            </div>
            <div class="videos-data-container" id="anteriores">
                <h2>Cargando...</h2>
            </div>
</body>
<p id="debug"></p>

</html>


<script>
    function igminita() {
        var iglargo = document.getElementById("ig-minita").value;
        if (iglargo.length >= 20) {
            iglargo = "";
        }
    }
</script>

<script>
    var siguientes = window.setInterval(function() {
        $.ajax({
            type: 'post',
            url: 'ajax.php',
            data: {
                anteriores: 'paquete',
            },
            success: function(response) {
                if (response != document.getElementById("anteriores").innerHTML) {
                    console.log(response);
                    console.log(document.getElementById("anteriores").innerHTML);
                    document.getElementById("anteriores").innerHTML = response;

                };
            },
            error: function() {}
        });
    }, 1000);
</script>
<script>
    function addfav(url) {
        $.ajax({

            type: 'post',
            url: 'ajax.php',
            data: {
                favorita: url,
            },
            success: function(response) {

            },
            error: function() {}
        });
    }

    function addytfav(url, star) {
        $.ajax({

            type: 'post',
            url: 'ajax.php',
            data: {
                favorita: url,
            },
            success: function(response) {
                if (star.innerHTML == '<i class="fas fa-star"></i>') {
                    star.innerHTML = '<i class="far fa-star"></i>';
                } else {
                    star.innerHTML = '<i class="fas fa-star"></i>';
                }
            },
            error: function() {}
        });
    }
</script>
<script>
    if (window.history.replaceState) { // verificamos disponibilidad
        window.history.replaceState(null, null, window.location.href);
    }
</script>

<script>
var input = document.getElementById("insta");
input.addEventListener("keyup", function(event) {
    if (event.keyCode === 13) {
        event.preventDefault();
        document.getElementById("sub-insta").click();
    }
});
</script>