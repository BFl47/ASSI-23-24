<?php
// comunica con corsi_preferiti.php
session_start();
if (!isset($_SESSION['id_log'])) {
    echo json_encode(['success' => false, 'message' => 'Devi essere loggato per lasciare una valutazione.']);
    exit;
}

$user_id = $_SESSION['id_log'];
$corso_id = $_POST['corso_id'];
$rating = $_POST['rating'];

$dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
    or die('Could not connect: ' . pg_last_error());

if ($dbconn) {
    $q = "
        UPDATE preferito 
        SET rating = $1 
        WHERE id_corso = $2 AND id_user = $3
    ";
    $result = pg_query_params($dbconn, $q, array($rating, $corso_id, $user_id));

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Errore nell\'aggiornamento della valutazione.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Errore nella connessione al database.']);
}
?>
