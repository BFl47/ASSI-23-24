<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index prova</title>
</head>
<body>
<div class="col-md-12">
    <form method="post" action="addEvent.php" class="form">
        <div class="form-group">
            <label>Nome Evento</label>
            <input type="text" class="form-control" name="nomeevento" required>
        </div>
        <div class="form-group">
            <label>Descrizione Evento</label>
            <textarea name="descrizioneevento" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label>Luogo</label>
            <input type="text" name="luogoevento" class="form-control">
        </div>
        <div class="form-group">
            <label>Data</label>
            <input type="date" name="dataevento" class="form-control" required>
        </div>
        <div class="form-group time">
            <label>Time</label>
            <input type="time" name="time_from" class="form-control">
            <span>TO</span>
            <input type="time" name="time_to" class="form-control">
        </div>
        <div class="form-group">
            <input type="submit" class="form-control btn-primary" name="submit" value="Add Event"/>
        </div>
    </form>
    <br>
    Cancella evento: <a href="deleteEvent.php">Cancella</a>
    <br>
    <br>
    <form method="post" action="editEvent.php" class="form">
        id prova = 66810c39b3bca
        <div class="form-group">
            <label>Nome Evento</label>
            <input type="text" class="form-control" name="nomeeventoed" required>
        </div>
        <div class="form-group">
            <label>Descrizione Evento</label>
            <textarea name="descrizioneeventoed" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label>Luogo</label>
            <input type="text" name="luogoeventoed" class="form-control">
        </div>
        <div class="form-group">
            <label>Data</label>
            <input type="date" name="dataeventoed" class="form-control" required>
        </div>
        <div class="form-group time">
            <label>Time</label>
            <input type="time" name="time_fromed" class="form-control">
            <span>TO</span>
            <input type="time" name="time_toed" class="form-control">
        </div>
        <div class="form-group">
            <input type="submit" class="form-control btn-primary" name="submit" value="Edit Event"/>
        </div>
    </form>
</div>
</body>
</html>

