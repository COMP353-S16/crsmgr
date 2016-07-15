<?php

if(empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post')
{
    $postMax = ini_get('post_max_size'); //grab the size limits...
    echo "Please note files larger than {$postMax} will result in this error!";
}
else 
{

    session_start();
    require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/dbc.php');
    $UploadHandler = new UploadHandler(1,1,1,1,$_FILES['fileUpload']);
    $UploadHandler->setUploadDirectory(CoreConfig::settings()['uploads']['upload_dir']);
    $success = $UploadHandler->upload();

    if (!$success) {
        $errors = $UploadHandler->getErrors();
        ?>
        <script>
            $(function(){
                $('.progress-bar').removeClass('progress-bar-success').addClass('progress-bar-danger');
            });
        </script>
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

        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            Uploaded file <?php echo $UploadHandler->getFile()->getBaseName().'.'.$UploadHandler->getFile()->getFileExtension();?> saved as <a href="<?php echo $_SERVER['REMOTE_HOST'] . '/fileuploads/' . $UploadHandler->getBuildDirectory() . $UploadHandler->getSavedAsName(); ?>"> <?php echo $UploadHandler->getSavedAsName();?></a>
        </div>
        <?php



    }


}
