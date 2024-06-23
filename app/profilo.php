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
    </script>

    <style>
        .profcontainer {
            margin-left: 20em;
            margin-top: 5em;
            text-align: center;
            width: 30em;
        }
        .infoprof {
            margin: 0 auto;
            width: 20em;
            margin-bottom: 1em;
        }
        .datiprof {
            margin-left: 5em;
            text-align: left;
        }
        .form-group {
            margin: 0 auto;
            width: 20em;
            margin-bottom: 1em;
        }
        img {
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
            <img src="/app/assets/profile.jpg" alt="Immagine profilo"> 
            <br>
            <br>
            <div class="datiprof">
                Nome: <?php echo $_SESSION['nome'] ?> <br>
                Email: <?php echo $_SESSION['email'] ?> <br>
                Ruolo: <?php echo $_SESSION['ruolo'] ?> <br>
            </div>
            <br>
        </div>

        <div class="cambiopsw">
            <form action="cambiopsw.php" method="post" onsubmit="return validaCambioPsw();">
                <div class="form-group">
                    <label for="vecchiapassword">Vecchia password</label>
                    <input type="password" class="form-control" id="vecchiapassword" name="vecchiapassword" required>
                </div>
                <div class="form-group">
                    <label for="nuovapassword">Nuova password</label>
                    <input type="password" class="form-control" id="nuovapassword" name="nuovapassword" required>
                </div>
                <div class="form-group">
                    <label for="confermapassword">Conferma password</label>
                    <input type="password" class="form-control" id="confermapassword" name="confermapassword" required>
                </div>
                <button type="submit" class="btn btn-secondary">Cambia password</button>
                <br>
                <br>
            </form>
        </div>
    </div>
    
</body>
</html>