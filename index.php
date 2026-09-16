<?php
require_once "config.php";

$mensaje = "";
$error = "";

// Crear
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["accion"] ?? "") === "crear") {
    $destinatario = trim($_POST["destinatario"] ?? "");
    $direccion = trim($_POST["direccion"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");

    if ($destinatario === "" || $direccion === "" || $descripcion === "") {
        $error = "Todos los campos son obligatorios.";
    } else {
        $stmt = $conn->prepare("INSERT INTO envios (destinatario, direccion, descripcion) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $destinatario, $direccion, $descripcion);
        if ($stmt->execute()) {
            $mensaje = "Envío creado correctamente.";
        } else {
            $error = "No se pudo crear el envío.";
        }
        $stmt->close();
    }
}

// Eliminar
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["accion"] ?? "") === "eliminar") {
    $id = (int)($_POST["id"] ?? 0);
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM envios WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $mensaje = "Envío eliminado correctamente.";
        } else {
            $error = "No se pudo eliminar el envío.";
        }
        $stmt->close();
    }
}

// Editar
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["accion"] ?? "") === "editar") {
    $id = (int)($_POST["id"] ?? 0);
    $destinatario = trim($_POST["destinatario"] ?? "");
    $direccion = trim($_POST["direccion"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");

    if ($id <= 0 || $destinatario === "" || $direccion === "" || $descripcion === "") {
        $error = "Los datos para editar no son válidos.";
    } else {
        $stmt = $conn->prepare("UPDATE envios SET destinatario=?, direccion=?, descripcion=? WHERE id=?");
        $stmt->bind_param("sssi", $destinatario, $direccion, $descripcion, $id);
        if ($stmt->execute()) {
            $mensaje = "Envío actualizado correctamente.";
        } else {
            $error = "No se pudo actualizar el envío.";
        }
        $stmt->close();
    }
}

$editar = null;
if (isset($_GET["editar"])) {
    $id = (int)$_GET["editar"];
    $stmt = $conn->prepare("SELECT id, destinatario, direccion, descripcion FROM envios WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultadoEditar = $stmt->get_result();
    $editar = $resultadoEditar->fetch_assoc();
    $stmt->close();
}

$envios = $conn->query("SELECT id, destinatario, direccion, descripcion, fecha_creacion FROM envios ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestión de Envíos</title>
<style>
:root{--primary:#2563eb;--primary-dark:#1d4ed8;--danger:#dc2626;--bg:#f1f5f9;--text:#0f172a;--muted:#64748b}
*{box-sizing:border-box}
body{margin:0;font-family:Arial,Helvetica,sans-serif;background:var(--bg);color:var(--text)}
header{background:linear-gradient(135deg,#1d4ed8,#2563eb,#3b82f6);color:white;padding:30px 20px}
header .wrap{max-width:1150px;margin:auto}
header h1{margin:0 0 7px;font-size:30px}
header p{margin:0;opacity:.9}
.container{max-width:1150px;margin:25px auto;padding:0 20px}
.grid{display:grid;grid-template-columns:340px 1fr;gap:22px}
.card{background:white;border-radius:16px;padding:22px;box-shadow:0 8px 25px rgba(15,23,42,.08)}
.card h2{margin-top:0;font-size:20px}
label{display:block;font-weight:bold;margin:14px 0 7px}
input,textarea{width:100%;padding:11px 12px;border:1px solid #cbd5e1;border-radius:9px;font-size:14px}
textarea{min-height:105px;resize:vertical}
button,.btn{border:0;border-radius:9px;padding:11px 15px;font-weight:bold;cursor:pointer;text-decoration:none;display:inline-block}
.primary{background:var(--primary);color:white;width:100%;margin-top:17px}
.primary:hover{background:var(--primary-dark)}
.cancel{background:#e2e8f0;color:#334155;margin-top:10px;width:100%;text-align:center}
.table-wrap{overflow:auto}
table{width:100%;border-collapse:collapse;min-width:700px}
th,td{padding:13px 10px;border-bottom:1px solid #e2e8f0;text-align:left;vertical-align:top}
th{background:#f8fafc}
.actions{display:flex;gap:7px}
.edit{background:#dbeafe;color:#1d4ed8}
.delete{background:#fee2e2;color:#b91c1c}
.alert{padding:12px 14px;border-radius:10px;margin-bottom:18px}
.ok{background:#dcfce7;color:#166534}
.err{background:#fee2e2;color:#991b1b}
.empty{text-align:center;color:var(--muted);padding:30px}
@media(max-width:850px){.grid{grid-template-columns:1fr}}
</style>
</head>
<body>
<header>
<div class="wrap">
<h1>📦 Gestión de Envíos</h1>
<p>Aplicativo PHP + MySQL para registrar, consultar, editar y eliminar envíos.</p>
</div>
</header>

<div class="container">
<?php if ($mensaje): ?><div class="alert ok"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="grid">
<section class="card">
<h2><?= $editar ? "Editar envío" : "Nuevo envío" ?></h2>

<form method="POST">
<input type="hidden" name="accion" value="<?= $editar ? "editar" : "crear" ?>">
<?php if ($editar): ?><input type="hidden" name="id" value="<?= (int)$editar["id"] ?>"><?php endif; ?>

<label for="destinatario">Destinatario</label>
<input id="destinatario" name="destinatario" maxlength="150" required
value="<?= htmlspecialchars($editar["destinatario"] ?? "") ?>" placeholder="Nombre del destinatario">

<label for="direccion">Dirección</label>
<input id="direccion" name="direccion" maxlength="255" required
value="<?= htmlspecialchars($editar["direccion"] ?? "") ?>" placeholder="Dirección de entrega">

<label for="descripcion">Descripción</label>
<textarea id="descripcion" name="descripcion" required placeholder="Descripción del contenido del envío"><?= htmlspecialchars($editar["descripcion"] ?? "") ?></textarea>

<button class="primary" type="submit"><?= $editar ? "Guardar cambios" : "Registrar envío" ?></button>
<?php if ($editar): ?><a class="btn cancel" href="index.php">Cancelar edición</a><?php endif; ?>
</form>
</section>

<section class="card">
<h2>Envíos registrados</h2>
<div class="table-wrap">
<table>
<thead><tr><th>ID</th><th>Destinatario</th><th>Dirección</th><th>Descripción</th><th>Fecha</th><th>Acciones</th></tr></thead>
<tbody>
<?php if ($envios && $envios->num_rows > 0): ?>
<?php while ($envio = $envios->fetch_assoc()): ?>
<tr>
<td><?= (int)$envio["id"] ?></td>
<td><?= htmlspecialchars($envio["destinatario"]) ?></td>
<td><?= htmlspecialchars($envio["direccion"]) ?></td>
<td><?= htmlspecialchars($envio["descripcion"]) ?></td>
<td><?= htmlspecialchars($envio["fecha_creacion"]) ?></td>
<td>
<div class="actions">
<a class="btn edit" href="?editar=<?= (int)$envio["id"] ?>">Editar</a>
<form method="POST" onsubmit="return confirm('¿Deseas eliminar este envío?');">
<input type="hidden" name="accion" value="eliminar">
<input type="hidden" name="id" value="<?= (int)$envio["id"] ?>">
<button class="delete" type="submit">Eliminar</button>
</form>
</div>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="6" class="empty">No hay envíos registrados.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
</section>
</div>
</div>
</body>
</html>
