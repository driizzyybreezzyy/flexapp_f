<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$input = file_get_contents("php://input");
$data = json_decode($input, true);

$data['age'] = '32';
$data['batch'] = '4 AM- 5 AM';

if (isset($data['age']) && isset($data['batch'])) {
    $age = $data['age'];
    $batch = $data['batch'];
    $description = 'You have been enrolled in the '.$batch.' batch. Please pay Rs. 500 for this month.';

    $servername = "localhost:5174";
    $username = "root";
    $password = "";
    $dbname = "flexapp";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO users (age, batch, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $age, $batch, $description);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Data inserted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to insert data']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
?>
