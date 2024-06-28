<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymGenius</title>

    <style>
    #data_app{
            border:none;
    }
    
    </style>
    <script>
        <?php
            session_start();
            if (isset($_SESSION['appuntamento_rimosso'])) {
                if($_SESSION['appuntamento_rimosso']==true){
                    echo 'alert("Appuntamento rimosso!")';
                    $_SESSION['appuntamento_rimosso']=false;
                }else{
                    echo 'alert("Attenzione! Non hai un appuntamento prenotato con queste caratteristiche")';
                }
                unset( $_SESSION['appuntamento_rimosso']);
            } 
        ?>
    </script>
</head>
<body>
<?php 
        session_start();
        if (isset($_SESSION['ruolo'])) {
            include 'templatelog.html';
        }
        else {
            include 'template.html'; 
        }
        echo "<h3>".$_SESSION['nome'].", ecco i tuoi appuntamenti:</h3><br>";
    ?>

<table>

<?php

     $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
     or die('Could not connect: ' . pg_last_error());

     if ($dbconn) {
        $id=$_SESSION['id_log'];
        $query = "select * from appuntamento,utente where id_user=$1 and appuntamento.id_trainer=utente.id";
        $result = pg_query_params($dbconn, $query, array($id));
        if (pg_num_rows($result) > 0) {

            echo"<thead>
                        <tr>
                            <th width=250px align='center'>Trainer</th>
                            <th width=250px align='center'>Email</th>
                            <th width=250px align='center'>Data</th>   
                            <th width=100px></th> 
                            <tr>
                                                        <td><br></td>
                                                    </tr>
                        </tr>
                    </thead>
                ";
            while ($row = pg_fetch_assoc($result)) {

                $emailAddress = $row["email"];
                
                
                echo "<tr>
                        <form action='ucancella_appuntamento.php' method='post'>
                            
                            <td>{$row['nome']}</td>
                            <td><a href='mailto:".$emailAddress."'>$emailAddress</a></td>
                            <td><input type='text' readonly value={$row['data_a']} id='data_app' name='data_app'></td>
                            <td><button type='submit'>Cancella</button></td>
                            <td><input type='text' value={$row['id_trainer']} hidden id='id_trainer' name='id_trainer'></td>
                        </form>
                    
                   ";
            }
                   
        } else {
            echo "<td>
                    NESSUN APPUNTAMENTO
                </td>";
                 
        }

    }else{
        echo "Connessione al database non riuscita";
    }
    
?>
</table>
</body>
</html>