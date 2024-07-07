<?php 
    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
    or die('Could not connect: ' . pg_last_error());

    if ($dbconn) {
        session_start();

        
        $id_utente= $_SESSION['id_log'];
        $id_trainer = $_POST['id_trainer'];
        $data = $_POST['data_app'];
        $flag='true';
        

        $q1 = "delete from appuntamento where id_user=$1 and id_trainer=$2 and data_a=$3";
        $result1 = pg_query_params($dbconn, $q1, array($id_utente, $id_trainer, $data));
        if ($result1){
            $affected_rows = pg_affected_rows($result1);
            if ($affected_rows > 0) {
                $_SESSION['appuntamento_rimosso'] = true;
                $q2= "update disp set free = $1 WHERE id_trainer = $2 and data_d = $3";
                $result2 = pg_query_params($dbconn, $q2, array($flag,$id_trainer,$data));
            } else {
                $_SESSION['appuntamento_rimosso'] = false;
            }
           
        }else{
            echo "Errore nella query";
            
        }
        header('Location: /app/appuntamenti_user.php');
        exit;
        
    }
    else {
        echo "Connessione al database non riuscita";
    }
?>