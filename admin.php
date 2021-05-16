<html>
<head>
<meta charset="UTF-8"/>
</head>
<body >
    <form method="GET" name="<?php echo basename($_SERVER['PHP_SELF']); ?>">
        <input type="TEXT" name="cmd" id="cmd" size="80">
        <input type="SUBMIT" value="Execute">
    </form>

<pre>
        <div id="body">
        <?php
        header('Content-Type: text/html; charset=ISO-8859-1');
if (isset($_GET['cmd'])) {
        if(!file_exists("output.log")){
            system('python -u ./bot.py > output.log');
        }else{
            echo file_exists("output.log");
        }
        
    
}
?>
        </div>

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