<?php
    session_start();

    echo "connessione effettuata";
    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
        or die('Could not connect: ' . pg_last_error());

    if ($dbconn) {
        $token = $_POST['token'];
        echo "Token: $token";

        $check_q = "SELECT email, scadenza from utente where token = $1";
        $check_result = pg_query_params($dbconn, $check_q, array($token));

        $row = pg_fetch_assoc($check_result);
        $scadenza = $row['scadenza'];
        $now = date("Y-m-d H:i:s");

        if ($now > $scadenza) {
            echo "Il token è scaduto. Per favore richiedi un nuovo link per il reset della password.";
            exit(); 
        }

        $new_password = $_POST['newpassword'];
        $new_password = hash('sha256', $new_password);

        $q = "UPDATE utente set password = $1 where token = $2";
        $result = pg_query_params($dbconn, $q, array($new_password, $token));

        if ($result) {
            $_SESSION['password_reset'] = true;

            $email = $row['email'];
            $delete_q = "UPDATE utente set token = NULL, scadenza = NULL where email = $1";
            $delete_result = pg_query_params($dbconn, $delete_q, array($email));

            header('Location: /app/formlogin.php');
            exit();
        } else {
            echo "Errore durante il reset della password.";
        }
    } else {
        echo "Connessione al database non riuscita";
    }
?>
