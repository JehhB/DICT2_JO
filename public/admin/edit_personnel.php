<?php
require_once __DIR__ . '/../app/setup.php';
$account = protectRoute(true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $sql = "
    UPDATE Personnels 
    SET 
        name = :name, 
        position = :position, 
        project_id = :project_id 
    WHERE 
        personnel_id = :personnel_id
    ";


    $id = execute("SELECT project_id FROM Projects WHERE project_name = :n", ['n' => $_POST['project_name']])->fetchColumn();
    if (!$id) $id = null;


    $args = [
        ':personnel_id' => $_POST['id'],
        ':name' => $_POST['name'],
        ':position' => $_POST['position'],
        ':project_id' => $id,
    ];


    execute($sql, $args);


    header('Location: /admin/personnels.php');
    exit();
}

header('Location: /admin/personnels.php');
exit();

?>