<?php
    session_start();
    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
                or die('Could not connect: ' . pg_last_error());
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        //echo "connessione effettuata\n";
        $upload_path = "assets/";
        $email = $_SESSION['email'];

        //echo $upload_path . ' ';

        if (!is_writable($upload_path)) {
            echo "La cartella di destinazione non è scrivibile\n";
            exit;
        }

        if ($_FILES['imgProfilo']['name'] != '') {
            echo "settata post[imgProfilo]\n";
            $filename = basename($_FILES['imgProfilo']['name']);
            $target_file = $upload_path . $filename;
            
            echo $target_file . ' ';

            if (!move_uploaded_file($_FILES['imgProfilo']['tmp_name'], $target_file)) {
                echo "Upload fallito\n" ;
            } else {
                $img = "/app/" . $target_file;
                $q = "update utente set path_img=$2 where email=$1";
                $result = pg_query_params($dbconn, $q, array($email, $img));

                $_SESSION['path_img'] = $img;
                //echo $_SESSION['path_img'] . ' ';
                echo "Immagine caricata con successo\n";
            }
        }
        else {
            echo "Nessun file caricato\n";
        }
        header('Location: /app/profilo.php');
        exit;
    }

?>