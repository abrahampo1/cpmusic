<html>
<?php
include("database.php");
session_start();
if (!isset($_SESSION["admin"])) {
    header("location: login");
    exit;
}
$sql = "SELECT * FROM ajustes WHERE nombre = 'status'";
$do = mysqli_query($link, $sql);
$result = mysqli_fetch_assoc($do);
if($result["value"] == "pause"){
    $icon = "fas fa-play";
}else if($result["value"]=="play"){
    $icon = "fas fa-pause";
}else{
    $icon = "fas fa-pause";
}
$sql = "SELECT * FROM ajustes WHERE nombre = 'volume'";
$do = mysqli_query($link, $sql);
$result = mysqli_fetch_assoc($do);
$volumen = floor(intval($result["value"])*100);
if(isset($_POST["play"])){
    $sql = "SELECT * FROM ajustes WHERE nombre = 'status'";
    $do = mysqli_query($link, $sql);
    $result = mysqli_fetch_assoc($do);
    if($result["value"]=="play"){
        $sql = "UPDATE `ajustes` SET `value` = 'pause' WHERE `ajustes`.`nombre` = 'status';";
    }else{
        $sql = "UPDATE `ajustes` SET `value` = 'play' WHERE `ajustes`.`nombre` = 'status';";
    }
    if(mysqli_query($link, $sql)){
        header("location: admin");
        exit;
    }
}
if(isset($_POST["next"])){
    $sql = "UPDATE `ajustes` SET `value` = 'next' WHERE `ajustes`.`nombre` = 'status';";
    if(mysqli_query($link, $sql)){
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

    form {
        text-align: center;
        font-size: 25px;
    }

    button {
        padding: 10px;
        background: none;
        border: none;
        background-color: black;
        border-radius: 15px;
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
        width: 50% !important;
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
        width: 40% !important;
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
        background: #04AA6D;
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
        background: #04AA6D;
        border-radius: 100%;
        /* Green background */
        cursor: pointer;
        /* Cursor on hover */
    }
    .media{
        margin-left: 15px;
        margin-right: 15px;
        font-size: 50px;
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
    } else {
        $sql = "UPDATE `ajustes` SET `value` = '1' WHERE `ajustes`.`nombre` = 'anuncio_active';";
    }
    if (mysqli_query($link, $sql)) {
        header("location: admin");
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

<body>
    <div style="text-align: center; margin-left: auto; margin-right: auto;">
    <div style="display: flex; text-align:center;align-items: center;justify-content: center; margin-top: 20px">
    <form action="" method="POST" >
        <button name="play" class="media" value="paquete"><i class="<?php echo $icon ?>"></i></button>
    </form>
    <form action="" method="POST" >
        <button name="next" class="media" value="paquete"><i class="fas fa-forward"></i></button>
    </form>
    </div>
    </div>
    
    
    <form action="" method="POST" style="margin: 20px">
        <input type="hidden" name="stream" value="paquete">
        <div class="slidercontainer">
            <input type="range" min="1" max="100" value="<?php echo $volumen ?>" onchange="volume()" class="slider" id="volume">
        </div>

        <button type="submit"><?php if ($anuncio_active == 1) {
                                    echo "Cerrar Anuncio/Stream";
                                } else {
                                    echo "Abrir Anuncio/Stream";
                                } ?></button>
    </form>
    <h1>~ WebShell by CP ~</h1>
    <div id="body" class="consola">
        Cargando...
    </div>
    <div id="discord" class="consola">
        Cargando...
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
        console.log(volumen);
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