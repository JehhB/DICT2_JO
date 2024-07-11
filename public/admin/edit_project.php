<?php
require_once __DIR__ . '/../app/setup.php';
$account = protectRoute(true);

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'id' is set in POST data
    if (isset($_POST['id'])) {

        
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

        // Construct the SQL statement
        $sql = "
            UPDATE Projects 
            SET 
                project_name = :project_name, 
                project_code = :project_code, 
                focal_person_id = :focal_person_id,
                project_logo = :project_logo
            WHERE 
                project_id = :project_id
        ";

        // Get focal_person_id from Personnels table
        $focal_person_id = execute("SELECT personnel_id FROM Personnels WHERE name = :name", ['name' => $_POST['focal_person']])->fetchColumn();
        if (!$focal_person_id) {
            $focal_person_id = null; // Set to null if no matching personnel found
        }

        // Prepare the arguments for the SQL query
        $args = [
            ':project_name' => $_POST['project_name'],
            ':project_code' => $_POST['project_code'],
            ':focal_person_id' => $focal_person_id,
            ':project_logo' => $project_logo_path, // Set project_logo to null if no file was uploaded
            ':project_id' => $_POST['id']
        ];

        // Execute the SQL query
        execute($sql, $args);

        // Redirect to the projects page after update
        header('Location: /admin/projects.php');
        exit();
    } else {
        echo "Project ID is required!";
        exit();
    }
}

// Redirect to projects page if not a POST request
header('Location: /admin/projects.php');
exit();
?>