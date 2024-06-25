<?php 
    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
    or die('Could not connect: ' . pg_last_error());

    if ($dbconn) {
        session_start();

        //echo 'connessione effettuata';
        $id_utente= $_SESSION['id_log'];
        $id_trainer = $_POST['id_trainer'];
        
        $data = $_POST['data_app'];
        $flag='false';
        //echo $email . ' ' . $password;

        $q1 = "insert into appuntamento values ($1,$2,$3)";
        $result1 = pg_query_params($dbconn, $q1, array($id_utente, $id_trainer, $data));
        
        $q2= "update disp set free = $1 WHERE id = $2 and data = $3";
        $result2 = pg_query_params($dbconn, $q2, array($flag,$id_trainer,$data));
        header('Location: /app/home.php');
    }
    else {
        echo "Connessione al database non riuscita";
    }
?>