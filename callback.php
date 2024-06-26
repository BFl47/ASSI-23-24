<?php
    require_once 'vendor/autoload.php';
    session_start();

    //echo "Inizio script oauth_callback.php<br>";

    $provider = new League\OAuth2\Client\Provider\Github([
        'clientId' => 'Ov23linruC7jbQV2OT8e',
        'clientSecret' => '542cc302e4401e1370bb56ab6acf6d4d2695645e',
        'redirectUri' => 'http://localhost:3000/callback.php',
        'urlAuthorize'      => 'https://github.com/login/oauth/authorize',
        'urlAccessToken'    => 'https://github.com/login/oauth/access_token',
        'urlResourceOwnerDetails' => 'https://api.github.com/user'
    ]);
    //echo "Provider configurato<br>";

    if (isset($_GET['error'])) {
        exit('Error: ' . htmlspecialchars($_GET['error']));

    } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
        //echo "Stato OAuth non corrisponde o è vuoto";
        unset($_SESSION['oauth2state']);
        exit('Invalid state');

    } else {
        try {
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);
            //echo "Token di accesso ottenuto: " . htmlspecialchars($token->getToken()) . "<br>";
            $user = $provider->getResourceOwner($token);
        
            $_SESSION['id_log'] = $user->getId();
            $_SESSION['email'] = $user->getEmail();
            $_SESSION['nome'] = $user->getName();
            $_SESSION['ruolo'] = 'User'; 
            //echo "Dati dell'utente salvati in sessione";
            
            $dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") or die('Could not connect: ' . pg_last_error());
            if ($dbconn) {
                $email = $_SESSION['email'];

                $q1 = "SELECT * from utente where email=$1";
                $result = pg_query_params($dbconn, $q1, array($email));
                
                if (!$tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
                    //echo "Utente non trovato nel database";
                    $id = $_SESSION['id_log'];
                    $nome = $_SESSION['nome'];
                    $password = '';
                    $ruolo = 'User'; // Ruolo di default
                    
                    $q2 = "INSERT INTO utente (id, ruolo, nome, email) values ($1, $2, $3, $4)";
                    pg_query_params($dbconn, $q2, array($id, $ruolo, $nome, $email));

                    $_SESSION['path_img'] = '/app/assets/profile.jpg';
                }
                else {
                    $_SESSION['path_img'] = $tuple['path_img'];
                }
                header('Location: /app/home.php');
            } else {
                echo "Registrazione non riuscita";
            }
        } catch (Exception $e) {
            exit('Errore ottenendo il token di accesso: ' . $e->getMessage());
        }
    }
?>
