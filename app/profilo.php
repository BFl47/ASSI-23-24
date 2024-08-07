<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo</title>

    <script>
       
        function validaCambioPsw() {
            var nuovapassword = document.getElementById("nuovapassword").value;
            var confermapassword = document.getElementById("confermapassword").value;
            if (nuovapassword != confermapassword) {
                alert("Le password non coincidono");
                return false;
            } else {
                return true;
            }
        }
        function aggiornaImg() {
            var fileInput = document.getElementById('imgProfilo');
            var anteprima = document.getElementById('anteprimaImg');

            if (fileInput.files.length != 0) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    anteprima.src = e.target.result; // sorgente anteprima = url fileinput
                };

                reader.readAsDataURL(fileInput.files[0]);
            }
        } 
    </script>

    <style>
        .profcontainer {
            margin-left: 15em;
            margin-top: 5em;
            text-align: center;
            width: 30em;
            height: auto;
        }
        .restoprofilo {
            margin-left: 55em;
            margin-top: -50.5em;
            width: 50em;
            height: auto;
            padding: 5em;
        }
        .infoprof {
            margin: 0 auto;
            width: 20em;
            margin-bottom: 1em;
        }
        .datiprof {
            margin-left: 1em;
            text-align: left;
            width: 30em;
        }
        .form-group {
            margin: 0 auto;
            width: 20em;
            margin-bottom: 1em;
        }
        #anteprimaImg {
            width: 10em;
            height: 10em;
            border-radius: 50%;
        } 
    </style>
</head>
<body>
    <?php 
        session_start();
        include 'templatelog.html'; 
        if (isset($_SESSION['cambio_psw_non_riuscito'])) {
            echo '<script>alert("Cambio password non riuscito")</script>';
            $_SESSION['cambio_psw_non_riuscito'] = false;
            unset( $_SESSION['cambio_psw_non_riuscito']);
        }
        if (isset($_SESSION['cambio_psw_riuscito'])) {
            echo '<script>alert("Cambio password effettuato con successo")</script>';
            $_SESSION['cambio_psw_riuscito'] = false;
            unset( $_SESSION['cambio_psw_riuscito']);
        }
    ?>
    <div class="profcontainer border"> 
        <div class="infoprof">
            <br>
            <img src="<?php echo $_SESSION['path_img'] ?>" alt="Immagine profilo" name="anteprimaImg" id="anteprimaImg">  
            <br>          
            <br>
            <div class="datiprof">
                Nome: <?php echo $_SESSION['nome'] ?> <br>
                Email: <?php echo $_SESSION['email'] ?> <br>
                Ruolo: <?php echo $_SESSION['ruolo'] ?> <br>
            </div>
            <form action="cambiaimg.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="imgProfilo" class="form-label"></label>
                    <input class="form-control" id="imgProfilo" name="imgProfilo" type="file" accept="image/png, image/jpeg" onchange="return aggiornaImg();">
                </div>
                <button type="submit" class="btn btn-secondary">Cambia immagine</button>
            </form>

            <br>
            
            <form action="cambiopsw.php" method="post" onsubmit="return validaCambioPsw()">
                <div class="form-group">
                    <label for="vecchiapassword">Vecchia password</label>
                    <input type="password" class="form-control" id="vecchiapassword" name="vecchiapassword" required <?php if (isset($_SESSION['github'])) echo 'disabled';?> >
                </div>
                <div class="form-group">
                    <label for="nuovapassword">Nuova password</label>
                    <input type="password" class="form-control" id="nuovapassword" name="nuovapassword" required <?php if (isset($_SESSION['github'])) echo 'disabled';?> >
                </div>
                <div class="form-group">
                    <label for="confermapassword">Conferma password</label>
                    <input type="password" class="form-control" id="confermapassword" name="confermapassword" required  <?php if (isset($_SESSION['github'])) echo 'disabled';?> >
                </div>
                <button type="submit" class="btn btn-secondary">Cambia password</button>
                <br>
                <br>
            </form>
        </div>
    
    </div>

    <br>
    <br>
    <br>

    <div class="restoprofilo border">
        <?php
        if (isset($_SESSION['ruolo'])){
                if($_SESSION['ruolo'] == 'User'){
                    include 'profilouser_resto.html';
                }else if($_SESSION['ruolo'] == 'Trainer'){
                    include 'profilotrainer_resto.html';
                } else if($_SESSION['ruolo'] == 'Admin'){
                    include 'profiloadmin_resto.html';
                }
        }
        ?>
    </div>
</body>
</html>