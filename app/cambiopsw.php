<?php
    session_start();

    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
        or die('Could not connect: ' . pg_last_error());

    if ($dbconn) {
        echo "Connessione effettuata";

        $email = $_SESSION['email'];
        $vecchiapassword = $_POST['vecchiapassword'];
        $vecchiapassword= hash('sha256', $vecchiapassword);

        $nuovapassword = $_POST['nuovapassword'];
        $nuovapassword = hash('sha256', $nuovapassword);

        $q = "update utente set password = $2 where email = $1 and password = $3";
        $result = pg_query_params($dbconn, $q, array($email, $nuovapassword, $vecchiapassword));

        if ($result) {
            $rows = pg_affected_rows($result);
            if ($rows > 0) {
                $_SESSION['cambio_psw_riuscito'] = true;
            } else {
                $_SESSION['cambio_psw_non_riuscito'] = true;
            }
        } else {
            echo "Errore nell'esecuzione della query: " . pg_last_error($dbconn);
        }

        header('Location: /app/profilo.php');
        exit;
    } 
    else {
        echo "Connessione al database non riuscita";
    }
?>
