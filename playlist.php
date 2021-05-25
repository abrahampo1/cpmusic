<?php
include("database.php");
if(isset($_GET["p"])){
    $playlist = $_GET["p"];
}else{
    header("location: /");
}

?>
<!doctype html>
<html>

<head>
    <title>Musica</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="iconos/css/all.css" rel="stylesheet">
    <style>
        * {
            font-family: 'Montserrat', sans-serif;
        }

        button {
            background: none;
            color: inherit;
            border: none;
            padding: 0;
            font: inherit;
            cursor: pointer;
            outline: inherit;
        }
        .btn {
        padding: 10px;
        background: none;
        border: none;
        background-color: black;
        border-radius: 25px;
        margin: 20px;
        color: white !important;
    }

        body {
            width: 99%;
            padding: 10px;
            text-align: center;
        }

        .search-form-container {
            background: #F0F0F0;
            border: #e0dfdf 1px solid;
            padding: 20px;
            border-radius: 2px;
        }

        .input-row {
            margin-bottom: 20px;
        }

        .input-field {
            width: 100%;
            border-radius: 30px;
            padding: 10px;
            text-align: center;
            font-size: 20px;
            border: #e0dfdf 1px solid;
        }

        .btn-submit {
            background: #333;
            border: #1d1d1d 1px solid;
            color: #f0f0f0;
            font-size: 0.9em;
            width: 100px;
            height: 50px;
            border-radius: 20px;
            font-size: 20px;
            cursor: pointer;
        }

        .videos-data-container {
            padding: 20px;
            border-radius: 2px;
        }

        .response {
            padding: 10px;
            margin-top: 10px;
            border-radius: 2px;
        }

        .error {
            background: #fdcdcd;
            border: #ecc0c1 1px solid;
        }

        .success {
            background: #c5f3c3;
            border: #bbe6ba 1px solid;
        }

        .result-heading {
            margin: 20px 0px;
            padding: 20px 10px 5px 0px;
            border-bottom: #e0dfdf 1px solid;
        }

        iframe {
            border: 0px;
        }

        .video-tile {
            display: inline-block;
            margin: 10px 10px 20px 10px;
        }

        .videoDiv {
            width: 250px;
            height: 150px;
            display: inline-block;
        }

        .videoTitle {
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        .videoDesc {
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        .videoInfo {
            width: 250px;
        }

        .inicio {
            padding: 10px;
        }

        .video-tile {
            animation: fadeInAnimation ease 1s;
            animation-iteration-count: 1;
            animation-fill-mode: forwards;
        }

        @keyframes fadeInAnimation {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .container {
            position: relative;
            text-align: center;
            color: white;
        }

        .centered {
            position: absolute;
            top: 80%;
            left: 90%;
            transform: translate(-50%, -50%);
        }

        .fav {
            text-decoration: none;
            background-color: whitesmoke;
            border-radius: 100%;
            padding: 5px;
            color: #222222;
        }
        .media {
            margin-left: -5px;
            margin-right: -5px;
            font-size: 25px;
            margin: 10px;
            padding: 5px;
        }

        .media.off {
            border-radius: 25px 0px 0px 25px;
            margin-right: 0px !important;

        }
        nav {
            height: 60px;
            text-align: center;
            background-color: white;
        }
        nav img{
            height: 50px;
        }

        .admin {
            position: fixed;
            left: 0;
            top: 0;
        }
    </style>

</head>
<nav>
        <img style="display: inline;" src="logo.png" height="100%" alt="">
        <form class="admin" method="POST" action="./">
            <button class="media off" value="paquete"><i class="fas fa-arrow-left"></i></button>
        </form>
    </nav>
<body>

    </div>
    <div class="videos-data-container" id="playlist">
        <h2>Cargando...</h2>
    </div>
</body>
<p id="debug"></p>

</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.0/color-thief.umd.js"></script>


<script>
    function igminita() {
        var iglargo = document.getElementById("ig-minita").value;
        if (iglargo.length >= 20) {
            iglargo = "";
        }
    }
</script>

<script>
    var siguientes = window.setInterval(function() {
        $.ajax({
            type: 'post',
            url: 'ajax.php',
            data: {
                playlist: <?php echo "'".$playlist."'" ?>,
            },
            success: function(response) {
                if (response != document.getElementById("playlist").innerHTML) {
                    console.log(response);
                    console.log(document.getElementById("playlist").innerHTML);
                    document.getElementById("playlist").innerHTML = response;

                };
            },
            error: function() {}
        });
    }, 1000);
</script>
<script>
    function addfav(url) {
        $.ajax({

            type: 'post',
            url: 'ajax.php',
            data: {
                favorita: url,
            },
            success: function(response) {

            },
            error: function() {}
        });
    }

    function addytfav(url, star) {
        $.ajax({

            type: 'post',
            url: 'ajax.php',
            data: {
                favorita: url,
            },
            success: function(response) {
                if (star.innerHTML == '<i class="fas fa-star"></i>') {
                    star.innerHTML = '<i class="far fa-star"></i>';
                } else {
                    star.innerHTML = '<i class="fas fa-star"></i>';
                }
            },
            error: function() {}
        });
    }
</script>
<script>
    if (window.history.replaceState) { // verificamos disponibilidad
        window.history.replaceState(null, null, window.location.href);
    }
</script>