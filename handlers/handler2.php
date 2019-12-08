<?php
header('Content-type: application/json');
$posted_data = json_decode(file_get_contents("php://input"), true);
$data = ["get" => $_GET, "post" => $_POST, "other" => $posted_data];
if ($posted_data["username"] == "Werzy" && $posted_data["password"] == "test") {
    $data["logged_in"] = true;
}
print(json_encode($data));
