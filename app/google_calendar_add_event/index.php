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
                <input type="text" name="luogoevento" class="form-control">
            </div>
            <div class="form-group">
                <label for="trainerevento">Trainer</label>
                <select name="trainerevento" class="form-control">
                    <option value=""></option>
                    <?php
                         while ($tuple = pg_fetch_assoc($result)) {
                            echo '<option value="' . $tuple['id'] . '">' . $tuple['id'] . ' - ' . $tuple['nome'] . '</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="dataevento">Data inizio</label>
                <input type="date" name="dataevento" class="form-control" required>
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

