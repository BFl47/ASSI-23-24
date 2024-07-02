<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Corsi</title>
    <style>
        .container-lista-corsi {
            margin: 0 auto;
            width: 70em;
            height: 45em;
            text-align: center;
            
        }
        .lista-corsi {
            overflow-y: scroll;
            overflow: hidden;
            height: 35em;
            text-align: left;
            padding-left: 5em;
            padding-right: 5em;
        }
        .btn-elimina, .btn-modifica {
            float: right;
            margin-right: 1em;
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

        if (isset($_SESSION['corsoeliminato'])) {
            echo '<script>alert("Corso eliminato con successo!")</script>';
            unset($_SESSION['corsoeliminato']);
        }
        if (isset($_SESSION['corsocreato'])) {
            echo '<script>alert("Corso creato con successo!")</script>';
            unset($_SESSION['corsocreato']);
        }
    ?>

    <div class="container-lista-corsi border">
        <br>
        <h2>Tutti i corsi</h2>
        <hr>
        <div class="lista-corsi" >
            
            <?php
                $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
                    or die('Could not connect: ' . pg_last_error());
                if ($dbconn) {
                    $q = "SELECT * FROM corso";
                    $result = pg_query($dbconn, $q);

                    while ($tuple = pg_fetch_assoc($result)) {
                        echo '
                            <div class="corso">'
                                . $tuple['nome'] . '&emsp;&emsp; resto delle info';

                                // solo per l'admin: eliminazione
                                if (isset($_SESSION['ruolo']) && $_SESSION['ruolo'] == 'Admin') {
                                    $id = $tuple['id'];
                                    echo '
                                    <form id="form-elimina-'.$id.'" method="POST" action="./google_calendar_add_event/deleteEvent.php" style="display:inline;">
                                        <input type="hidden" name="id" value="'.$id.'">
                                        <button type="submit" class="btn btn-danger btn-elimina" id="mod-'.$id.'">Elimina</button>
                                    </form>';
                                }
                                echo '
                                <hr>
                            </div>
                            ';
                    }
                }

            ?>
        </div>
        <?php
            // solo per l'admin: creazione
            if (isset($_SESSION['ruolo']) && $_SESSION['ruolo'] == 'Admin') {
                echo '<a type="button" class="btn btn-primary btn-addCorso" href="/app/google_calendar_add_event/index.php">Aggiungi nuovo corso</a>';
            }
            
        ?>
    </div>
    
</body>
</html>