<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corsi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .container-lista-corsi {
            margin: 0 auto;
            width: 70em;
            height: 45em;
            text-align: center;
        }
        .lista-corsi {
            overflow-y: scroll;
            /*overflow: hidden;*/
            height: 35em;
            text-align: left;
            padding-left: 5em;
            padding-right: 5em;
        }
        .btn-elimina, .btn-modifica {
            float: right;
            margin-right: 1em;
        }
        .favorite {
            cursor: pointer;
            font-size: 1.5em;
            color: gray;
            margin-right: 10px;
        }
        .favorite.filled {
            color: red;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        function confermaEliminazione() {
            var conferma = confirm("Sei sicuro di voler procedere?");
            return conferma;
        }
    </script>
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
        <div class="lista-corsi">
            
            <?php
                $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
                    or die('Could not connect: ' . pg_last_error());
                if ($dbconn) {
                    $q = "SELECT * FROM corso";
                    $result = pg_query($dbconn, $q);

                    while ($tuple = pg_fetch_assoc($result)) {
                        $id_corso = $tuple['id'];
                        $user_id = $_SESSION['id_log'] ?? null;
                        $is_favorite = false;

                        echo '<div class="corso">';
                        
                        // mostra cuori
                        if (isset($_SESSION['ruolo']) && $_SESSION['ruolo'] == 'User') {
                            if ($user_id) {
                                // verifica se il corso è tra i preferiti dell'utente
                                $q_favorite = "SELECT 1 FROM preferito WHERE id_corso = $1 AND id_user = $2";
                                $result_favorite = pg_query_params($dbconn, $q_favorite, array($id_corso, $user_id));
                                $is_favorite = pg_num_rows($result_favorite) > 0;
                            }
                            echo '<span class="favorite '.($is_favorite ? 'filled' : '').'" data-corso-id="'.$id_corso.'">&#9829;</span>';
                        }

                        echo '<h3 style="display:inline">' . $tuple['nome'] . '</h3>';

                        // solo per l'admin: eliminazione
                        if (isset($_SESSION['ruolo']) && $_SESSION['ruolo'] == 'Admin') {
                            echo '
                            <form id="form-elimina-'.$id_corso.'" method="POST" action="./google_calendar_add_event/deleteEvent.php" style="display:inline;">
                                <input type="hidden" name="id" value="'.$id_corso.'">
                                <button type="submit" class="btn btn-danger btn-elimina" id="mod-'.$id_corso.'" onclick="return confermaEliminazione();">Elimina</button>
                            </form>';
                        }

                        // lezioni del corso
                        $q_lezioni = "SELECT DISTINCT giorno, time_from, time_to FROM lezione WHERE id_corso = $1";
                        $result_lezioni = pg_query_params($dbconn, $q_lezioni, array($id_corso));
                        
                        echo '<ul>';
                        while ($lezione = pg_fetch_assoc($result_lezioni)) {
                            echo '<li>' . $lezione['giorno'] . ' - ore ' . $lezione['time_from'] . '-' . $lezione['time_to'] . '</li>';
                        }
                        echo '</ul>';

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
    
    <!-- gestione dei preferiti -->
    <script>
        $(document).ready(function() {
            $('.favorite').on('click', function() {
                var $this = $(this);
                var corsoId = $this.data('corso-id');
                var action = $this.hasClass('filled') ? 'remove' : 'add';

                $.ajax({
                    url: 'update_favorite.php',
                    method: 'POST',
                    data: {
                        action: action,
                        corso_id: corsoId
                    },
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.success) {
                            if (action === 'add') {
                                $this.addClass('filled');
                            } else {
                                $this.removeClass('filled');
                            }
                        } else {
                            alert('Errore: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Errore nella comunicazione col server.');
                    }
                });
            });
        });
    </script>
</body>
</html>
