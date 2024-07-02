<?php
    require_once 'config.php'; 
    include_once 'GoogleCalendarApi.class.php'; 

    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
        or die('Could not connect: ' . pg_last_error());

    $event_id = $_POST['id'];

    $_SESSION['last_event_id'] = $event_id; 
    $_SESSION['deleteEvent'] = true;
    
    header("Location: $googleOauthURLdelete"); 
    exit();
?>