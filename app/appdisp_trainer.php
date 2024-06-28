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
    #nome_prenotato{
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
        echo "<h3>".$_SESSION['nome'].", ecco le tue disponibilità con appuntamenti prenotati:</h3><br>";
    ?>

<table>

<?php

     $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
     or die('Could not connect: ' . pg_last_error());

     if ($dbconn) {
        $id=$_SESSION['id_log'];
        $query = "SELECT * FROM disp LEFT JOIN appuntamento ON disp.id_trainer=appuntamento.id_trainer AND disp.data_d = appuntamento.data_a LEFT JOIN utente ON appuntamento.id_user=utente.id WHERE disp.id_trainer=$1";
        $result = pg_query_params($dbconn, $query, array($id));
        if (pg_num_rows($result) > 0) {

            echo"<thead>
                        <tr>
                            
                            
                            <th width=250px align='center'>Data</th>  
                            <th width=250px align='center'>Prenotato</th>
                            <th width=250px align='center'>Contatta</th>
                            <th width=100px></th> 
                            <tr>
                                                        <td><br></td>
                                                    </tr>
                        </tr>
                    </thead>
                ";
            while ($row = pg_fetch_assoc($result)) {
                $emailAddress = $row["email"];
                $flag = $row["free"];
                $data = $row["data_d"];

                if($flag=="f"){
                    echo "<tr>
                        <form action='tcancella_disponibiltà.php' method='post'>
                            
                            <td><input type='text' readonly value=$data id='data_app' name='data_app'></td>
                            <td><input type='text' readonly value={$row['nome']} id='nome_prenotato' name='nome_prenotato'></td>
                            <td><a href='mailto:".$emailAddress."'>$emailAddress</a></td>
                            <td><button type='submit'>Cancella</button></td>
                            <td><input type='text' value={$row['id']} hidden id='id_user' name='id_user'></td>
                            <td><input type='text' value={$row['free']} hidden id='free_flag' name='free_flag'></td>
                        <form>
                    
                   ";
                }else if($flag=="t"){
                    echo "<tr>
                        <form action='tcancella_disponibiltà.php' method='post'>
                            
                            <td><input type='text' readonly value=$data id='data_app' name='data_app'></td>
                            <td></td>
                            <td></td>
                            <td><button type='submit'>Cancella</button></td>
                            <td><input type='text' value={$row['free']}  hidden id='free_flag' name='free_flag'></td>
                        </form>
                   ";
                    
                }
                
                
            }
                   
        } else {
            echo "<td>
                    NESSUNA DISPONIBILITÀ
                </td>";
                 
        }

    }else{
        echo "Connessione al database non riuscita";
    }
    
?>
</table>
</body>
</html>