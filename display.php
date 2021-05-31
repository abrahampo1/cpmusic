<?php

include("database.php");
$sql = "SELECT * FROM musica WHERE reproducida = 0 and datos = 1 LIMIT 1";
$do = mysqli_query($link, $sql);
$id_video = "";
$ig_minita = "";
$tiempo = 0;
$tiempo_total = 0;
$motd = "";
if ($do->num_rows > 0) {
    $video = mysqli_fetch_assoc($do);
    $id_video = $video["id"];
    $miniatura = "temp/" . $video["miniatura"] . ".png";
    $titulo = $video["titulo"];
    $videourl = $video["video"];
    $contenido = true;
    $ig_minita = $video["insta"];
    $tiempo = $video["tiempo"] + 1;
    $tiempo_total = $video["total_tiempo"];
    $ig_minita = str_replace("@", "", $ig_minita);
} else {
    $titulo = "No hay canciones ahora mismo.";
    $miniatura = "404.png";
    $contenido = false;
}
$hayvideo = "";
$sql = "SELECT * FROM ajustes WHERE nombre = 'motd'";
$do = mysqli_query($link, $sql);
$motd = mysqli_fetch_assoc($do);
$motd = $motd["value"];
if ($ig_minita == "") {
    $ig_minita = "franciscoasorey";
}
$sql = "SELECT * FROM ajustes WHERE nombre = 'volume'";
$do = mysqli_query($link, $sql);
$volumen = mysqli_fetch_assoc($do);
$volumen = $volumen["value"];
?>

