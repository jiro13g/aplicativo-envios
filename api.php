<?php
header("Content-Type: application/json; charset=UTF-8");
require_once "config.php";

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET") {
    if (isset($_GET["id"])) {
        $id = (int)$_GET["id"];
        $stmt = $conn->prepare("SELECT id, destinatario, direccion, descripcion, fecha_creacion FROM envios WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();

        if (!$data) {
            http_response_code(404);
            echo json_encode(["error" => "Envío no encontrado"], JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        $result = $conn->query("SELECT id, destinatario, direccion, descripcion, fecha_creacion FROM envios ORDER BY id DESC");
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
if (!is_array($input)) $input = $_POST;

if ($method === "POST") {
    $destinatario = trim($input["destinatario"] ?? "");
    $direccion = trim($input["direccion"] ?? "");
    $descripcion = trim($input["descripcion"] ?? "");

    if ($destinatario === "" || $direccion === "" || $descripcion === "") {
        http_response_code(400);
        echo json_encode(["error" => "destinatario, direccion y descripcion son obligatorios"], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO envios (destinatario, direccion, descripcion) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $destinatario, $direccion, $descripcion);
    $stmt->execute();
    $id = $stmt->insert_id;
    $stmt->close();

    http_response_code(201);
    echo json_encode(["mensaje" => "Envío creado", "id" => $id], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($method === "PUT") {
    $id = (int)($input["id"] ?? 0);
    $destinatario = trim($input["destinatario"] ?? "");
    $direccion = trim($input["direccion"] ?? "");
    $descripcion = trim($input["descripcion"] ?? "");

    if ($id <= 0 || $destinatario === "" || $direccion === "" || $descripcion === "") {
        http_response_code(400);
        echo json_encode(["error" => "id, destinatario, direccion y descripcion son obligatorios"], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $stmt = $conn->prepare("UPDATE envios SET destinatario=?, direccion=?, descripcion=? WHERE id=?");
    $stmt->bind_param("sssi", $destinatario, $direccion, $descripcion, $id);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    echo json_encode(["mensaje" => $affected >= 0 ? "Envío actualizado" : "No se pudo actualizar"], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($method === "DELETE") {
    $id = (int)($input["id"] ?? $_GET["id"] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(["error" => "Debe indicar un id válido"], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM envios WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    if ($affected === 0) {
        http_response_code(404);
        echo json_encode(["error" => "Envío no encontrado"], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["mensaje" => "Envío eliminado"], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

http_response_code(405);
echo json_encode(["error" => "Método no permitido"], JSON_UNESCAPED_UNICODE);
?>
