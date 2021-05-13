<?php

include("database.php");
$sql = "SELECT * FROM musica WHERE reproducida = 0 LIMIT 1";
$do = mysqli_query($link, $sql);
$id_video = "";
$ig_minita = "";
$tiempo = 0;
if ($do->num_rows > 0) {
    $video = mysqli_fetch_assoc($do);
    $id_video = $video["id"];
    $miniatura = "temp/" . $video["miniatura"] . ".png";
    $titulo = $video["titulo"];
    $videourl = $video["video"];
    $contenido = true;
    $ig_minita = $video["insta"];
    $tiempo = $video["tiempo"]+1;
    $ig_minita = str_replace("@", "", $ig_minita);
} else {
    $titulo = "No hay canciones ahora mismo.";
    $miniatura = "404.png";
    $contenido = false;
}
$hayvideo = "";


?>

<style>
    * {
        font-family: 'Montserrat', sans-serif;
    }

    h1 {
        background-color: whitesmoke;
    }

    img {
        border-radius: 10px;
    }

    #img-principal {
        box-shadow: black, 10, 10, 10;
    }

    #img {
        margin-top: 10px;
    }

    /* The Modal (background) */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        padding-top: 100px;
        /* Location of the box */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    video {
        border-radius: 25px;
        -webkit-box-shadow: 0px 0px 92px -21px rgba(0, 0, 0, 0.75);
        -moz-box-shadow: 0px 0px 92px -21px rgba(0, 0, 0, 0.75);
        box-shadow: 0px 0px 92px -21px rgba(0, 0, 0, 0.75);
        max-width: 75%;
    }
    .instagram{
        border-radius: 15px;
        background-color: whitesmoke;
        padding: 5px;
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

        <?php

        if ($contenido == false) {
            echo '<img onerror="location.reload()" onloadeddata="color()" src="' . $miniatura . '" height="auto" width="100%" alt="" />';
        } else {
            echo '<video src="' . $videourl . '#t='.$tiempo.'" autoplay muted width="100%" height="auto"></video>';
        }

        ?>

        <img style="display: none;" onerror="location.reload()" id="img-principal" onloadeddata="color()" src="<?php echo $miniatura ?>" height="auto" width="100%" alt="" />

        <div id="siguientes">

        </div>
    </div>

    <div id="myModal" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <p id="noticia">Some text in the Modal..</p>
        </div>

    </div>
    <?php
    if ($ig_minita != "") {
        echo '<div class="instagram" style="position: fixed; left: 0; bottom: 0; display: flex">
        <img style="padding-top:5px; margin-left:5px" width="50px" height="50px" src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e7/Instagram_logo_2016.svg/768px-Instagram_logo_2016.svg.png">
        <h3 style="margin-left: 10px">@' . $ig_minita . '<h3>
        </div>';
    }
    ?>
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

<script>
    var anuncios = window.setInterval(function() {
        $.ajax({
            type: 'post',
            url: 'ajax.php',
            data: {
                anuncio: 'active',
            },
            success: function(response) {
                if (response.includes("anuncio")) {
                    var noticia = response.split(":");
                    document.getElementById("noticia").innerHTML = noticia[1];
                    document.getElementById("myModal").style.display = "block";

                } else {
                    document.getElementById("myModal").style.display = "none";
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
                nuevo: 'paquete',
            },
            success: function(response) {
                if (response != document.getElementById("siguientes").innerHTML) {
                    document.getElementById("siguientes").innerHTML = response;
                };
            },
            error: function() {}
        });

    }, 1000);
</script>