<style>
    * {
        font-family: 'Montserrat', sans-serif;
        z-index: 100;
    }

    body {
        overflow: hidden;
    }

    h1 {
        text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white !important;
        font-family: 'Hind Siliguri', sans-serif !important;
        color: black !important;
    }

    img {
        border-radius: 10px;

    }

    .ies {
        width: 80%;
        height: auto;
        max-height: 90%;
    }

    .ies-div {
        text-align: center;
        width: 100%;
        height: auto;
        max-height: 90%;
    }

    .video-div {
        text-align: center;
        background-color: transparent;
        background: none;
        width: 70%;
        max-width: 140vh !important;
        overflow: visible;
    }

    #img-principal {
        box-shadow: black, 10, 10, 10;
    }

    #img {
        margin-top: 10px;
        height: auto;
        width: 100%;
    }

    /* The Modal (background) */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        border-radius: 25px !important;
        /* Stay in place */
        z-index: 101;
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

    div {
        height: auto;
    }

    /* Modal Content */
    .modal-content {
        background-color: #fefefe;
        border-radius: 25px;
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
        max-height: 70vh;

    }

    .instagram {
        border-radius: 15px;
        background-color: rgba(255, 255, 255, 0.7) !important;
        padding: 5px;
        justify-content: center;
        align-items: center;
        -webkit-box-shadow: 0px 0px 92px -21px rgba(0, 0, 0, 0.75);
        -moz-box-shadow: 0px 0px 92px -21px rgba(0, 0, 0, 0.75);
        box-shadow: 0px 0px 92px -21px rgba(0, 0, 0, 0.75);
        margin: 15px;
    }

    .hora {
        border-radius: 0px 15px 15px 0px;
        font-size: 40px;
        background-color: rgba(255, 255, 255, 0.7);
        padding: 5px;
        height: 75px;
        line-height: 0px;
        align-items: center;
        margin-bottom: 15px;
    }

    .motd {
        border-radius: 15px 0px 0px 15px;
        font-size: 40px;
        background-color: rgba(255, 255, 255, 0.7);
        padding: 5px;
        height: 75px;
        line-height: 0;
        z-index: 2;
        align-items: center;
        margin-bottom: 15px;
    }

    #myProgress {
        width: 93%;
        padding-left: 20px;
        padding-right: 10px;
        padding-top: -40px;
    }

    #myBar {
        width: 1%;
        border-radius: 7px;
        height: 15px;
        background-color: #04AA6D;
    }

    body {
        animation: fadeInAnimation ease 1s;
        animation-iteration-count: 1;
        animation-fill-mode: forwards;
    }

    .anterior {
        animation: fadeInAnimation ease 3s;
        animation-iteration-count: 1;
        animation-fill-mode: forwards;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .siguiente {
        animation: fadeInAnimation ease 2s;
        animation-iteration-count: 1;
        animation-fill-mode: forwards;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }

    .siguiente_text {
        animation: fadeInAnimation ease 1s;
        animation-iteration-count: 1;
        animation-fill-mode: forwards;
        overflow: hidden;
    }

    @keyframes fadeInAnimation {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    .hora_text {
        text-align: center;
    }

    .scroll-slow {
        overflow: hidden;
        justify-content: center;
        align-items: center;
    }

    .scroll-slow p {
        position: absolute;
        width: 100%;
        height: 100%;
        margin: 0;
        line-height: 75px;
        text-align: center;
        /* Starting position */
        -moz-transform: translateX(100%);
        -webkit-transform: translateX(100%);
        transform: translateX(100%);
        /* Apply animation to this element */
        -moz-animation: scroll-slow 25s linear infinite;
        -webkit-animation: scroll-slow 25s linear infinite;
        animation: scroll-slow 25s linear infinite;
    }

    .vjs-default-skin {
        width: 100% !important;
        height: auto !important;
        min-height: 700px;

    }

    /* Move it (define the animation) */
    @-moz-keyframes scroll-slow {
        0% {
            -moz-transform: translateX(100%);
        }

        100% {
            -moz-transform: translateX(-100%);
        }
    }

    @-webkit-keyframes scroll-slow {
        0% {
            -webkit-transform: translateX(100%);
        }

        100% {
            -webkit-transform: translateX(-100%);
        }
    }

    @keyframes scroll-slow {
        0% {
            -moz-transform: translateX(100%);
            /* Browser bug fix */
            -webkit-transform: translateX(100%);
            /* Browser bug fix */
            transform: translateX(100%);
        }

        100% {
            -moz-transform: translateX(-100%);
            /* Browser bug fix */
            -webkit-transform: translateX(-100%);
            /* Browser bug fix */
            transform: translateX(-100%);
        }
    }

    .slide-in-right {
        -webkit-animation: slide-in-right .2s cubic-bezier(.25, .46, .45, .94) both;
        animation: slide-in-right .2s cubic-bezier(.25, .46, .45, .94) both;
        overflow: hidden;
    }

    @-webkit-keyframes slide-in-right {
        0% {
            -webkit-transform: translateX(1000px);
            transform: translateX(1000px);
            opacity: 0
        }

        100% {
            -webkit-transform: translateX(0);
            transform: translateX(0);
            opacity: 1
        }
    }

    @keyframes slide-in-right {
        0% {
            -webkit-transform: translateX(1000px);
            transform: translateX(1000px);
            opacity: 0
        }

        100% {
            -webkit-transform: translateX(0);
            transform: translateX(0);
            opacity: 1
        }
    }

    .anterior-img {
        border-radius: 10px 0px 0px 10px;
    }

    .siguiente-img {
        border-radius: 0px 10px 10px 0px !important;
    }

    .container {
        position: relative;
        text-align: center;
        color: white;
    }

    .centered {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>

<head>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/video.js/dist/video-js.css" rel="stylesheet">
</head>

<body id="back" onload="color()" style="text-align: center;">

    <h1><?php echo $titulo ?></h1>
    <div style="display: flex; width:100%; background-color: none">
        <div class="anterior" style="width: 15%;">

            <?php
            $sql = "SELECT * FROM musica WHERE reproducida = 1 ORDER BY id desc LIMIT 1";
            $do = mysqli_query($link, $sql);
            if ($do->num_rows > 0) {
                //echo '<h1>Anterior:</h1>';
            }
            while ($video_query = mysqli_fetch_assoc($do)) {
                $video_id = explode("?v=", $video_query["urlspoti"]);
                $video_id = $video_id[1];
                $thumbnail = "temp/" . $video_query["miniatura"] . ".png";
                echo '<img id="img" width="100%" class="anterior-img" onload="" src="' . $thumbnail . '" alt="" />';
            }
            ?>
        </div>

        <?php

        if ($contenido == false) {
            echo '<div class="ies-div"><img class="ies" onerror="location.reload()" onloadeddata="color()" src="' . $miniatura . '" height="auto" width="100%" alt=""></div>';
        } else {
            echo '<div class="video-div"><input type="hidden" id="video_tiempo" value="' . $tiempo . '"><input type="hidden" id="video_total" value="' . $tiempo_total . '"><video src="' . $videourl . '#t=' . $tiempo . '" id="videoclip" volume="' . $volumen . '" onload="barra()" onloadstart="barra()" controls autoplay width="100%" height="auto"></video><div id="myProgress">
            <div id="myBar"></div>
          </div></div>';
        }

        ?>

        <img style="display: none;" onerror="location.reload()" id="img-principal" onloadeddata="color()" src="<?php echo $miniatura ?>" height="auto" width="100%" alt="" />

        <div class="siguiente" onload="loadnext()" style="width: 15%;">
            <div id="siguientes" style="width: 100%"></div>
        </div>
    </div>


    <div style="display: flex; width:100%;">
        <?php
        if ($ig_minita != "") {
            echo '<div class="instagram" style="position: fixed; left: 0; bottom: 0; height: 75px; display: flex; max-width: 19%">
        <img style="padding-top:5px; margin-left:5px" width="50px" height="50px" src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e7/Instagram_logo_2016.svg/768px-Instagram_logo_2016.svg.png">
        <h3 style="margin-left: 10px">@' . $ig_minita . '<h3>
        </div>';
        }
        ?>
        <div style="width: 100%;">
            <div class="motd scroll-slow" style="position: fixed; right: calc(6% + 25px); bottom: 0; display: flex; width: 70%">
                <p><?php echo $motd ?></p>

            </div>
        </div>
        <div class="hora" style="position: fixed; right: 0; bottom: 0; width: 6%; margin-right: 15px; text-align: center">
            <p class="hora_text" id="hora"></p>
        </div>

    </div>

</body>
<div id="siguiente_holder" style="display: none;">

</div>
<div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <p id="noticia"></p>
    </div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.0/color-thief.umd.js"></script>
<script src="https://unpkg.com/video.js/dist/video.js"></script>
<script src="https://unpkg.com/@videojs/http-streaming/dist/videojs-http-streaming.js"></script>

<script>
    function loadvideo() {
        var player = videojs('my_video_1');
    }
</script>
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
    function barra() {
        var elem = document.getElementById("myBar");
        var video = document.getElementById("videoclip");
        if (video && elem) {
            var tiempototal = document.getElementById("video_total").value;
            var video = document.getElementById("videoclip");
            var tiempo = video.currentTime;
            console.log(tiempototal);
            console.log(tiempo);
            var width = (tiempo / tiempototal) * 100;
            elem.style.width = width + "%";

        }
    }
    var updatetime = window.setInterval(function() {
        function barra() {
            var elem = document.getElementById("myBar");
            var video = document.getElementById("videoclip");
            if (video && elem) {
                var video = document.getElementById("videoclip");
                var tiempototal = video.duration;
                var tiempo = video.currentTime;
                //console.log(tiempototal);
                //console.log(tiempo);
                var width = (tiempo / tiempototal) * 100;
                elem.style.width = width + "%";

            }
        }
        barra();
    }, 500);
</script>
<script>
    var updatetime = window.setInterval(function() {
        function siguiente_en() {
            var siguiente = document.getElementById("siguiente_texto");
            if (siguiente) {
                var video = document.getElementById("videoclip");
                var tiempototal = video.duration;
                var tiempo = video.currentTime;
                var restante = tiempototal - tiempo;
                if (restante < 31 && restante != 0 && restante != -0) {
                    siguiente.innerHTML = "En " + restante.toFixed(0) + "...";
                }
                if (restante <= 0) {
                    video.pause();
                    video.src = "";
                    siguiente.innerHTML = "Cargando...";
                }
            }


        }
        siguiente_en();
    }, 500);
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
                if (document.getElementById("noticia").innerHTML == "") {
                    if (response == "terminada" || response == "nuevo") {
                        setTimeout(function() {
                            location.reload();
                        }, 100);
                    }
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
                get_volume: 'active',
            },
            success: function(response) {
                if (response != "") {
                    document.getElementById("videoclip").volume = response;
                };
            },
            error: function() {}
        });

    }, 100);
