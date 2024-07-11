<?php
require_once __DIR__ . '/../app/setup.php';

$account = protectRoute(true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_logo_path = ""; 
    if (isset($_FILES['project_logo']) && $_FILES['project_logo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../storage/';
        $target_name = uniqid() . basename($_FILES['project_logo']['name']);
        $uploadFile = $uploadDir . $target_name;

        if (move_uploaded_file($_FILES['project_logo']['tmp_name'], $uploadFile)) {
            $project_logo_path = '/storage/'. $target_name;
        } else {
            echo "File upload failed!";
            exit();
        }
    } elseif (isset($_FILES['project_logo']) && $_FILES['project_logo']['error'] !== UPLOAD_ERR_NO_FILE) {
        echo "File upload error occurred.";
        exit();
    }
    $sql = "
    INSERT INTO Projects (
        focal_person_id, project_name ,project_code,project_logo
    ) VALUES (
        :focal_person_id, :project_name, :project_code, :project_logo
    );";
    
    $id = execute("SELECT personnel_id FROM Personnels WHERE name = :n", ['n' => $_POST['focal_person']])->fetchColumn();
    if (!$id) $id = null;

    $args = [
        ':focal_person_id' => $id,
        ':project_name' => $_POST['project_name'],
        ':project_code' => $_POST['project_code'],
        ':project_logo' => $project_logo_path
    ];
    
    execute($sql,$args);
    
    header('Location: /admin/projects.php');
    exit();
    
    }

    $sql = "
    SELECT
        proj.*,
        pa.name
    FROM Projects proj
    JOIN Personnels pa ON proj.focal_person_id = pa.personnel_id
    WHERE proj.deleted = false
    ";
$stmt = execute($sql);
$project = $stmt->fetchAll(); 

$stmt = execute('SELECT name FROM Personnels');
$personnels = $stmt->fetchAll();
$options = array_map(function ($item) {
  return $item['name'];
}, $personnels);


echo $twig->render('projects.twig', ['options'=> $options,'project' => $project, 'count' => count($project)]);