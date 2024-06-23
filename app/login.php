<?php 
    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
    or die('Could not connect: ' . pg_last_error());

    if ($dbconn) {
        session_start();

        //echo 'connessione effettuata';
        $email = $_POST['emaillog'];
        $password = $_POST['passwordlog'];
        $password = hash('sha256', $password);

        //echo $email . ' ' . $password;

        $q1="select * from utente where email=$1 and password=$2";
        $result=pg_query_params($dbconn, $q1, array($email, $password));
        
        if ($tuple=pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $_SESSION['email'] = $email;
            $_SESSION['ruolo'] = $tuple['ruolo'];
            $_SESSION['nome'] = $tuple['nome'];

            header('Location: /app/home.php'); 
            exit;
        }
        else {
            //echo 'Login non riuscito';
            $_SESSION['login_non_riuscito'] = true;
            header('Location: /app/formlogin.php'); 
            exit;
        }
    }
    else {
        echo "Connessione al database non riuscita";
    }
?>