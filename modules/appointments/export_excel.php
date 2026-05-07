<?php
require_once '../../settings/conexion.php';

$filename = "listado_turnos_" . date('Ymd') . ".xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

$sql = $conexion->query("
    SELECT t.id_turno, t.fecha, t.hora, t.motivo,
           CONCAT(per.nombre_persona, ' ', per.apellido_persona) AS profesional,
           m.nombre_mascota AS mascota
    FROM turnos t
    INNER JOIN profesional p ON t.id_profesional = p.id_profesional
    INNER JOIN persona per ON p.id_persona = per.id_persona
    INNER JOIN mascota m ON t.id_mascota = m.id_mascota
    ORDER BY t.fecha DESC, t.hora DESC
");

echo "ID\tFecha\tHora\tMotivo\tProfesional\tMascota\n";

while ($datos = $sql->fetch_object()) {
    echo "{$datos->id_turno}\t{$datos->fecha}\t{$datos->hora}\t{$datos->motivo}\t{$datos->profesional}\t{$datos->mascota}\n";
}
?>
