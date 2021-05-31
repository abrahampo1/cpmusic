<?php

$incorrecta = 0;
if(isset($_POST["clave"])){
    $clave = $_POST["clave"];
    include("database.php");
    $sql = "SELECT * FROM ajustes WHERE nombre = 'admin'";
    $do = mysqli_query($link, $sql);
    $pass = mysqli_fetch_assoc($do);
    if($clave == $pass["value"]){
        session_start();
        $_SESSION["admin"] = "log";
        header("location: admin");
    }else{
        $incorrecta = 1;
    }
}
?>
<head>
    <title>Musica</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="iconos/css/all.css" rel="stylesheet">
</head>
<style>
    form {
        width: 100%;
        text-align: center;
    }

    input {
        font-size: 30px;
        width: 30%;
        min-width: 200px;
        padding: 10px;
        border-radius: 25px;
    }
    button{
        padding: 10px;
        background: none;
        border: none;
        background-color: black;
        border-radius: 15px;
        margin: 20px;
        font-size: 30px;
        color: white;
        
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
            position: fixed;
            left: 0;
            top: 0;
            color: black;
            background: none;
            font-size: 50px;

        }

        nav {
            height: 100px;
            font-size: 50px;
            text-align: center;
            background-color: white;
        }

        nav img {
            height: 80px;
        }

        .admin {
            position: fixed;
            left: 0;
            top: 10;
        }
</style>
<nav>
        <img style="display: inline;" src="logo.png" height="100%" alt="">
        <form class="admin" method="POST" action="./">
            <button class="media off" value="paquete"><i class="fas fa-arrow-left"></i></button>
        </form>
    </nav>
<form action="" method="POST">
    <img style="text-align: center;" src="404.png" alt=""><br><br>
    <?php
    if($incorrecta == 1){
        echo "Clave incorrecta. <br>";
        flush();
        ob_flush();
        sleep(1);
    }
    ?>
    <input type="password" name="clave" placeholder="Clave..."><br><br>
    <button type="submit">Entrar</button>
</form>