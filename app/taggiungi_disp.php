<?php 
    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
    or die('Could not connect: ' . pg_last_error());

    if ($dbconn) {
        session_start();
        $trainer=$_SESSION['id_log'];
        $data=$_POST['data_nuovad'];
        $flag="true";

        $q0="select * from disp where id_trainer=$1 and data_d=$2";
        $result0=pg_query_params($dbconn, $q0, array($trainer,$data));
        
        if ($tuple0=pg_fetch_array($result0, null, PGSQL_ASSOC)) {
            $_SESSION['ins_disp']= false;
            header('Location: /app/appdisp_trainer.php'); 
            exit;
        }
        
        $q1 = "insert into disp values ($1,$2,$3)";
                        
        $result = pg_query_params($dbconn, $q1, array($trainer,$data,$flag));
        $_SESSION['ins_disp']= true;    
    } else {
        echo "Connessione al database non riuscita";
    }
    header('Location: /app/appdisp_trainer.php');
    exit;
?>