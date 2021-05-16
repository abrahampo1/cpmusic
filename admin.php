<?php
    if(isset($_POST["start_python"])){
        $results = shell_exec("python3 ./bot.py");
        echo $results;
    }
?>

<form action="" method="post">
    <input type="hidden" name="start_python">
    <button type="submit">Encender Bot</button>
</form>