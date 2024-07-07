<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prossime Lezioni</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .container-lista-lezioni {
            margin: 0 auto;
            width: 70em;
            height: 45em;
            text-align: center;
        }
        .lista-lezioni {
            overflow-y: scroll;
            overflow: hidden;
            height: 35em;
            text-align: left;
            padding-left: 5em;
            padding-right: 5em;
        }
        .lezione {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
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

    <div class="container-lista-lezioni border">
        <br>
        <h2>Le tue prossime lezioni</h2>
        <hr>
        <div class="lista-lezioni">
            
            <?php
                $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
                    or die('Could not connect: ' . pg_last_error());

                $user_id = $_SESSION['id_log'] ?? null;

                if ($dbconn && $user_id) {
                    // Query: prossime lezioni dell'utente loggato
                    $q = "
                        SELECT corso.nome AS corso_nome, lezione.giorno, lezione.data, lezione.luogo, lezione.time_from, lezione.time_to, lezione.id AS lezione_id
                        FROM lezione
                        INNER JOIN partecipa ON lezione.id = partecipa.id_lezione
                        INNER JOIN corso ON lezione.id_corso = corso.id
                        WHERE partecipa.id_user = $1
                        ORDER BY lezione.data, lezione.time_from
                    ";
                    $result = pg_query_params($dbconn, $q, array($user_id));

                    if (pg_num_rows($result) > 0) {
                        while ($tuple = pg_fetch_assoc($result)) {
                            $corso_nome = $tuple['corso_nome'];
                            $giorno = $tuple['giorno'];
                            $data = $tuple['data'];
                            $luogo = $tuple['luogo'];
                            $time_from = $tuple['time_from'];
                            $time_to = $tuple['time_to'];
                            $lezione_id = $tuple['lezione_id'];

                            echo '<div class="lezione">';
                            echo '<span style="font-weight: bold;">' . $corso_nome . '</span>';
                            echo '<span>' . $giorno . '</span>';
                            echo '<span>' . $data . '</span>';
                            echo '<span>' . $luogo . '</span>';
                            echo '<span>' . $time_from . ' - ' . $time_to . '</span>';
                            echo '<button class="btn btn-danger btn-cancella" data-lezione-id="' . $lezione_id . '">Cancella</button>';
                            echo '</div>';
                        }
                    } else {
                        echo '<h4 style="text-align: center;">Nessuna lezione prenotata</h4>';
                    }
                } else {
                    echo '<p>Devi essere loggato per vedere le tue prossime lezioni.</p>';
                }
            ?>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.btn-cancella').on('click', function() {
                var $this = $(this);
                var lezioneId = $this.data('lezione-id');

                $.ajax({
                    url: 'cancellazione_partecipazione.php',
                    method: 'POST',
                    data: {
                        lezione_id: lezioneId
                    },
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.success) {
                            alert('Partecipazione alla lezione cancellata con successo');
                            $this.closest('.lezione').remove();
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
