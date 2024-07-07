<?php
// comunica con listacorsi.php
session_start();

if (!isset($_SESSION['id_log'])) {
    echo json_encode(['success' => false, 'message' => 'Non sei loggato']);
    exit;
}

$dbconn = pg_connect("host=localhost port=5432 dbname=GymGeniusASSI user=postgres password=password") 
    or die('Could not connect: ' . pg_last_error());

$action = $_POST['action'];
$corso_id = $_POST['corso_id'];
$user_id = $_SESSION['id_log'];

$response = ['success' => false];

if ($action === 'add') {
    $q = "INSERT INTO preferito (id_user, id_corso, rating) VALUES ($1, $2, NULL)";
    $result = pg_query_params($dbconn, $q, array($user_id, $corso_id));
    if ($result) {
        $response['success'] = true;
    } else {
        $response['message'] = pg_last_error($dbconn);
    }
} elseif ($action === 'remove') {
    $q = "DELETE FROM preferito WHERE id_user = $1 AND id_corso = $2";
    $result = pg_query_params($dbconn, $q, array($user_id, $corso_id));
    if ($result) {
        $response['success'] = true;
    } else {
        $response['message'] = pg_last_error($dbconn);
    }
} else {
    $response['message'] = 'Azione non valida';
}

echo json_encode($response);

pg_close($dbconn);
?>

