<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymGenius</title>
    <style>
    #data_ad{
            border:none;
    }
    #nome_prenotato{
        border:none;
    }
    </style>
        <script>
        function valida_disp(event) {
            const dataInput = document.getElementById('data_nuovad').value;
            const dataPattern = /^\d{4}-\d{2}-\d{2}$/;

            if (!dataPattern.test(dataInput)) {
                alert("Inserisci una data valida nel formato AAAA-MM-GG.");
                event.preventDefault();
                return false;
            }

            const data = new Date(dataInput);
            const oggi = new Date();

            
            oggi.setHours(0, 0, 0, 0);

            if (isNaN(data.getTime()) || data < oggi) {
                alert("Inserisci una data non passata.");
                event.preventDefault();
                return false;
            }

            return true;
        }
    </script>
    <script>
        function chiediConferma(event) {
            var risposta = confirm("Stai per cancellare un appuntamento con un utente, procedere?");
            if (!risposta) {
                event.preventDefault();
            }
        }
    </script>
    <script>
        <?php
            session_start();

            if(isset($_SESSION['ins_disp'])){
                if($_SESSION['ins_disp']==true){
                    echo 'alert("Disponibilità inserita correttamente")';
                    $_SESSION['ins_disp']=false;
                }else{
                    echo 'alert("Hai già inserito una disponibilità in questa data")';
                }
                unset( $_SESSION['ins_disp']);
            }
            
            if (isset($_SESSION['appuntamento_rimosso'])) {
                if($_SESSION['appuntamento_rimosso']==true){
                    echo 'alert("Appuntamento rimosso!")';
                    $_SESSION['appuntamento_rimosso']=false;
                }else{
                    echo 'alert("Attenzione! Non hai un appuntamento prenotato con queste caratteristiche")';
                }
                unset( $_SESSION['appuntamento_rimosso']);
            }else if (isset($_SESSION['disp_rimossa'])) {
                if($_SESSION['disp_rimossa']==true){
                    echo 'alert("Disponibilità rimossa!")';
                    $_SESSION['disp_rimossa']=false;
                }else{
                    echo 'alert("Attenzione! Non hai un appuntamento prenotato con queste caratteristiche")';
                }
                unset( $_SESSION['disp_rimossa']);
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
                        <form action='tcancella_appdisp.php' method='post' onsubmit='chiediConferma(event)'>
                            
                            <td><input type='text' readonly value=$data id='data_ad' name='data_ad'></td>
                            <td><input type='text' readonly value={$row['nome']} id='nome_prenotato' name='nome_prenotato'></td>
                            <td><a href='mailto:".$emailAddress."'>$emailAddress</a></td>
                            <td><button type='submit'>Cancella appuntamento</button></td>
                            <td><input type='text' value={$row['id']} hidden id='id_user' name='id_user'></td>
                            <td><input type='text' value={$row['free']} hidden id='free_flag' name='free_flag'></td>
                            
                        </form>
                    
                   ";
                   echo "<tr>
                                            <td><br></td>
                                         </tr>";
                }else if($flag=="t"){
                    echo "<tr>
                        <form action='tcancella_appdisp.php' method='post'>
                            
                            <td><input type='text' readonly value=$data id='data_ad' name='data_ad'></td>
                            <td></td>
                            <td></td>
                            <td><button type='submit'>Cancella disponibilità</button></td>
                            <td><input type='text' value={$row['free']}  hidden id='free_flag' name='free_flag'></td>
                        </form>
                   ";
                   echo "<tr>
                                            <td><br></td>
                                         </tr>";
                    
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
<br>
<br>
Da qui puoi aggiungere disponibilità
<form action="taggiungi_disp.php" method="POST" onsubmit="valida_disp(event)">
    <tr><input type="date" id="data_nuovad" name="data_nuovad"></tr>
    <td><button type='submit'>Aggiungi disponibilità</button></td>
</form>
</body>
</html>