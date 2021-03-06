<html>
<?php
include("database.php");
include("ajustes.php");
session_start();
if (!isset($_SESSION["admin"])) {
    header("location: ./login");
}
$sql = "SELECT * FROM musica WHERE reproducida = 0 and datos = 1";
$do = mysqli_query($link, $sql);
$musicaahora = mysqli_fetch_assoc($do);
if (ajuste("status") == "pause") {
    $icon = "fas fa-play";
} else if (ajuste("status") == "play") {
    $icon = "fas fa-pause";
} else {
    $icon = "fas fa-pause";
}
$playlist_sel = ajuste("playlist");
$playlist_active = ajuste("playlist_active");
$timer = ajuste("timer");
$start_hour = ajuste("start_hour");
$stop_hour = ajuste("stop_hour");
$volumen = floatval(ajuste("volume")) * 100;
if (isset($_POST["play"])) {
    if (ajuste("status") == "play") {
        $sql = "UPDATE `ajustes` SET `value` = 'pause' WHERE `ajustes`.`nombre` = 'status';";
    } else {
        $sql = "UPDATE `ajustes` SET `value` = 'play' WHERE `ajustes`.`nombre` = 'status';";
    }
    if (mysqli_query($link, $sql)) {
        header("location: admin");
        exit;
    }
}
if (isset($_POST["next"])) {
    $sql = "UPDATE `ajustes` SET `value` = 'next' WHERE `ajustes`.`nombre` = 'status';";
    if (mysqli_query($link, $sql)) {
        header("location: admin");
        exit;
    }
}
?>

<head>
    <meta charset="UTF-8" />
</head>
<style>
    .consola {
        background-color: black;
        color: green;
        padding: 15px;
        border-radius: 15px;
        margin: 15px;
    }

    img {
        max-height: 400px;
        max-width: 720px;
        display: none;
    }

    form {
        text-align: center;
        font-size: 25px;
    }

    button {
        padding: 10px;
        background: none;
        border: none;
        background-color: black;
        border-radius: 25px;
        margin: 20px;
        color: white !important;
    }

    h1 {
        text-align: center;
        color: black;
        font-family: 'Montserrat', sans-serif;
        font-size: 15px !important;
    }

    .slidecontainer {
        width: 100% !important;
        text-align: center !important;
        margin-left: auto;
        margin-right: auto;
        /* Width of the outside container */
    }

    /* The slider itself */
    .slider {
        -webkit-appearance: none;
        /* Override default CSS styles */
        appearance: none;
        border-radius: 20px;
        margin: 10px;
        width: 60% !important;
        margin-left: auto;
        margin-right: auto;
        /* Full-width */
        height: 25px;
        /* Specified height */
        background: #d3d3d3;
        /* Grey background */
        outline: none;
        /* Remove outline */
        opacity: 0.7;
        /* Set transparency (for mouse-over effects on hover) */
        -webkit-transition: .2s;
        /* 0.2 seconds transition on hover */
        transition: opacity .2s;
    }


    /* Mouse-over effects */
    .slider:hover {
        opacity: 1;
        /* Fully shown on mouse-over */
    }

    /* The slider handle (use -webkit- (Chrome, Opera, Safari, Edge) and -moz- (Firefox) to override default look) */
    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        /* Override default look */
        appearance: none;
        width: 25px;
        /* Set a specific slider handle width */
        height: 25px;
        /* Slider handle height */
        background: #000;
        /* Green background */
        cursor: pointer;
        border-radius: 100%;
        /* Cursor on hover */
    }

    .slider::-moz-range-thumb {
        width: 25px;
        /* Set a specific slider handle width */
        height: 25px;
        /* Slider handle height */
        background: #000;
        border-radius: 100%;
        /* Green background */
        cursor: pointer;
        /* Cursor on hover */
    }

    .slider.tiempo::-moz-range-thumb {

        width: 25px;
        /* Set a specific slider handle width */
        height: 25px;
        /* Slider handle height */
        background: green;
        border-radius: 100%;
    }

    .slider.tiempo::-webkit-slider-thumb {
        -webkit-appearance: none;
        /* Override default look */
        appearance: none;
        width: 25px;
        /* Set a specific slider handle width */
        height: 25px;
        /* Slider handle height */
        background: green;
        /* Green background */
        cursor: pointer;
        border-radius: 100%;
        /* Cursor on hover */
    }

    #timeline {
        display: none;
    }

    .media {
        margin-left: -5px;
        font-size: 50px;
        padding: 20px;
    }

    .media.next {
        border-radius: 0px 25px 25px 0px;

    }

    .media.off {
        border-radius: 25px 0px 0px 25px;
    }

    nav {
        height: 100px;
        text-align: center;
    }
    nav img{
        border-radius: 15px;
    }

    select {
        border: none;
        border: 1px solid black;
        border-radius: 20px;
        padding: 5px;
        margin: 10px;
        font-size: 25px !important;
        width: 50%;
        min-width: 200px;
        text-align-last: center;
    }

    select option {
        text-align: center;
    }

    .playlist {
        text-align: center;
    }

    .checkbox {
        width: 40px;
        height: 40px;
    }

    .playlist-options {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px;
    }

    .playlist-options p {
        padding: 5px;
        padding-top: 15px;
        font-size: 20px;
    }

    .input-field {
        width: 100px;
        border-radius: 30px;
        padding: 10px;
        text-align: center;
        font-size: 20px;
        border: #e0dfdf 2px solid;
    }
