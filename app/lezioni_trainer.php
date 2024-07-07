<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lezioni Trainer</title>
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
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        .lezione span {
            flex: 1;
            text-align: left;
        }
        .lezione-header span {
            flex: 1;
            text-align: left;
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
        <h2>Le tue lezioni</h2>
        <hr>
        <div class="lista-lezioni">
            <?php
                $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
                    or die('Could not connect: ' . pg_last_error());

                $trainer_id = $_SESSION['id_log'] ?? null;

                if ($dbconn && $trainer_id) {
                    // query: lezioni del trainer loggato
                    $q = "
                        SELECT corso.nome AS corso_nome, lezione.giorno, lezione.data, lezione.luogo, lezione.time_from, lezione.time_to, lezione.id AS lezione_id, COUNT(partecipa.id_user) AS num_partecipanti
                        FROM lezione
                        INNER JOIN corso ON lezione.id_corso = corso.id
                        LEFT JOIN partecipa ON lezione.id = partecipa.id_lezione
                        WHERE corso.trainer = $1
                        GROUP BY corso.nome, lezione.giorno, lezione.data, lezione.luogo, lezione.time_from, lezione.time_to, lezione.id
                        ORDER BY lezione.data, lezione.time_from
                    ";
                    $result = pg_query_params($dbconn, $q, array($trainer_id));

                    if (pg_num_rows($result) > 0) {
                        while ($tuple = pg_fetch_assoc($result)) {
                            $corso_nome = $tuple['corso_nome'];
                            $giorno = $tuple['giorno'];
                            $data = $tuple['data'];
                            $luogo = $tuple['luogo'];
                            $time_from = $tuple['time_from'];
                            $time_to = $tuple['time_to'];
                            $num_partecipanti = $tuple['num_partecipanti'];
                            $lezione_id = $tuple['lezione_id'];

                            echo '<div class="lezione">';
                            echo '<span>' . $corso_nome . '</span>';
                            echo '<span>' . $giorno . '</span>';
                            echo '<span>' . $data . '</span>';
                            echo '<span>' . $luogo . '</span>';
                            echo '<span>' . $time_from . ' - ' . $time_to . '</span>';
                            echo '<span>' . $num_partecipanti . ' partecipanti</span>';
                            echo '<button class="btn btn-danger btn-cancella" data-lezione-id="' . $lezione_id . '">Cancella</button>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>Nessuna lezione trovata</p>';
                    }
                } else {
                    echo '<p>Devi essere loggato per vedere le tue lezioni.</p>';
                }
            ?>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.btn-cancella').on('click', function() {
                var lezioneId = $(this).data('lezione-id');

                $.ajax({
                    url: 'cancella_lezione.php',
                    method: 'POST',
                    data: { lezione_id: lezioneId },
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.success) {
                            alert(response.message);
                            location.reload();
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
