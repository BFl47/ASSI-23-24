<?php
    require_once './google_calendar_add_event/config.php';
    include_once './google_calendar_add_event/GoogleCalendarApi.class.php'; 
    session_start();
    $id_trainer = $_GET['id'];
    //echo $id_trainer;

    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
    or die('Could not connect: ' . pg_last_error());
    if ($dbconn) {

        $q1 = "SELECT * FROM corso WHERE trainer = $1";
        $result1 = pg_query_params($dbconn, $q1, array($id_trainer));
        if ($result1) {
            $rows = pg_fetch_all($result1);
            if ($rows) {
                $course_ids = array_column($rows, 'google_calendar_event_id'); 
                //print_r($course_ids);
            }
        }

        $q = "DELETE FROM utente WHERE id = $1";
        $result = pg_query_params($dbconn, $q, array($id_trainer));

        
        $_SESSION['eliminaTrainer'] = true;

        if ($course_ids) {
            $_SESSION['course_ids'] = $course_ids;
            header("Location: $googleOauthURL");
        }
        else {
            header('Location: /app/trainers.php');
        }
        exit();
    }
?>