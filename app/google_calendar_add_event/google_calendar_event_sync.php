<?php 
// Include Google calendar api handler class 
include_once 'GoogleCalendarApi.class.php'; 
include_once '../Corso.class.php'; 
require_once 'config.php'; 
     
$dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
    or die('Could not connect: ' . pg_last_error());

session_start();

$statusMsg = ''; 
$status = 'danger'; 
if(isset($_GET['code'])){ 

    $GoogleCalendarApi = new GoogleCalendarApi(); 
    echo "creata classe GoogleCalendarApi <br>";

    $event_id = $_SESSION['last_event_id']; 
    echo "event_id $event_id <br>";

    if(!empty($event_id)){ 

        $q1="SELECT * from corso where id=$1";
        $result=pg_query_params($dbconn, $q1, array($event_id));
        
        if ($tuple=pg_fetch_array($result, null, PGSQL_ASSOC)) {
        
            $calendar_event = array( 
                'summary' => $tuple['nome'], 
                'location' => $tuple['luogo'], 
                'description' => $tuple['descrizione'],
                'recurrence' => [
                    $tuple['rrule']
                ]
            ); 
             
            $event_datetime = array( 
                'event_date' => $tuple['data'], 
                'start_time' => $tuple['time_from'], 
                'end_time' => $tuple['time_to'] 
            ); 
            
            print_r($calendar_event);
            if(!empty($_SESSION['google_access_token'])){ 
                $access_token = $_SESSION['google_access_token']; 
            }else{ 
                $data = $GoogleCalendarApi->GetAccessToken(GOOGLE_CLIENT_ID, REDIRECT_URI, GOOGLE_CLIENT_SECRET, $_GET['code']); 
                $access_token = $data['access_token']; 
                $_SESSION['google_access_token'] = $access_token; 
            } 
            echo "Access Token ottenuto <br>";

            if(!empty($access_token)){ 
                $user_timezone = 'Europe/Rome';
                $calendar_id = 'c_1f9546d9b401e2377cd81a011d90db4d01136ff427891fee6bd7c19181c43709@group.calendar.google.com';

                try { 
                    if (isset($_SESSION['deleteEvent'])) {                          // cancella Evento
                    
                        $google_event_id = $tuple['google_calendar_event_id'];
                        echo "Prima dell'eliminazione<br>";
                        $GoogleCalendarApi->DeleteCalendarEvent($access_token, $calendar_id, $google_event_id);
                        echo "Dopo l'eliminazione<br>";
                        $q="DELETE FROM corso WHERE id=$1";
                        $result=pg_query_params($dbconn, $q, array($event_id));

                        unset($_SESSION['last_event_id']); 
                        unset($_SESSION['google_access_token']); 
                        unset($_SESSION['deleteEvent']);

                        $status = 'success'; 

                        $_SESSION['corsoeliminato'] = true;
                        header("Location: ../listacorsi.php");
                        exit();

                        // $statusMsg = '<p>Event #'.$event_id.' has been deleted from Google Calendar successfully!</p>'; 
                        // $statusMsg .= '<p><a href="https://calendar.google.com/calendar/" target="_blank">Open Calendar</a>'; 
                    }
                    else {                                                        // aggiungi Evento
                        echo "prima della creazione<br>";
                        $google_event_id = $GoogleCalendarApi->CreateCalendarEvent($access_token, $calendar_id, $calendar_event, 0, $event_datetime, $user_timezone); 
                        echo "dopo la creazione<br>";
                        echo "Google Event ID $google_event_id<br>";

                        if($google_event_id){ 
                            $q="UPDATE corso set google_calendar_event_id=$1 where id=$2";
                            $result=pg_query_params($dbconn, $q, array($google_event_id, $event_id));
                            
                            unset($_SESSION['last_event_id']); 
                            unset($_SESSION['google_access_token']); 
                            
                            $status = 'success'; 
                            $statusMsg = '<p>Event #'.$event_id.' has been added to Google Calendar successfully!</p>'; 
                            $statusMsg .= '<p><a href="https://calendar.google.com/calendar/" target="_blank">Open Calendar</a>'; 

                            $_SESSION['corsocreato'] = true;

                            header("Location: ../listacorsi.php");
                            exit();
                        } 
                    }
                } catch(Exception $e) { 
                    $statusMsg = $e->getMessage(); 
                } 
            }else{ 
                $statusMsg = 'Failed to fetch access token!'; 
            } 
        }else{ 
            $statusMsg = 'Event data not found!'; 
        } 
    }else{ 
        $statusMsg = 'Event reference not found!'; 
    } 

    //$array_id = $_SESSION['course_ids'];

    if (isset($_SESSION['course_ids']) && !empty($_SESSION['course_ids'])) {
        $array_id = $_SESSION['course_ids'];
    } else {
        $array_id = array();
    }
    
    if (isset($_SESSION['eliminaTrainer']) && !empty($array_id)) {
        echo 'eliminazione trainer';
        $data = $GoogleCalendarApi->GetAccessToken(GOOGLE_CLIENT_ID, REDIRECT_URI, GOOGLE_CLIENT_SECRET, $_GET['code']); 
        $access_token = $data['access_token']; 
        $_SESSION['google_access_token'] = $access_token; 

        if(!empty($access_token)){ 
            $user_timezone = 'Europe/Rome';
            $calendar_id = 'c_1f9546d9b401e2377cd81a011d90db4d01136ff427891fee6bd7c19181c43709@group.calendar.google.com';

            foreach ($array_id as $id) {
                $GoogleCalendarApi->DeleteCalendarEvent($access_token, $calendar_id, $id);

                $q="DELETE FROM corso WHERE google_calendar_event_id=$1";
                $result=pg_query_params($dbconn, $q, array($event_id));
            } 
        }
        unset($_SESSION['eliminaTrainer']);
        unset($_SESSION['course_ids']);

        $_SESSION['trainereliminato'] = true;
        echo 'trainer eliminato';
        header("Location: ../trainers.php");
        exit();
    }
     
    $_SESSION['status_response'] = array('status' => $status, 'status_msg' => $statusMsg); 
    //print_r($_SESSION['status_response']);

    //header("Location: index.php"); 
    exit(); 
} 
?>