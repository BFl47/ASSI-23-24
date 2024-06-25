<?php
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        header("Location: /app/");
    }
    else {
        $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
                    or die('Could not connect: ' . pg_last_error());
    }
    if ($dbconn) {
        echo "connessione effettuata";
        session_start();
        $email = $_POST['email'];

        $q1="select * from utente where email=$1";
        $result=pg_query_params($dbconn, $q1, array($email));
        
        if ($tuple=pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $_SESSION['email_non_disponibile'] = true;
            header('Location: /app/formsignup.php'); 
            exit;
        }
        
        $id = uniqid();
        $nome = $_POST['nome'];
        $password = $_POST['password'];
        $password = hash('sha256', $password);
        $ruolo = $_POST['ruolo'];

        echo $id . ' ' . $nome . ' ' . $email .' ' . $password . ' ' .$ruolo;
        
        $q2 = "insert into utente values ($1,$2,$3, $4,$5)";
                        
        $data = pg_query_params($dbconn, $q2, array($id, $ruolo, $nome, $email, $password));

        $_SESSION['id_log'] = $id;
        $_SESSION['ruolo'] = $ruolo;
        $_SESSION['email'] = $email;
        $_SESSION['nome'] = $nome;
        $_SESSION['path_img'] = "/assets/img/default.jpg";
        
        header('Location: /app/home.php');
    }
    else {
        echo "Registrazione non riuscita";
    }

?>