</style>
<?php
$sql = "SELECT * FROM ajustes WHERE nombre = 'anuncio_active'";
$do = mysqli_query($link, $sql);
$ajuste = mysqli_fetch_assoc($do);
$anuncio_active = $ajuste["value"];
if (isset($_POST["stream"])) {
    if ($anuncio_active == 1) {
        $sql = "UPDATE `ajustes` SET `value` = '0' WHERE `ajustes`.`nombre` = 'anuncio_active';";
        $sql2 = "UPDATE `ajustes` SET `value` = 'play' WHERE `ajustes`.`nombre` = 'status';";
        $anuncio_active = 0;
    } else {
        $sql = "UPDATE `ajustes` SET `value` = '1' WHERE `ajustes`.`nombre` = 'anuncio_active';";
        $sql2 = "UPDATE `ajustes` SET `value` = 'pause' WHERE `ajustes`.`nombre` = 'status';";
        $anuncio_active = 1;
    }
    if (mysqli_query($link, $sql)) {
        mysqli_query($link, $sql2);
    }
}
?>

<head>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="iconos/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<nav>
    <a href="https://asorey.net"><img style="display: inline;" src="logo.png" height="80px" alt=""></a>
</nav>

<body>
    <div style="text-align: center; margin-left: auto; margin-right: auto;">
        <div style="display: flex; text-align:center;align-items: center;justify-content: center; margin-top: 20px">
            <form class="next" method="POST" action="./">
                <button name="logout" class="media off" value="paquete"><i class="fas fa-home"></i></button>
            </form>
            <form action="" method="POST">
                <button name="play" class="media" style="font-size: 80px;" value="paquete"><i class="<?php echo $icon ?>"></i></button>
            </form>
            <form action="" class="next" method="POST">
                <button name="next" class="media next" value="paquete"><i class="fas fa-forward"></i></button>
            </form>
        </div>
    </div>


    <form action="" method="POST" style="margin: 20px">
        
        <h2><i class="fas fa-volume-up"></i></h2>
        <div class="slidercontainer">
            <input type="range" min="1" max="100" value="<?php echo $volumen ?>" onchange="volume()" class="slider" id="volume">
        </div>
        <div style="text-align: center;">
            <img id="imagenahora" src="./temp/<?php echo $musicaahora["miniatura"] ?>.png" height="auto" style="border-radius: 25px; text-align: center" alt="">
        </div>

        <div class="slidercontainer tiempocontainer">
            <input type="range" min="1" max="<?php echo $musicaahora["total_tiempo"] ?>" value="<?php echo $musicaahora["tiempo"] ?>" onchange="time()" class="slider tiempo" id="timeline">
        </div>

        <br>
        
        <button id="console_btn" onclick="console()" type="button">Abrir consola</button>
    </form>
    <form action="" method="POST" style="margin: 20px">
        <input type="hidden" name="stream" value="paquete">
    <button type="submit"><?php if ($anuncio_active == 1) {
                                    echo "Cerrar Transmisi??n en Directo";
                                } else {
                                    echo "Abrir Transmisi??n en Directo";
                                } ?></button>
    </form>
    <div class="playlist">
        <h2>Ajustes Apagado automatico</h2>
        <div class="playlist-options">
            <p>Apagado/Encendido automatico</p><input type="checkbox" id="timer" class="checkbox" <?php if ($playlist_active == 1) {
                                                                                                        echo "checked";
                                                                                                    } ?>>
        </div>
        <p>Hora de encendido</p>
        <input class="input-field" type="number" step="1" max="23" min="1" name="" id="start_hour" value="<?php echo $start_hour ?>">
        <p>Hora de apagado</p>
        <input class="input-field" type="number" step="1" max="23" min="1" name="" id="stop_hour" value="<?php echo $stop_hour ?>">
        <h3>Playlist Seleccionada</h3>
    </div>
    <div class="playlist">
        <h2>Ajustes Playlist</h2>
        <div class="playlist-options">
            <p>Reproducci??n automatica </p><input class="checkbox" id="playlist_active" type="checkbox" <?php if ($playlist_active == 1) {
                                                                                                            echo "checked";
                                                                                                        } ?>>
            <br>

        </div>
        
        <select name="playlist" id="">
            <?php
            $sql = "SELECT * FROM playlist";
            $do = mysqli_query($link, $sql);
            while ($row = mysqli_fetch_assoc($do)) {
            ?>
                <option value="<?php echo $row["id"] ?>"><?php echo $row["nombre"] ?></option>
            <?php
            }
            ?>
        </select>
    </div>

    <div id="console" style="display: none;">
        <h1>~ WebShell by CP ~</h1>
        <div id="body" class="consola">
            Cargando...
        </div>
        <div id="discord" class="consola">
            Cargando...
        </div>
    </div>

