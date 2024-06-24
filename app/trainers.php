<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainers</title>
</head>
<body>
<?php
    
    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
    or die('Could not connect: ' . pg_last_error());

    if ($dbconn) {
        session_start();
        include 'templatelog.html'; 
        $ruolo = 'Trainer';
        $query = "SELECT * FROM utente WHERE ruolo = $1";
        $result = pg_query_params($dbconn, $query, array($ruolo));
        
        
        // Verifica se ci sono risultati
        if (pg_num_rows($result) > 0) {
            // Iterazione sui risultati della query
            while ($row = pg_fetch_assoc($result)) {
                echo "ID: " . $row['id'] . ", Nome: " . $row['nome'] . ", Ruolo: " . $row['ruolo'] . ", Email: ". $row['email']."<br>";
            }
        } else {
            echo "Nessun risultato trovato.";
        }

        // Chiusura della connessione
        pg_close($db);
    }else {
        echo "Connessione al database non riuscita";
    }
?>
    
</body>
</html>