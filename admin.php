<html>

<head>
    <meta charset="UTF-8" />
</head>

<body>
    <form action="" method="POST">
        <?php
        exec("pgrep -f ./bot.py", $out);
        if ($out[0] > 0) {

        ?>
            <input type="hidden" name="kill_python" value="paquete">
            <button type="submit">Parar BOT</button>
        <?php
        }else{
        ?>
        <input type="hidden" name="start_python" value="paquete">
            <button type="submit">Empezar BOT</button>
        <?php
        }
        ?>

    </form>
    <pre>
        <div id="body">
        
        </div>
        <?php
        if (isset($_POST["kill_python"])) {
            exec("kill -9 " . $out[1], $killout);
            echo "Terminado fino";
            header("location: admin");
        }
        if (isset($_POST["start_python"])) {
            unlink("./output.log");
            echo "Empezado fino";
            header("location: admin");
        }
        ?>

</pre>
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