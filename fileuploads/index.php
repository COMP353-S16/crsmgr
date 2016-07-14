<?php
define('UPLOAD_DIR', 'uploads/');
//echo "<pre>";
//print_r($_POST);
//print_r($_FILES);



if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_FILES["fileUpload"])) {

    session_start();
    require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/dbc.php');

    /*
    $myFile = $_FILES["fileUpload"];

    // give the filename a unique name using time


    if ($myFile["error"] !== UPLOAD_ERR_OK) {
        echo "<p>An error occurred.</p>";
        exit;
    }
    // ensure a safe filename
    $name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);

    // give it a unique name
    $parts = pathinfo($name);
    $timeparts = explode(" ",microtime());
    $unique = bcadd(($timeparts[0]*1000),bcmul($timeparts[1],1000));

    $fileName = $parts["filename"] . "_" . $unique;
    $fileExtension = $parts["extension"];
    $newFileName = $fileName . "." . $fileExtension ;



    // where to upload ?
    $groupID = $_POST['gid'];
    $courseID = 1;
    $deliverableID = 1;
    $userID = 1;

    $uploadDirectory = UPLOAD_DIR. $courseID . '/' . $deliverableID . '/' . $groupID . '/';  // Directory to upload file

    // Create the directory if it doesn't exist
    if (!file_exists($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    // preserve file from temporary directory
    $success = move_uploaded_file($myFile["tmp_name"], $uploadDirectory . $newFileName);
    */

    $UploadHandler = new UploadHandler(1,1,1,1,$_FILES['fileUpload']);

    $success = $UploadHandler->upload();


    if (!$success) {

        $errors = $UploadHandler->getErrors();


        ?>

        <br>
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php
            $msg .= "<ul>";
            foreach ($errors as $error)
            {
                $msg .= '<li>' . $error . '</li>';
            }
            $msg .= "</ul>";
            echo $msg;
            ?>
        </div>

        <?php
        exit;
    }
    else
    {

                ?>
                <p>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Uploaded file <?php echo $UploadHandler->getFile()->getBaseName().'.'.$UploadHandler->getFile()->getFileExtension();?> saved as <a href="<?php echo $_SERVER['REMOTE_HOST'] . '/fileuploads/' . $UploadHandler->getBuildDirectory() . $UploadHandler->getSavedAsName(); ?>"> <?php echo $UploadHandler->getSavedAsName();?></a>
                </div>
                <?php



    }


} else
    echo 'no';
die;
