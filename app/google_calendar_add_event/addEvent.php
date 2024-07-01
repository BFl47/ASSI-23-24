<?php     
    require_once 'config.php'; 

    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
        or die('Could not connect: ' . pg_last_error());

    session_start();

    if(isset($_POST['submit'])){ 

        $nome = $_POST['nomeevento']; 
        $descrizione = $_POST['descrizioneevento']; 
        $luogo = $_POST['luogoevento']; 
        $data = $_POST['dataevento']; 
        $time_from = $_POST['time_from']; 
        $time_to = $_POST['time_to']; 

        $event_id = uniqid();
        $q = "INSERT into corso values ($1,$2,$3, $4,$5, $6,$7)";               
        $data = pg_query_params($dbconn, $q, array($event_id, $nome, $descrizione, $luogo, $data, $time_from, $time_to));
  
        $_SESSION['last_event_id'] = $event_id; 
                
        header("Location: $googleOauthURL"); 
        exit(); 
            
    }
    header("Location: index.php"); 
    exit(); 
?>