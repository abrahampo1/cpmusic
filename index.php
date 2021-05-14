<?php
include("database.php");
define("MAX_RESULTS", 5);

if (isset($_POST['submit'])) {
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



?>
<!doctype html>
<html>

<head>
    <title>Musica</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <style>
        * {
            font-family: 'Montserrat', sans-serif;
        }

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
            width: 100px;
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
    </style>

</head>

<body>
    <?php
    if (isset($_GET["e"])) {
        echo '<h2>El video se ha puesto a la cola.</h2><br><br><a class="btn-submit inicio" style="text-decoration:none" type="submit" name="submit" href="/" value="Inicio">Inicio</a>';
        exit;
    }
    if (isset($_POST["video_id"])) {
        $video_id = $_POST["video_id"];
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
            header("location: /?e=1");
        }
    }
    if (isset($_POST["videoid"])) {
        $videoid = $_POST["videoid"];
        $title = $_POST["title"];
        echo '<h2>Has seleccionado ' . $title . '</h2><br><br>';
        echo '<img style="border-radius:20px" src="http://img.youtube.com/vi/' . $videoid . '/mqdefault.jpg" height="auto" width="40%">';
        echo '<br><br><p style="font-size:25px">Opcional<p><hr>';
        echo '<form method="post"><div class="input-row"><input id="ig-minita" name="insta" class="input-field" type="text" placeholder="Escribe tu @ de insta"></div><input type="hidden" name="video_id" value="' . $videoid . '">';
        echo '<input class="btn-submit" type="submit" value="→"></form>';
        exit;
    }
    ?>
    <h2>Pon tu canción favorita en el hilo</h2>
    <div class="">
        <form id="keywordForm" method="post" action="">
            <div class="input-row">
                <input class="input-field" type="search" id="keyword" name="keyword" placeholder="Buscar">
            </div>

            <input class="btn-submit" type="submit" name="submit" value="→">
        </form>
    </div>

    <?php if (!empty($response)) { ?>
        <div class="response <?php echo $response["type"]; ?>"> <?php echo $response["message"]; ?> </div>
    <?php
            exit; } ?>
    <?php
    if (isset($_POST['submit'])) {

        if (!empty($keyword)) {
            $sql = "SELECT * FROM ajustes WHERE nombre = 'googleapi'";
            $do = mysqli_query($link, $sql);
            $apikey = mysqli_fetch_assoc($do);
            $apikey = $apikey["value"];
            $keyword = urlencode($keyword);
            $i = 1;
            setcookie("delay", "si", time() + 15);
            function cargarapi($i, $apikey, $keyword)
            {

                $apis = explode(";", $apikey);
                if($i >= count($apis)){
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
                    echo $apis[$i];
                    print_r($value);
                    echo "<br>";
                    
                    $i++;
                    $value = cargarapi($i, $apikey, $keyword);
                }
                return $value;
            }
            if(!isset($_COOKIE["delay"])){
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
                ?>

                        <div class="video-tile">
                            <div class="videoDiv">
                                <form action="" method="post">
                                    <input type="hidden" name="videoid" value="<?php echo $videoId ?>">
                                    <input type="hidden" name="title" value="<?php echo $title ?>">
                                    <button type="submit" style="text-decoration: none;"><img style="border-radius: 15px;" src="http://img.youtube.com/vi/<?php echo $videoId ?>/mqdefault.jpg" height="auto" width="100%" alt=""></button>
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


</body>

</html>

<script>
    if (window.history.replaceState) { // verificamos disponibilidad
        window.history.replaceState(null, null, window.location.href);
    }
</script>

<script>
    function igminita() {
        var iglargo = document.getElementById("ig-minita").value;
        if (iglargo.length >= 20) {
            iglargo = "";
        }
    }
</script>