</script>
<script>
    var playerstate = "";
    var anuncios = window.setInterval(function() {
        $.ajax({
            type: 'post',
            url: 'ajax.php',
            data: {
                getplayerstate: playerstate,
            },
            success: function(response) {
                if (response == "play" && document.getElementById("videoclip").paused == true) {
                    document.getElementById("videoclip").play();
                    playerstate = response;
                }
                if (response == "pause" && document.getElementById("videoclip").paused == false) {
                    document.getElementById("videoclip").pause();
                    playerstate = response;
                };
            },
            error: function() {}
        });

    }, 500);
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
                if (response != "") {
                    if (document.getElementById("noticia").innerHTML == "") {
                        document.getElementById("noticia").innerHTML = response;
                        document.getElementById("myModal").style.display = "block";
                        loadvideo();
                    }

                } else if (response == "") {
                    if (document.getElementById("noticia").innerHTML != "") {
                        location.reload();
                    }
                    document.getElementById("myModal").style.display = "none";
                };
            },
            error: function() {}
        });

    }, 1000);
</script>
<script>
    function loadnext(el, segundos) {
        if (el.style.display == "none") {
            setTimeout(function() {
                el.style.display = "inline";
                el.classList.add("slide-in-right");
            }, segundos);

        } else {}
    };
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
                if (document.getElementById("noticia").innerHTML == "") {
                    if (response != document.getElementById("siguiente_holder").innerHTML) {
                        document.getElementById("siguientes").style.width = "100%";
                        responsenew = response.replace(document.getElementById("siguiente_holder").innerHTML, "");
                        document.getElementById("siguientes").innerHTML = document.getElementById("siguientes").innerHTML + responsenew;
                        document.getElementById("siguiente_holder").innerHTML = response;
                    } else if (response == "") {
                        document.getElementById("siguientes").style.width = "";
                    }
                };
            },
            error: function() {}
        });

    }, 1000);
</script>

<script>
    function clock() {
        var hours = document.getElementById("hora");

        var h = new Date().getHours();
        var m = new Date().getMinutes();
        h = h < 10 ? "0" + h : h;
        m = m < 10 ? "0" + m : m;

        hours.innerHTML = h + ":" + m;
    }

    var interval = setInterval(clock, 500);
</script>

<script>
    // this function must be defined in the global scope
    $(document).ready(function() {
        $(".fadeIn").each(function() {
            var src = $(this).data("src");
            if (src) {
                var img = new Image();
                img.style.display = "none";
                img.onload = function() {
                    $(this).fadeIn(1000);
                };
                $(this).append(img);
                img.src = src;
            }
        });
    });
</script>