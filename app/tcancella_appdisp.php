<?php 
    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
    or die('Could not connect: ' . pg_last_error());

    if ($dbconn) {
        session_start();

        $condizione=$_POST['free_flag'];
        $trainer=$_SESSION['id_log'];
        $data=$_POST['data_ad'];

        if($condizione=="f"){   //la disponibilità è associata ad un appuntamento
            $flag="true";
            $utente=$_POST['id_user'];
            $q1 = "delete from appuntamento where id_user=$1 and id_trainer=$2 and data_a=$3";
            $result1 = pg_query_params($dbconn, $q1, array($utente, $trainer, $data));
            if ($result1){
                $affected_rows = pg_affected_rows($result1);
                if ($affected_rows > 0) {
                    $_SESSION['appuntamento_rimosso'] = true;
                    $q2= "update disp set free = $1 WHERE id_trainer = $2 and data_d = $3";
                    $result2 = pg_query_params($dbconn, $q2, array($flag,$trainer,$data));
                } else {
                    $_SESSION['appuntamento_rimosso'] = false;
                }
            
            }else{
                echo "Errore nella query";
                
            }
            header('Location: /app/appdisp_trainer.php');
            exit;

        }else if($condizione=="t"){     //la disponibilità non è associata ad alcun appuntamento, rimozione libera
            
            $q2 = "delete from disp where id_trainer=$1 and data_d=$2";
            $result2 = pg_query_params($dbconn, $q2, array($trainer, $data));
            if($result2){
                $affected_rows=pg_affected_rows($result2);
                if ($affected_rows > 0) {
                    $_SESSION['disp_rimossa'] = true;
                } else {
                    $_SESSION['disp_rimossa'] = false;
                }

            }else{
                echo "Errore nella rimozione della disponibilità";
            }
            header('Location: /app/appdisp_trainer.php');
            exit;
        }
        
        

        
        
    }
    else {
        echo "Connessione al database non riuscita";
    }
?>