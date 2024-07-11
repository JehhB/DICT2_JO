<?php
require_once __DIR__ . '/../app/setup.php';
$account = protectRoute(true);

if (empty($_GET['id'])){
    header('Location: /admin/personnels.php');  
    exit();
}

$personnel_id = $_GET['id'];

$stmt = execute("SELECT personnel_id FROM Personnels WHERE personnel_id = :personnel_id", [':personnel_id' => $personnel_id]);
$account = $stmt->fetch();


execute("DELETE FROM Personnels WHERE personnel_id = :personnel_id", [':personnel_id' => $account['personnel_id']]);



header('Location: /admin/personnels.php');
exit();