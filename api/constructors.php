<?php
header("Content-Type: application/json");
$db = new PDO("sqlite:../data/f1.db");

$request_method = $_SERVER["REQUEST_METHOD"];
$ref = isset($_GET["ref"]) ? $_GET["ref"] : null;

if ($request_method === "GET") {
  if ($ref) {
    $stmt = $db->prepare("SELECT * FROM constructors WHERE constructorRef = ?");
    $stmt->execute([$ref]);
    $constructor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($constructor) {
      echo json_encode($constructor);
    } else {
      echo json_encode(["message" => "Constructor not found"]);
    }
  } else {
    $stmt = $db->query("SELECT * FROM constructors");
    $constructors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($constructors);
  }
} else {
  http_response_code(405);
  echo json_encode(["message" => "Method not allowed"]);
}
?>
