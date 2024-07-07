<?php
session_start();

if (!isset($_SESSION['id_log'])) {
    echo json_encode(['success' => false, 'message' => 'Devi essere loggato per cancellare la partecipazione a una lezione.']);
    exit;
}

$user_id = $_SESSION['id_log'];
$lezione_id = $_POST['lezione_id'];

$dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
    or die('Could not connect: ' . pg_last_error());

if ($dbconn) {
    // query: utente iscritto alla lezione
    $q_check = "SELECT 1 FROM partecipa WHERE id_lezione = $1 AND id_user = $2";
    $result_check = pg_query_params($dbconn, $q_check, array($lezione_id, $user_id));

    if (pg_num_rows($result_check) > 0) {
        // cancella partecipazione
        $q_delete = "DELETE FROM partecipa WHERE id_lezione = $1 AND id_user = $2";
        $result_delete = pg_query_params($dbconn, $q_delete, array($lezione_id, $user_id));

        if ($result_delete) {
            echo json_encode(['success' => true, 'message' => 'Partecipazione alla lezione cancellata con successo.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Errore durante la cancellazione della partecipazione alla lezione.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Non sei un partecipante della lezione.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Errore nella connessione al database.']);
}
?>
