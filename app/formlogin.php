<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script>
        <?php
            session_start();
            if (isset($_SESSION['login_non_riuscito'])) {
                echo 'alert("Spiacente, email o password errati")';
                $_SESSION['login_non_riuscito'] = false;
                unset( $_SESSION['login_non_riuscito']);
            }
        ?>
    </script>
    <style>
        .formlogin {
            margin: 0 auto;
            text-align: center;
            width: 50em;
        }
        .form-group {
            margin: 0 auto;
            width: 40em;
            margin-bottom: 1em;
        }
        .linkgithub {
            color: white;
            text-decoration:none;
        }
    </style>
</head>
<body>
<?php include 'template.html'; 
    session_start();
    if (isset($_SESSION['password_reset'])) {
        echo '<script>alert("Password modificata con successo")</script>';
        $_SESSION['password_reset'] = false;
        unset($_SESSION['password_reset']);
    }
    if (isset($_SESSION['richiesta_recuperopsw'])) {
        echo '<script>alert("Richiesta inviata con successo")</script>';
        $_SESSION['richiesta_recuperopsw'] = false;
        unset($_SESSION['richiesta_recuperopsw']);
    }
?>

<div class="formlogin border">
    <br>
    <h1>Accedi a GymGenius</h1>
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="emaillog">Email</label>
            <input type="email" class="form-control" id="emaillog" name="emaillog" required>
        </div>
        <div class="form-group">
            <label for="passwordlog">Password</label>
            <input type="password" class="form-control" id="passwordlog" name="passwordlog" required>
        </div>
        <button type="submit" class="btn btn-primary" id="accedi">Accedi</button>
    </form>
    <br>
    <button class="btn btn-secondary btn-dark"><a href="oauth_login.php" class="linkgithub" >Accedi con GitHub &nbsp;&nbsp;</a><i class="bi bi-github"></i></button>

    <br>
    <br>
    
    <a href="formsignup.php">Non hai un account? Registrati</a>
    <br>
    <br>
    <a href="formpsw.php">Hai dimenticato la password? Clicca qui!</a>
    <br>
    <br>

    
</div>
</body>
</html>