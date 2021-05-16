<?php
if (isset($_POST["start_python"])) {
    ob_start();
    passthru('python3 /opt/lampp/htdocs/cpmusic/bot.py');
    $output = ob_get_clean();
    echo $output;
}
?>

<form action="" method="post">
    <input type="hidden" name="start_python">
    <button type="submit">Encender Bot</button>
</form>