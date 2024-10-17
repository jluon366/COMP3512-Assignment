<?php
header("Content-Type: application/json");
$db = new PDO("sqlite:../data/f1.db");

$request_method = $_SERVER["REQUEST_METHOD"];
$ref = isset($_GET["ref"]) ? $_GET["ref"] : null;


if ($request_method === "GET") {
  if ($ref) {
    $stmt = $db->prepare("SELECT * FROM circuits WHERE circuitRef = ?");
    $stmt->execute([$ref]);
    $circuit = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($circuit) {
      echo json_encode($circuit);
    } else {
      echo json_encode(["message" => "Circuit not found"]);
    }
  } else {
    $stmt = $db->query("SELECT * FROM circuits");
    $circuits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($circuits);
  }
} else {
  http_response_code(405);
  echo json_encode(["message" => "Method Not Allowed"]);
}
?>
