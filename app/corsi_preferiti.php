<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corsi Preferiti</title>
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
        .rating {
            display: inline-block;
            font-size: 1.5em;
            color: gray;
            cursor: pointer;
        }
        .rating .filled {
            color: gold;
        }
        .lezione {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .lezione-buttons {
            display: flex;
            justify-content: flex-end;
            flex-grow: 1;
            margin-left: 20px;
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

    <div class="container-lista-corsi border">
        <br>
        <h2>I tuoi corsi preferiti</h2>
        <hr>
        <div class="lista-corsi">
            
            <?php
                $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
                    or die('Could not connect: ' . pg_last_error());

                $user_id = $_SESSION['id_log'] ?? null;

                if ($dbconn && $user_id) {
                    // query: corsi preferiti dell'utente
                    $q = "
                        SELECT corso.*, preferito.rating
                        FROM corso 
                        INNER JOIN preferito ON corso.id = preferito.id_corso 
                        WHERE preferito.id_user = $1
                    ";
                    $result = pg_query_params($dbconn, $q, array($user_id));

                    while ($tuple = pg_fetch_assoc($result)) {
                        $id_corso = $tuple['id'];
                        $rating = $tuple['rating'] ?? 0;

                        echo '<div class="corso">';
                        echo '<span class="favorite filled" data-corso-id="'.$id_corso.'">&#9829;</span>';
                        echo '<h3 style="display:inline; margin-right: 10px;">' . $tuple['nome'] . '</h3> ';
                        
                        // star rating
                        echo '<div class="rating" data-corso-id="'.$id_corso.'">';
                        for ($i = 1; $i <= 5; $i++) {
                            echo '<span class="star '.($i <= $rating ? 'filled' : '').'" data-value="'.$i.'">&#9733;</span>';
                        }
                        echo '</div>';

                        // query: lezioni del corso corrente
                        $q_lezioni = "SELECT id, giorno, data, time_from, time_to FROM lezione WHERE id_corso = $1";
                        $result_lezioni = pg_query_params($dbconn, $q_lezioni, array($id_corso));
                        echo '<ul>';
                        while ($lezione = pg_fetch_assoc($result_lezioni)) {
                            $id_lezione = $lezione['id'];
                            echo '<li class="lezione">' . $lezione['giorno'] . ' ' . $lezione['data'] . ' - ore ' . $lezione['time_from'] . '-' . $lezione['time_to'] . 
                                '<div class="lezione-buttons"><button class="btn btn-success btn-partecipa" style="margin: 5px;" data-lezione-id="'.$id_lezione.'">Partecipa</button>' . 
                                '<button class="btn btn-danger btn-cancella" style="margin: 5px;" data-lezione-id="'.$id_lezione.'">Cancella</button></div></li>';
                        }
                        echo '</ul>';
                    }                    
                } 
                else {
                    echo '<p>Devi essere loggato per vedere i tuoi corsi preferiti.</p>';
                }
            ?>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // gestione preferiti
            $('.favorite').on('click', function() {
                var $this = $(this);
                var corsoId = $this.data('corso-id');
                var action = 'remove';

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
                            $this.removeClass('filled');
                            $this.closest('.corso').remove();
                        } else {
                            alert('Errore: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Errore nella comunicazione col server.');
                    }
                });
            });

            // gestione star rating
            $('.rating .star').on('click', function() {
                var $this = $(this);
                var rating = $this.data('value');
                var corsoId = $this.parent().data('corso-id');

                $.ajax({
                    url: 'update_rating.php',
                    method: 'POST',
                    data: {
                        rating: rating,
                        corso_id: corsoId
                    },
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.success) {
                            $this.parent().children('.star').each(function() {
                                var $star = $(this);
                                $star.toggleClass('filled', $star.data('value') <= rating);
                            });
                        } else {
                            alert('Errore: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Errore nella comunicazione col server.');
                    }
                });
            });

            // gestione partecipazione lezione
            $('.btn-partecipa').on('click', function() {
                var $this = $(this);
                var lezioneId = $this.data('lezione-id');

                $.ajax({
                    url: 'partecipazione.php',
                    method: 'POST',
                    data: {
                        lezione_id: lezioneId
                    },
                    success: function(response) {
                        response = JSON.parse(response);
                        alert(response.message);
                    },
                    error: function() {
                        alert('Errore nella comunicazione col server.');
                    }
                });
            });

            // gestione cancellazione partecipazione
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
                        alert(response.message);
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
