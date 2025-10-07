<?php
header("Content-Type: application/json");
require_once "dbconn.php";

// Read input
$complaint_id = isset($_POST["complaint_id"]) ? intval($_POST["complaint_id"]) : 0;
$decision     = isset($_POST["decision"]) ? strtolower(trim($_POST["decision"])) : "";
$reason       = isset($_POST["reason"]) ? $conn->real_escape_string(trim($_POST["reason"])) : "";

// Validate
if (!$complaint_id || !$decision || !$reason) {
    http_response_code(400);
    echo json_encode(["error" => "Missing required fields: complaint_id, decision, or reason"]);
    exit;
}

if (!in_array($decision, ["solve", "deny"])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid decision"]);
    exit;
}

// Check if complaint exists
$check_sql = "SELECT id FROM complaints WHERE id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("i", $complaint_id);
$check_stmt->execute();
$check_stmt->store_result();
if ($check_stmt->num_rows === 0) {
    echo json_encode(["error" => "Complaint not found"]);
    exit;
}
$check_stmt->close();

// Update complaint
$decision_value = ($decision === "solve") ? "Solved" : "Denied";

$update_sql = "UPDATE complaints 
               SET status = ?, updated_at = NOW() 
               WHERE id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("si", $decision_value, $complaint_id);

if ($update_stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Complaint resolved successfully",
        "complaint_id" => $complaint_id,
        "decision" => $decision_value,
        "reason" => $reason  // still returning it in response
    ]);
} else {
    echo json_encode(["error" => "Failed to update complaint"]);
}
$update_stmt->close();
$conn->close();
?>
