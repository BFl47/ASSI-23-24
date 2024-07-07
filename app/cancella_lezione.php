<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password")
        or die('Could not connect: ' . pg_last_error());

    $lezione_id = $_POST['lezione_id'] ?? null;
    $trainer_id = $_SESSION['id_log'] ?? null;

    if ($dbconn && $lezione_id && $trainer_id) {

        $q = "SELECT id_corso, data, time_from, time_to FROM lezione WHERE id = $1";
        $result = pg_query_params($dbconn, $q, array($lezione_id));
        $lezione = pg_fetch_assoc($result);
        
        if ($lezione) {
            // query: nome del corso
            $q_corso = "SELECT nome FROM corso WHERE id = $1";
            $result_corso = pg_query_params($dbconn, $q_corso, array($lezione['id_corso']));
            $corso = pg_fetch_assoc($result_corso);

            // query: utenti iscritti alla lezione
            $q_users = "
                SELECT u.email
                FROM partecipa p
                INNER JOIN utente u ON p.id_user = u.id
                WHERE p.id_lezione = $1
            ";
            $result_users = pg_query_params($dbconn, $q_users, array($lezione_id));
            $emails = [];
            while ($row = pg_fetch_assoc($result_users)) {
                $emails[] = $row['email'];
            }

            // cancella
            $q_delete = "DELETE FROM lezione WHERE id = $1";
            $result_delete = pg_query_params($dbconn, $q_delete, array($lezione_id));

            if ($result_delete) {
                // notifica mediante mail
                $subject = "Lezione cancellata";
                $message = "La lezione del " . $lezione['data'] . " ore " . $lezione['time_from'] . "-" . $lezione['time_to'] . " del corso " . $corso['nome'] . " è stata annullata.";
                foreach ($emails as $email) {
                    mail($email, $subject, $message);
                }
                echo json_encode(['success' => true, 'message' => 'Lezione cancellata con successo.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Errore nella cancellazione della lezione.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Lezione non trovata.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Parametri mancanti.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metodo non consentito.']);
}
?>