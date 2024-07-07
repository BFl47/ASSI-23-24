<?php
// comunica con corsi_preferiti.php
session_start();

if (!isset($_SESSION['id_log'])) {
    echo json_encode(['success' => false, 'message' => 'Devi essere loggato per partecipare a una lezione.']);
    exit;
}

$user_id = $_SESSION['id_log'];
$lezione_id = $_POST['lezione_id'];

$dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
    or die('Could not connect: ' . pg_last_error());

if ($dbconn) {
    // query: verifica se utente è già iscritto alla lezione
    $q_check = "SELECT 1 FROM partecipa WHERE id_lezione = $1 AND id_user = $2";
    $result_check = pg_query_params($dbconn, $q_check, array($lezione_id, $user_id));

    if (pg_num_rows($result_check) > 0) {
        echo json_encode(['success' => false, 'message' => 'Lezione già prenotata.']);
    } else {
        // query: inserisce l'utente come partecipante della lezione
        $q_insert = "INSERT INTO partecipa (id_lezione, id_user) VALUES ($1, $2)";
        $result_insert = pg_query_params($dbconn, $q_insert, array($lezione_id, $user_id));

        if ($result_insert) {
            echo json_encode(['success' => true, 'message' => 'Lezione prenotata con successo.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Errore durante la prenotazione della lezione.']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Errore nella connessione al database.']);
}
?>