</body>
<script>
    document.getElementById("cmd").focus();
</script>

</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.0/color-thief.umd.js"></script>

<script>
    var siguientes = window.setInterval(function() {
        $.ajax({
            type: 'post',
            url: 'ajax.php',
            data: {
                webshell_python: 'paquete',
            },
            success: function(response) {
                if (response != document.getElementById("body").innerHTML) {
                    document.getElementById("body").innerHTML = response;
                    document.getElementById("body").innerHTML = response;
                };
            },
            error: function() {}
        });

    }, 1000);
</script>
<script>
    var siguientes = window.setInterval(function() {
        $.ajax({
            type: 'post',
            url: 'ajax.php',
            data: {
                webshell_discord: 'paquete',
            },
            success: function(response) {
                if (response != document.getElementById("discord").innerHTML) {
                    document.getElementById("discord").innerHTML = response;
                    document.getElementById("discord").innerHTML = response;
                };
            },
            error: function() {}
        });

    }, 1000);
</script>

<script>
    document.getElementById("volume").onchange = function() {
        var volumen = document.getElementById("volume").value;
        $.ajax({
            type: 'post',
            url: 'ajax.php',
            data: {
                volume: volumen,
            },
            success: function(response) {

            },
            error: function() {}
        });
    }
</script>

<script>
    var miniatura = "";
    var timeline = window.setInterval(function() {
        $.ajax({
            type: 'post',
            url: 'ajax.php',
            data: {
                getplaydata: miniatura,
            },
            success: function(response) {
                datos = response.split(";");
                miniatura = datos[1];
                tiempo = datos[0];
                document.getElementById("timeline").value = tiempo;
                var miniatura_completa = "/temp/" + miniatura + ".png";
                $.ajax({
                    url: window.location.origin + miniatura_completa,
                    type: 'HEAD',
                    error: function() {
                        document.getElementById("imagenahora").style.display = "none";
                        document.getElementById("timeline").style.display = "none";
                    },
                    success: function() {
                        if (document.getElementById("imagenahora").src != miniatura_completa) {
                            document.getElementById("imagenahora").src = miniatura_completa;
                            document.getElementById("imagenahora").style.display = "inline";
                            document.getElementById("timeline").style.display = "inline";
                        }
                    }
                });




            },
            error: function() {}
        });
    }, 1000);
</script>

<script>
    var btn = document.getElementById("console_btn");
    var open = 1;

    function console() {
        if (open == 0) {
            document.getElementById("console").style.display = "none";
            btn.innerHTML = "Abrir consola";
            open = 1;
        } else {
            document.getElementById("console").style.display = "block";
            btn.innerHTML = "Cerrar consola";
            open = 0;
        }

    };
</script>

<script>
    document.getElementById("playlist_active").onchange = function() {
        var check = document.getElementById("playlist_active").checked;
        $.ajax({
            type: 'post',
            url: 'ajax.php',
            data: {
                playlist_active: check,
            },
            success: function(response) {
                if (response == "false") {
                    document.getElementById("playlist_active").checked = false;
                } else {
                    document.getElementById("playlist_active").checked = true;
                }

            },
            error: function() {}
        });
    }
</script>
<script>
    document.getElementById("timer").onchange = function() {
        var check = document.getElementById("timer").checked;
        $.ajax({
            type: 'post',
            url: 'ajax.php',
            data: {
                timer: check,
            },
            success: function(response) {
                if (response == "false") {
                    document.getElementById("timer").checked = false;
                } else {
                    document.getElementById("timer").checked = true;
                }

            },
            error: function() {}
        });
    }
</script>
