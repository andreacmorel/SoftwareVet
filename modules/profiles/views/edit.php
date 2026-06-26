<?php
require_once '../../app/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="utf-8">
<title>Modificar Perfil</title>

<link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../../css/sb-admin-2.min.css" rel="stylesheet">

<style>
.page-title {
    font-weight:800;
    color:#1f2937;
    margin-bottom:2px;
}

.page-title i {
    color:#52266E;
}

.page-subtitle {
    color:#9ca3af;
    font-size:14px;
}

.form-card {
    background:white;
    border-radius:15px;
    padding:25px;
    box-shadow:0 4px 18px rgba(0,0,0,.06);
    margin-top:20px;
}

label {
    color:#52266E;
    font-size:12px;
    font-weight:800;
    text-transform:uppercase;
}

.form-control {
    border-radius:8px;
    border:1px solid #d8c2e8;
    font-size:14px;
}

.form-control:focus {
    border-color:#52266E;
    box-shadow:0 0 0 3px rgba(82,38,110,.12);
}

.form-control.is-invalid {
    border-color:#dc2626 !important;
    box-shadow:0 0 0 3px rgba(220,38,38,.12) !important;
}

.invalid-feedback {
    display:block;
    font-size:13px;
    font-weight:600;
}

.btn-purple {
    background:#52266E;
    color:white;
    border-radius:8px;
    font-weight:700;
    padding:8px 20px;
}

.btn-purple:hover {
    background:#3f1d55;
    color:white;
}

.btn-cancel {
    background:#e5e7eb;
    color:#374151;
    border-radius:8px;
    font-weight:700;
    padding:8px 20px;
}

.btn-cancel:hover {
    background:#d1d5db;
    color:#111827;
}

.section-title {
    color:#52266E;
    font-weight:800;
    margin-bottom:15px;
}
</style>
</head>

<body>

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 page-title">
            <i class="fas fa-user-edit mr-2"></i> Modificar Perfil
        </h1>

        <div class="page-subtitle">
            Editá el nombre del perfil seleccionado
        </div>
    </div>
</div>

<div class="form-card">

<form method="POST" novalidate>

    <h5 class="section-title">
        <i class="fas fa-id-badge mr-2"></i> Datos del Perfil
    </h5>

    <div class="form-group mb-4">

        <label>
            Nombre del perfil
            <span style="color:#dc2626;">*</span>
        </label>

        <input 
            type="text" 
            name="nombre_perfil" 
            class="form-control <?php echo isset($erroresCampos['nombre_perfil']) ? 'is-invalid' : ''; ?>"
            value="<?= htmlspecialchars($perfilEditar->nombre_perfil) ?>"
        >

        <?php if (isset($erroresCampos['nombre_perfil'])) { ?>

            <div class="invalid-feedback">
                <?php echo htmlspecialchars($erroresCampos['nombre_perfil']); ?>
            </div>

        <?php } ?>

    </div>

    <div class="d-flex justify-content-between">

        <a href="index.php" class="btn btn-cancel">
            <i class="fas fa-times mr-1"></i>
            Cancelar
        </a>

        <button type="submit" name="btnModificar" value="1" class="btn btn-purple">
            <i class="fas fa-save mr-1"></i>
            Guardar cambios
        </button>

    </div>

</form>

</div>

</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

</body>
</html>