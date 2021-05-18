<html>
<?php
session_start();
if(!isset($_SESSION["admin"])){
    header("location: login");
    exit;
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
    form{
        text-align: center;
        font-size: 25px;
    }
    button{
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
</style>
<?php
include("database.php");
$sql = "SELECT * FROM ajustes WHERE nombre = 'anuncio_active'";
$do = mysqli_query($link, $sql);
$ajuste = mysqli_fetch_assoc($do);
$anuncio_active = $ajuste["value"];

if(isset($_POST["stream"])){
    if($anuncio_active == 1){
        $sql = "UPDATE `ajustes` SET `value` = '0' WHERE `ajustes`.`nombre` = 'anuncio_active';";
    }else{
        $sql = "UPDATE `ajustes` SET `value` = '1' WHERE `ajustes`.`nombre` = 'anuncio_active';";
    }
    if(mysqli_query($link, $sql)){
        header("location: admin");
    }
    
}
?>
<head>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>
    <form action="" method="POST" style="margin: 20px">
        <input type="hidden" name="stream" value="paquete">
        <input type="range" min="1" max="100" value="100" onchange="volume()" class="slider" id="volume">
        <button type="submit"><?php if($anuncio_active == 1){echo "Cerrar Anuncio/Stream";}else{echo "Abrir Anuncio/Stream";}?></button>
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
    function volume(){
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