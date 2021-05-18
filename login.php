<?php
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
        echo "Clave incorrecta.";
        flush();
        ob_flush();
        sleep(3);
        exit;
    }
}
?>
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
    <input type="password" name="clave" placeholder="Clave..."><br><br>
    <button type="submit">Entrar</button>
</form>