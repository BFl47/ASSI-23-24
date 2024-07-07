<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Genius</title>

    <style>
        .profcontainer {
            margin-left: 15em;
            margin-top: 5em;
            text-align: center;
            width: 30em;
            height: auto;
        }
       
        .infoprof {
            margin: 0 auto;
            width: 20em;
            margin-bottom: 1em;
        }
        .datiprof {
            margin-left: 1em;
            text-align: left;
            width: 30em;
        }
        #anteprimaImg {
            width: 10em;
            height: 10em;
            border-radius: 50%;
        } 
    </style>
</head>
<body>

<?php 
    $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
    or die('Could not connect: ' . pg_last_error());

    if ($dbconn) {
        session_start();

        include 'templatelog.html'; 
        
        $id_trainer = $_POST['id_trainer_profilo'];
        

        $q1="SELECT * FROM utente WHERE id=$1";
        $result = pg_query_params($dbconn, $q1, array($id_trainer));
        if (pg_num_rows($result) > 0){
            $row = pg_fetch_assoc($result);
            $img=$row['path_img'];
            $nome=$row['nome'];
            $email=$row['email'];
            $ruolo=$row['ruolo'];
            echo '

                        <div class="profcontainer"> 
                            <div class="infoprof">
                                <br>
                                <img src="'.$img.'" alt="Immagine profilo" name="anteprimaImg" id="anteprimaImg">  
                                <br>          
                                <br>
                                <div class="datiprof">
                                    Nome:'.$nome.'<br>
                                    Email:'.$email.'<br>
                                    Ruolo:'.$ruolo.'<br>
                                </div>
                            </div>
                        </div> 
            
            ';
           
        }else{
            echo "Errore nella query";
            
        }
        exit;
        
    }
    else {
        echo "Connessione al database non riuscita";
    }
?>

</body>
</html>
