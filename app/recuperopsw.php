<?php 
    include_once './Utente.class.php'; 
    
    session_start();

    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
        or die('Could not connect: ' . pg_last_error());

    if ($dbconn) {
        echo "Connessione effettuata<br>";

        $email = $_POST['emailpsw'];

        $check_q = "SELECT email from utente where email = $1";
        $check_result = pg_query_params($dbconn, $check_q, array($email));

        if (pg_num_rows($check_result) == 0) {
            $_SESSION['email_non_trovata'] = true;
            header('Location: /app/formpsw.php');
        } 
        $tuple = pg_fetch_assoc($check_result);
        $nome = $tuple['nome'];

        $token = bin2hex(random_bytes(20));
        $scadenza = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $q = "UPDATE utente set token=$1, scadenza=$2 where email=$3";
        $result = pg_query_params($dbconn, $q, array($token, $scadenza, $email));

        $link = "http://localhost:3000/app/formresetpsw.php?token=$token";

        $to_email = "$email";
        $subject = 'Reset password';
                            
        $message = file_get_contents('email_psw.html');
        $message = str_replace("[nome]", $nome, $message);
        $message = str_replace("[link]", $link, $message);

        $headers = 'From: info.gymgenius@libero.it' . "\r\n" .
                    'Content-type: text/html; charset=utf-8' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                            
        $mail_sent = mail($to_email, $subject, $message, $headers);

        if ($mail_sent) {
            echo "Email inviata con successo a $email";
        } else {
            echo "Errore nell'invio dell'email a $email";
        }
        $_SESSION['richiesta_recuperopsw'] = true;
        header('Location: /app/formlogin.php');
    }
    else {
        echo "Connessione al database non riuscita";
    }

?>