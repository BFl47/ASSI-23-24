<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style> 
        h1 {
            text-align: center;
            margin-top: 5em;
        }
        h2 {
            text-align: center;
            margin-top: 1em;
        }
        .calendario-home {
            margin: 0 auto;
            width: 50em;
        }
        .descrizione-home {
            margin: 0 auto;
            width: 50em;
        }
        .corsi-home {
            margin: 0 auto;
            width: 50em;
        }
    </style>
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

    <div class="calendario-home border">
        <h2>Calendario corsi</h2>
        <iframe src="https://calendar.google.com/calendar/embed?src=c_1f9546d9b401e2377cd81a011d90db4d01136ff427891fee6bd7c19181c43709%40group.calendar.google.com&ctz=Europe%2FRome&wkst=2" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
    </div>

    <div class="descrizione-home border">
        <h2>Descrizione</h2>
    </div>

    <div class="corsi-home border">
        <h2>Corsi</h2>
    </div>

</body>
</html>