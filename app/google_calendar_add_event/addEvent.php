<?php     
    require_once 'config.php'; 
    include_once '../Corso.class.php'; 

    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
        or die('Could not connect: ' . pg_last_error());

    session_start();

    if(isset($_POST['submit'])){ 
        $trainer = $_POST['trainerevento'];

        $q0 = "SELECT * from utente where id = $1";
        $result0 = pg_query_params($dbconn, $q0, array($trainer));

        if ($tuple0 = pg_fetch_array($result0, null, PGSQL_ASSOC)) {
            $trainer_nome = $tuple0['nome'];
        }

        $nome = $_POST['nomeevento']; 
        $descrizione = $_POST['descrizioneevento'] . '<br>Trainer: ' . $trainer_nome; 
        $luogo = $_POST['luogoevento']; 
        
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
        

        $dayMap = ["MO" => "Lunedì", "TU" => "Martedì", "WE" => "Mercoledì", "TH" => "Giovedì", "FR" => "Venerdì", "SA" => "Sabato", "SU" => "Domenica"];
        $dayMapReverse = ["Lunedì" => "MO", "Martedì" => "TU", "Mercoledì" => "WE", "Giovedì" => "TH", "Venerdì" => "FR", "Sabato" => "SA", "Domenica" => "SU"];
        $dayMapEngToIta = ["Monday" => "Lunedì", "Tuesday" => "Martedì", "Wednesday" => "Mercoledì", "Thursday" => "Giovedì", "Friday" => "Venerdì", "Saturday" => "Sabato", "Sunday" => "Domenica"];
        $start = $_POST['dataevento'];

        if ($data) {
            // popola Lezione
            $date_inizio = new DateTime($start);
            $lezioni_inserite = 0;
            
            while ($lezioni_inserite < $occorrenze) {
                $giorno_settimana = $dayMapReverse[$dayMapEngToIta[$date_inizio->format('l')]];
                
                if (in_array($giorno_settimana, $giorni)) {
                    $q_lezione = "INSERT INTO lezione (id_corso, nome, data, giorno, luogo, time_from, time_to) VALUES ($1, $2, $3, $4, $5, $6, $7)";
                    $result_lezione = pg_query_params($dbconn, $q_lezione, array($event_id, $nome, $date_inizio->format('Y-m-d'), $dayMap[$giorno_settimana], $luogo, $time_from, $time_to));

                    if ($result_lezione) {
                        $lezioni_inserite++;
                    } else {
                        echo "Errore nell'inserimento della lezione: " . pg_last_error($dbconn);
                    }
                }
                $date_inizio->modify('+1 day');
            }
        }

        header("Location: $googleOauthURL"); 
        exit();
    
            
    }
    header("Location: index.php"); 
    exit(); 
?>