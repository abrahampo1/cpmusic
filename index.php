<?php

include("database.php");
$sql = "SELECT * FROM musica WHERE reproducida = 0 LIMIT 1";
$do = mysqli_query($link, $sql);
$id_video = "";
if ($do->num_rows > 0) {
    $video = mysqli_fetch_assoc($do);
    $id_video = $video["id"];
    $miniatura = "temp/" . $video["miniatura"] . ".png";
    $titulo = $video["titulo"];
} else {
    $titulo = "No hay canciones ahora mismo.";
    $miniatura = "404.png";
}
$hayvideo = "";

?>

<style>
    * {
        font-family: 'Montserrat', sans-serif;
    }
    h1{
        background-color: whitesmoke;
    }
    img{
        border-radius: 10px;
    }
    #img-principal{
        box-shadow: black, 10, 10, 10;
    }
    #img{
        margin-top: 10px;
    }
</style>

<head>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
</head>

<body id="back" onload="color()" style="text-align: center;">

    <h1><?php echo $titulo ?></h1>
    <div style="display: flex;">
    <div>
    <h1>Anterior:</h1>
        <?php
        $sql = "SELECT * FROM musica WHERE reproducida = 1 ORDER BY id desc LIMIT 1";
        $do = mysqli_query($link, $sql);
        while ($video_query = mysqli_fetch_assoc($do)) {
            $video_id = explode("?v=", $video_query["urlspoti"]);
            $video_id = $video_id[1];
            $thumbnail = "temp/" . $video_query["miniatura"] . ".png";
            echo '<img id="img" onload="color()" src="' . $thumbnail . '" height="auto" width="100%" alt="" />';
        }
        ?>
    </div>
    <img id="img-principal" onloadeddata="color()" src="<?php echo $miniatura ?>" height="auto" width="100%" alt="" />
    <div>
    <h1>Siguientes:</h1>
        <?php
        $sql = "SELECT * FROM musica WHERE reproducida = 0 LIMIT 4";
        $do = mysqli_query($link, $sql);
        $video = 0;
        while ($video_query = mysqli_fetch_assoc($do)) {
            if ($video != 0) {
                $video_id = explode("?v=", $video_query["urlspoti"]);
                $video_id = $video_id[1];
                $hayvideo .= ";".$video_query["id"];
                $thumbnail = "http://img.youtube.com/vi/" . $video_id . "/mqdefault.jpg";
                echo '<img id="img" onload="color()" src="' . $thumbnail . '" height="auto" width="100%" alt="" /><br>';
            }
            $video++;
        }
        ?>
    </div>
    </div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.0/color-thief.umd.js"></script>

<script>
    // Make sure image is finished loading
    function color() {
        const img = document.getElementById('img-principal');
        const colorThief = new ColorThief();
        if (img.complete) {
            document.body.style.backgroundColor = "rgb(" + colorThief.getColor(img) + ")";
        } else {
            img.addEventListener('load', function() {
                document.body.style.backgroundColor = "rgb(" + colorThief.getColor(img) + ")";
            });
        }
    }
</script>

<script>
    var updatetime = window.setInterval(function() {
        var apid = '<?php echo $id_video; ?>';
        var nuevo = '<?php echo $hayvideo; ?>';
        $.ajax({
            type: 'post',
            url: 'ajax.php',
            data: {
                next: apid,
                nuevo: nuevo,
            },
            success: function(response) {
                if (response == "terminada" || response == "nuevo") {
                    setTimeout(function() {
                        location.reload();
                    }, 5000);
                };
            },
            error: function() {}
        });

    }, 1000);
</script>