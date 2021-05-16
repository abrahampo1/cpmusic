<?php
    if(isset($_POST["start_python"])){
        $command = escapeshellcmd('python3 bot.py');
    $output = shell_exec($command);
    `echo $output|at now`;
    }
?>

<form action="" method="post">
    <input type="hidden" name="start_python">
    <button type="submit">Encender Bot</button>
</form>