<html>

<head>
    <meta charset="UTF-8" />
</head>
<style>
    .consola {
        background-color: black;
        color: green;
        padding: 15px;
        border-radius: 15px;
    }

    h1 {
        text-align: center;
        color: black;
        font-family: 'Montserrat', sans-serif;
        font-size: 15px !important;
    }
</style>

<head>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>
<form action="" method="POST">
        <?php
        exec("pgrep -f bot.py", $out);
        if (count($out) > 1) {

        ?>
            <input type="hidden" name="kill_python" value="paquete">
            <button type="submit">Parar BOT</button>
        <?php
        } else if (file_exists("output.log")) {
        ?>
            <input type="hidden" name="start_python" value="paquete">
            <button type="submit">Empezar BOT</button>
        <?php
        } else {


        ?>
            <input type="hidden" name="kill_python" value="paquete">
            <button type="submit">Parar BOT</button>
        <?php
        } ?>
        </form>
        <form action="" method="POST">
        <?php
        exec("pgrep -f ./discordbot/app.py", $out);
        if (count($out) > 1) {

        ?>
            <input type="hidden" name="kill_discord" value="paquete">
            <button type="submit">Parar BOT DISCORD</button>
        <?php
        } else if (!file_exists("output_discord.log")) {
        ?>
            <input type="hidden" name="start_discord" value="paquete">
            <button type="submit">Empezar BOT DISCORD</button>
        <?php
        } else {


        ?>
            <input type="hidden" name="kill_discord" value="paquete">
            <button type="submit">Parar BOT DISCORD</button>
        <?php
        } ?>
        
    </form>
    <h1>~ WebShell by CP ~</h1>
    <div id="body" class="consola">
            Cargando...
    </div>
    <div id="discord" class="consola">
            Cargando...
    </div>
    <?php
    if (isset($_POST["kill_python"])) {
        exec("pkill -f ./bot.py", $killout);
        echo "Terminado fino";
        header("location: admin");
    }
    if (isset($_POST["start_python"])) {
        unlink("./output.log");
        echo "Empezado fino";
        header("location: admin");
    }
    if (isset($_POST["kill_discord"])) {
        exec("pkill -f ./discordbot/app.py", $killout);
        echo "Terminado fino";
        header("location: admin");
    }
    if (isset($_POST["start_discord"])) {
        unlink("./output_discord.log");
        echo "Empezado fino";
        header("location: admin");
    }
    ?>
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