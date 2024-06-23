<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <?php 
        session_start();
        if (isset($_SESSION['ruolo'])) {
            include 'templatelog.html';
        }
        else {
            include 'template.html'; 
        }
    ?>

    <h1>Benvenuto su GymGenius!</h1>
    <?php 
        if (isset($_SESSION['ruolo'])) {
            echo '<h2>Sei loggato come ' . $_SESSION['nome'] . '</h2>';
        }
    ?>
</body>
</html>