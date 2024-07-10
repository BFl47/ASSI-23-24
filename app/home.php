<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybC1cFZhZRirxb6OLJp6N1RTvyuQtP4Ylhcq5P5K5nAYN0fEO" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuP2n5lxrjWsK1f2fGzK9YQb9hvD0CJ2V5iEXj4I5i0E5Ll89NHEuzHcmDA6j6gD" crossorigin="anonymous"></script>
    -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>

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
        p {
            text-align: left;
            margin-top: 1em;
        }
        #desc {
            margin: 1em;
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
        .course-title {
            display: flex;
            align-items: center;
            margin-bottom: 0.5em;
        }
        .star-rating {
            display: flex;
            align-items: center;
            margin-left: 1em;
        }
        .star {
            font-size: 1rem;
            color: gold;
        }
        .full-star {
            color: gold;
        }
        .half-star {
            color: lightgray;
            position: relative;
        }
        .half-star::before {
            content: '\2605';
            color: gold;
            position: absolute;
            left: 0;
            width: 50%;
            overflow: hidden;
        }
        .empty-star {
            color: lightgray;
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
        <p id="desc">GymGenius è un'applicazione progettata per supportare la gestione di una palestra, fornendo funzionalità specifiche per utenti (clienti), trainer e l'admin.</p>
    </div>
        
    <div class="corsi-home border">
        <h2>Corsi</h2>
        
        <?php
            function getCorrectDescription($rawDescription) {
                $parts = explode('<br>Trainer:', $rawDescription);
                return $parts[0];
            }

            $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
            or die('Could not connect: ' . pg_last_error());
            if ($dbconn) {
                $query = "
                    SELECT 
                        c.id, 
                        c.nome, 
                        c.descrizione, 
                        COALESCE(AVG(CAST(p.rating AS float)), 0) AS star_rating,
                        COUNT(p.rating) AS review_count
                    FROM 
                        corso c 
                    LEFT JOIN 
                        preferito p 
                    ON 
                        c.id = p.id_corso 
                    GROUP BY 
                        c.id, c.nome, c.descrizione";
                $result = pg_query($dbconn, $query);

                if (!$result) {
                    echo "Errore nella query: " . pg_last_error();
                } else {
                    echo '<ul>';
                    while ($row = pg_fetch_assoc($result)) {
                        $fullStars = floor($row['star_rating']);
                        $halfStar = ($row['star_rating'] - $fullStars >= 0.5) ? 1 : 0;
                        $emptyStars = 5 - $fullStars - $halfStar;
                        $tooltipText = $row['star_rating'] . "/5, " . $row['review_count'] . " valutazioni lasciate";

                        echo '<li>';
                        echo '<div class="course-title">';
                        echo '<h4>' . htmlspecialchars($row['nome']) . '</h4>';
                        echo '<div class="star-rating" data-bs-toggle="tooltip" title="' . $tooltipText . '">';
                        for ($i = 0; $i < $fullStars; $i++) {
                            echo '<span class="star full-star">&#9733;</span>';
                        }
                        for ($i = 0; $i < $halfStar; $i++) {
                            echo '<span class="star half-star">&#9733;</span>';
                        }
                        for ($i = 0; $i < $emptyStars; $i++) {
                            echo '<span class="star empty-star">&#9733;</span>';
                        }
                        echo '</div>';
                        echo '</div>';
                        echo '<p>' . htmlspecialchars(getCorrectDescription($row['descrizione'])) . '</p>';
                        echo '</li>';
                    }
                    echo '</ul>';
                }
            } else {
                echo "Connessione al database non riuscita";
            }
        ?>
        <div style="text-align: center;">
            <a type="button" id="more_info" class="btn btn-primary" href="/app/listacorsi.php">Più informazioni</a>
        </div>
        
    </div>

</body>
</html>