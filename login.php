<?php
if(isset($_POST["clave"])){
    $incorrecta = 0;
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
        color: white !important;
    }
</style>

<form action="" method="POST">
    <img style="text-align: center;" src="404.png" alt=""><br><br>
    <?php
    if($incorrecta == 1){
        echo "Clave incorrecta.";
        flush();
        ob_flush();
        sleep(1);
    }
    ?>
    <input type="password" name="clave" placeholder="Clave..."><br><br>
    <button type="submit">Entrar</button>
</form>