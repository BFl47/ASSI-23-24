<?php
    session_start();
    $id_trainer = $_GET['id'];
    //echo $id_trainer;

    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
    or die('Could not connect: ' . pg_last_error());
    if ($dbconn) {
        $q = "DELETE FROM utente WHERE id = $1";
        $result = pg_query_params($dbconn, $q, array($id_trainer));
        if ($result) {
            header('Location: /app/trainers.php');
            exit;
        }
        else {
            echo "Errore nell'eliminazione del trainer";
        }
    }
?>