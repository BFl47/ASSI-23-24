<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Richiesta reset password</title>
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
    <?php 
        include 'template.html'; 
        session_start();
        
        if (isset($_SESSION['email_non_trovata'])) {
            echo '<script>alert("Email non presente nel sistema")</script>';
            $_SESSION['email_non_trovata'] = false;
            unset($_SESSION['email_non_trovata']);
        }
    ?>
    
    <div class="formlogin border">
        <br>
        <h1>Cambio password</h1>
        <form action="recuperopsw.php" method="post">
            <div class="form-group">
                <label for="emailpsw">Email</label>
                <input type="email" class="form-control" id="emailpsw" name="emailpsw" required>
            </div>
            <button type="submit" class="btn btn-primary">Invia richiesta</button>
        </form>
        <br>
</body>
</html>