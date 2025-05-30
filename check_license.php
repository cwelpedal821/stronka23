<?php
header('Content-Type: application/json');
session_start();

require_once('db.php');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];
$movieId = isset($_GET['movie_id']) ? intval($_GET['movie_id']) : 0;

if (!$movieId) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid movie ID']);
    exit;
}

// Check if user has license for this movie
$stmt = $conn->prepare("SELECT id FROM movie_licenses WHERE user_id = ? AND movie_id = ?");
$stmt->bind_param("ii", $userId, $movieId);
$stmt->execute();
$result = $stmt->get_result();

$hasLicense = $result->num_rows > 0;

echo json_encode(['hasLicense' => $hasLicense]);
?>