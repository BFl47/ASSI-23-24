<?php
    require '../vendor/autoload.php';
    session_start();
    //echo "Inizio script oauth_login.php";

    $provider = new League\OAuth2\Client\Provider\Github([
        'clientId' => 'Ov23linruC7jbQV2OT8e',
        'clientSecret' => '542cc302e4401e1370bb56ab6acf6d4d2695645e',
        'redirectUri' => 'http://localhost:3000/callback.php',
        'urlAuthorize'      => 'https://github.com/login/oauth/authorize',
        'urlAccessToken'    => 'https://github.com/login/oauth/access_token',
        'urlResourceOwnerDetails' => 'https://api.github.com/user'
    ]);
    //echo "Provider configurato";

    $authUrl = $provider->getAuthorizationUrl([
        'scope' => ['user']  
    ]);
    $_SESSION['oauth2state'] = $provider->getState();
    //echo "URL di autorizzazione ottenuto: " . $authUrl;

    header('Location: ' . $authUrl);
    exit();
?>
