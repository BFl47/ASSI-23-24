<?php 
// Include Google calendar api handler class 
include_once 'GoogleCalendarApi.class.php'; 
require_once 'config.php'; 
     
$dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
    or die('Could not connect: ' . pg_last_error());

session_start();

$statusMsg = ''; 
$status = 'danger'; 
if(isset($_GET['code'])){ 
    // Initialize Google Calendar API class 
    $GoogleCalendarApi = new GoogleCalendarApi(); 
    echo "creata classe GoogleCalendarApi <br>";
     
    // Get event ID from session 
    $event_id = $_SESSION['last_event_id']; 
    echo "event_id $event_id <br>";
 
    if(!empty($event_id)){ 

        $q1="SELECT * from corso where id=$1";
        $result=pg_query_params($dbconn, $q1, array($event_id));
        
        if ($tuple=pg_fetch_array($result, null, PGSQL_ASSOC)) {
        
            $calendar_event = array( 
                'summary' => $tuple['nome'], 
                'location' => $tuple['luogo'], 
                'description' => $tuple['descrizione'] 
            ); 
             
            $event_datetime = array( 
                'event_date' => $tuple['data'], 
                'start_time' => $tuple['time_from'], 
                'end_time' => $tuple['time_to'] 
            ); 
            
            print_r($calendar_event);
            // Get the access token 
            if(!empty($_SESSION['google_access_token'])){ 
                $access_token = $_SESSION['google_access_token']; 
            }else{ 
                $data = $GoogleCalendarApi->GetAccessToken(GOOGLE_CLIENT_ID, REDIRECT_URI, GOOGLE_CLIENT_SECRET, $_GET['code']); 
                $access_token = $data['access_token']; 
                $_SESSION['google_access_token'] = $access_token; 
            } 
            echo "Access Token ottenuto <br>";

            if(!empty($access_token)){ 
                try { 
                    // Get the user's calendar timezone 
                    //$user_timezone = $GoogleCalendarApi->GetUserCalendarTimezone($access_token); 
                    $user_timezone = 'Europe/Rome';
                 
                    // Create an event on the primary calendar 
                    //$calendar_id = 'primary';
                    $calendar_id = 'c_1f9546d9b401e2377cd81a011d90db4d01136ff427891fee6bd7c19181c43709@group.calendar.google.com';
                    echo "prima della creazione<br>";
                    $google_event_id = $GoogleCalendarApi->CreateCalendarEvent($access_token, $calendar_id, $calendar_event, 0, $event_datetime, $user_timezone); 
                    echo "dopo la creazione<br>";
                    echo "Google Event ID $google_event_id<br>";

                    if($google_event_id){ 
                        // Update google event reference in the database 
                        $q="UPDATE corso set google_calendar_event_id=$1 where id=$2";
                        $result=pg_query_params($dbconn, $q, array($google_event_id, $event_id));
                        
                        unset($_SESSION['last_event_id']); 
                        unset($_SESSION['google_access_token']); 
                         
                        $status = 'success'; 
                        $statusMsg = '<p>Event #'.$event_id.' has been added to Google Calendar successfully!</p>'; 
                        $statusMsg .= '<p><a href="https://calendar.google.com/calendar/" target="_blank">Open Calendar</a>'; 
                    } 
                } catch(Exception $e) { 
                    //header('Bad Request', true, 400); 
                    //echo json_encode(array( 'error' => 1, 'message' => $e->getMessage() )); 
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
     
    $_SESSION['status_response'] = array('status' => $status, 'status_msg' => $statusMsg); 
    print_r($_SESSION['status_response']);
    //header("Location: index.php"); 
    exit(); 
} 
?>