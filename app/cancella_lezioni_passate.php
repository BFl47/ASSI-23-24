<?php
// comunica con lezioni_trainer.php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password")
        or die('Could not connect: ' . pg_last_error());
    
    $trainer_id = $_SESSION['id_log'] ?? null;

    if ($dbconn && $trainer_id) {
        // query: cancellare le lezioni passate specifiche del trainer loggato
        $q_delete = "
            DELETE FROM lezione
            USING corso
            WHERE lezione.id_corso = corso.id
            AND corso.trainer = $1
            AND (
                (lezione.data < CURRENT_DATE) OR 
                (lezione.data = CURRENT_DATE AND lezione.time_from < CURRENT_TIME)
            )
            RETURNING lezione.id
        ";
        $result_delete = pg_query_params($dbconn, $q_delete, array($trainer_id));
        $rows_deleted = pg_num_rows($result_delete);

        if ($rows_deleted > 0) {
            echo json_encode(['success' => true, 'message' => 'Lezioni passate cancellate con successo.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Non risultano lezioni passate.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Errore nella connessione al database.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metodo non consentito.']);
}
?>
