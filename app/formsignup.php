<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Signup</title>
    <style>
        .formsignup {
            margin: 0 auto;
            text-align: center;
            width: 50em;
        }
        .form-group {
            margin: 0 auto;
            width: 40em;
            margin-bottom: 1em;
        }
    </style>
    <script>
        function inArray(needle, haystack) {
            var length = haystack.length;
            for(var i = 0; i < length; i++) {
                if(haystack[i] == needle) return true;
            }
            return false;
        }
        function validaFormSignup() {
            var password = document.getElementById("password").value;
            var confermapassword = document.getElementById("confermapassword").value;
            console.log(password);
            console.log(confermapassword);
            if (password != confermapassword) {
                alert("Le password non coincidono");
                return false;
            }
            //alert("Dati inseriti correttamente");
            return true;
        }
    </script>
    <script>
        <?php
            session_start();
            if (isset($_SESSION['email_non_disponibile'])) {
                echo 'alert("Spiacente, l\'indirizzo email non é disponibile")';
                $_SESSION['email_non_disponibile'] = false;
                unset( $_SESSION['email_non_disponibile']);
            }
            if (isset($_SESSION['non_autorizzato'])) {
                echo 'alert("Spiacente, non hai i permessi necessari")';
                $_SESSION['non_autorizzato'] = false;
                unset( $_SESSION['non_autorizzato']);
            }
        ?>
    </script>
</head>
<body>
    <?php 
        if (isset($_SESSION['ruolo'])) {
            include 'templatelog.html';
        }
        else {
            include 'template.html'; 
        }
    ?>

    <div class="formsignup border">
        <br>
        <h1>Registrati a GymGenius</h1>
        <form action="signup.php" method="post" onsubmit="return validaFormSignup()">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" 
                title="La password deve contenere almeno un numero, una lettera minuscola, una lettera maiuscola, un carattere speciale e essere lunga almeno 8 caratteri." required>
            </div>
            <div class="form-group">
                <label for="confermapassword">Conferma Password</label>
                <input type="password" class="form-control" id="confermapassword" name="confermapassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" 
                title="La password deve contenere almeno un numero, una lettera minuscola, una lettera maiuscola, un carattere speciale e essere lunga almeno 8 caratteri." required>
            </div>
            <div class="form-group">
                <label for="ruolo">Ruolo</label>
                <select class="form-select" id="ruolo" name="ruolo" aria-label="Default select example" required>
                    <option selected></option>
                    <option value="Trainer">Trainer</option>
                    <option value="Admin">Admin</option>
                    <option value="User">User</option>
                </select>
            </div>

            <br>
            <button type="submit" class="btn btn-primary" id="signup">Signup</button>
            <br>
            <br>
        </form>
    </div>
</body>
</html>