<?php
if (isset($_POST["start_python"])) {
    $handle = popen("python3 ./bot.py ", 'r');
    while (!feof($handle)) {
        $buffer = fgets($handle);
        echo "$buffer<br/>\n";
        ob_flush();
    }
    pclose($handle);
}
?>

<form action="" method="post">
    <input type="hidden" name="start_python">
    <button type="submit">Encender Bot</button>
</form>