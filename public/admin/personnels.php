<?php
require_once __DIR__ . '/../app/setup.php';
$account = protectRoute(true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
  $sql = "
  INSERT INTO Personnels (
      name, position ,project_id
  ) VALUES (
      :name, :position, :project_id
  );";
  
  $id = execute("SELECT project_id FROM Projects WHERE project_name = :n", ['n' => $_POST['project_name']])->fetchColumn();
  if (!$id) $id = null;

  $args = [
      ':project_id' => $id,
      ':name' => $_POST['name'],
      ':position' => $_POST['position'],
  ];
  
  execute($sql,$args);
  
  header('Location: /admin/personnels.php');
  exit();
  
  }

$sql = "
        SELECT
            *,
            proj.project_name
            FROM Personnels per
            LEFT JOIN Projects proj ON per.project_id = proj.project_id
            ORDER BY per.name ASC
        
";

$stmt = execute($sql);
$personnel = $stmt->fetchAll();

$stmt = execute('SELECT project_name FROM Projects');
$projects = $stmt->fetchAll();
$options = array_map(function ($item) {
  return $item['project_name'];
}, $projects);

echo $twig->render('personnels.twig', ['options' => $options, 'personnel' => $personnel, 'count' => count($personnel)]);