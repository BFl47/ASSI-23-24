<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuovo Corso</title>

    <style>
        .formaddEvent {
            margin: 0 auto;
            text-align: center;
            width: 40em;
            padding: 1em;
        }
        .form-group {
            margin: 0 auto;
            width: 30em;
            margin-bottom: 1em;
        }
        .time {
            display: flex;
            justify-content: space-between;
        }
        .form-control-time {
            width: 10em !important;
        }
        .start-repeat {
            margin-left: 4em !important;
        }
        
    </style>
</head>
<body>

    <?php 
        session_start();
        if (isset($_SESSION['ruolo']) && $_SESSION['ruolo'] == 'Admin') {
            include '../templatelog.html';
        }
        else {
            header("Location: ../home.php");
        }

        $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
                    or die('Could not connect: ' . pg_last_error());
        if ($dbconn) {
            $q = "SELECT * from utente where ruolo = 'Trainer'";
            $result = pg_query($dbconn, $q);
        }
    ?>
    <div class="formaddEvent border">
        <form method="post" action="addEvent.php" class="form">
            <div class="form-group">
                <label for="nomeevento">Nome Corso</label>
                <input type="text" class="form-control" name="nomeevento" required>
            </div>
            <div class="form-group">
                <label for="descrizioneevento">Descrizione</label>
                <textarea name="descrizioneevento" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="luogoevento">Luogo</label>
                <input type="text" name="luogoevento" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="trainerevento">Trainer</label>
                <select name="trainerevento" class="form-control" required>
                    <option value=""></option>
                    <?php
                         while ($tuple = pg_fetch_assoc($result)) {
                            echo '<option value="' . $tuple['id'] . '">' . $tuple['id'] . ' - ' . $tuple['nome'] . '</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="form-group start-repeat row">
                <div class="col">
                    <label for="dataevento">Data inizio</label>
                    <input type="date" name="dataevento" class="form-control" required>
                </div>
                
                <div class="col">
                    <label for="terminadopo">Numero occorrenze</label>
                    <input type="number" name="terminadopo" class="form-control" min="1" max="24" required>
                </div>
            </div>
            Giorni
            <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="MO" name="days[]" value="MO">
                    <label class="form-check-label" for="MO">L</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="TU" name="days[]" value="TU">
                    <label class="form-check-label" for="TU">Ma</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="WE" name="days[]" value="WE">
                    <label class="form-check-label" for="WE">Me</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="TH" name="days[]" value="TH">
                    <label class="form-check-label" for="TH">G</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="FR" name="days[]" value="FR">
                    <label class="form-check-label" for="FR">V</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="SA" name="days[]" value="SA">
                    <label class="form-check-label" for="SA">S</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="SU" name="days[]" value="SU">
                    <label class="form-check-label" for="SU">D</label>
                </div>
            </div>

            <div class="form-group time">
                <label for="time_from">Da:</label>
                <input type="time" name="time_from" class="form-control form-control-time">
                &nbsp;
                <label for="time_to">A:</label>
                <input type="time" name="time_to" class="form-control form-control-time">
            </div>
            
            <div class="form-group">
                <input type="submit" class="form-control btn btn-primary" name="submit" value="Aggiungi corso"/>
            </div>
        </form>
        
    </div>
</body>
</html>

