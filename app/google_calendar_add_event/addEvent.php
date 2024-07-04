<?php     
    require_once 'config.php'; 

    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
        or die('Could not connect: ' . pg_last_error());

    session_start();

    if(isset($_POST['submit'])){ 

        $nome = $_POST['nomeevento']; 
        $descrizione = $_POST['descrizioneevento'] . '<br>Trainer: ' . $_POST['trainerevento']; 
        $luogo = $_POST['luogoevento']; 
        $trainer = $_POST['trainerevento'];
        $data = $_POST['dataevento']; 
        $time_from = $_POST['time_from']; 
        $time_to = $_POST['time_to']; 
        $occorrenze = $_POST['terminadopo'];

        $giorni = [];
        if (isset($_POST['days'])) {
            $giorni = $_POST['days'];
        }

        $rrule = "RRULE:FREQ=WEEKLY;BYDAY=" . implode(',', $giorni) . ";COUNT=" . intval($occorrenze);

        $event_id = uniqid();
        $q = "INSERT into corso values ($1,$2,$3, $4,$5, $6,$7, $8, $9)";               
        $data = pg_query_params($dbconn, $q, array($event_id, $nome, $descrizione, $luogo, $data, $time_from, $time_to, $trainer, $rrule));
  
        $_SESSION['last_event_id'] = $event_id; 
                
        header("Location: $googleOauthURL"); 
        exit(); 
            
    }
    header("Location: index.php"); 
    exit(); 
?>