<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
    </style>
</head>
<body>
<?php include 'template.html'; ?>

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
        <button type="submit" class="btn btn-primary">Accedi</button>
    </form>
    <br>
    <a href="formsignup.php">Non hai un account? Registrati</a>
    <br>
    <br>
</div>
</body>
</html>