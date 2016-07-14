<?php
define('UPLOAD_DIR', 'files/');
//echo "<pre>";
//print_r($_POST);
//print_r($_FILES);


if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_FILES["fileUpload"])) {
    $myFile = $_FILES["fileUpload"];

    if ($myFile["error"] !== UPLOAD_ERR_OK) {
        echo "<p>An error occurred.</p>";
        exit;
    }
    // ensure a safe filename
    $name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);
    // don't overwrite an existing file
    $i = 0;
    $parts = pathinfo($name);
    while (file_exists(UPLOAD_DIR . $name)) {
        $i++;
        $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
    }
    // preserve file from temporary directory
    $success = move_uploaded_file($myFile["tmp_name"], UPLOAD_DIR . $name);
    if (!$success) {
        echo "<p>Unable to save file.</p>";
        exit;
    }
    // set proper permissions on the new file
    chmod(UPLOAD_DIR . $name, 0644);
    echo "<p>Uploaded file saved as " . $name . ".</p>";
} else
    echo 'no';
die;
