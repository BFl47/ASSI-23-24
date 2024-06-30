<?php
    require_once 'config.php'; 
    include_once 'GoogleCalendarApi.class.php'; 

    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
        or die('Could not connect: ' . pg_last_error());

    $event_id = '66810c39b3bca';

    $q = "SELECT * from corso where id=$1";
    $result = pg_query_params($dbconn, $q, array($event_id));
    $tuple = pg_fetch_array($result, null, PGSQL_ASSOC);
    $event_id_google = $tuple['google_calendar_event_id'];

    // Get event info 
    $nome = $_POST['nomeeventoed']; 
    $descrizione = $_POST['descrizioneeventoed']; 
    $luogo = $_POST['luogoeventoed']; 
    $data = $_POST['dataeventoed']; 
    $time_from = $_POST['time_fromed']; 
    $time_to = $_POST['time_toed']; 

    $q = "UPDATE corso set data=$1 where id=$2";               
    $data = pg_query_params($dbconn, $q, array($data, $event_id));

    $_SESSION['last_event_id'] = $event_id; 
    $_SESSION['editEvent'] = true;
    
    header("Location: $googleOauthURL"); 
    exit();
?>