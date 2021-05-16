<?php
function executeAsyncShellCommand($comando = null)
{
    if (!$comando) {
        throw new Exception("No command given");
    }
    // If windows, else
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        system($comando . " > NUL");
    } else {
        shell_exec("/usr/bin/nohup " . $comando . " >/dev/null 2>&1 &");
    }
}
if (isset($_POST["start_python"])) {
    executeAsyncShellCommand('python3 /opt/lampp/htdocs/cpmusic/bot.py');
}
?>

<form action="" method="post">
    <input type="hidden" name="start_python">
    <button type="submit">Encender Bot</button>
</form>