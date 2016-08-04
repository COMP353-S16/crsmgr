<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

if(!isset($_REQUEST['gid']))
    exit("Error. No group id found.");

$gid = $_REQUEST['gid'];
$pdo = Registry::getConnection();

//TODO Need to correct this for current timezone since the server's timezone does not match!!!
$query = $pdo->prepare("SELECT d.did FROM Deliverables d, GroupDeliverables gd
                WHERE gd.gid=:gid AND gd.did = d.did AND (:d) BETWEEN d.startDate AND d.endDate ");
$query->bindValue(":gid", $gid);
$query->bindValue(":d", '\''.date('Y-m-d H:i:s').'\'');
$query->execute();


//echo '\''.date('Y-m-d H:i:s').'\'';

if ($query->rowCount() > 0)
{
    ?>
    <!-- File Uploader -->
    <form id="uploadForm">

        <div class="form-group">
            <label for="sel1">Select Deliverable</label>
            <select class="form-control" id="deliverableSelect">
                <?php

                while ($del = $query->fetch())
                {
                    $Deliverable = new Deliverable($del['did']);




                        ?>
                        <option value="<?php echo $del['did']; ?>"><?php echo $Deliverable->getDName(); ?>
                            - Due on <?php echo $Deliverable->getEndDate(); ?></option>
                        <?php

                }
                ?>
            </select>
        </div>

        <label id="label-browser" class="btn btn-success btn-file" >
            Browse
            <input type="file" name="fileUpload" id="fileUpload" class="fileUpload" style="display: none;" multiple/>
        </label>


        <button class="btn btn-warning btn-file" id="cancelUpload">Cancel</button>
        Max upload size: <?php echo $max_upload = min((int)ini_get('post_max_size'), (int)(ini_get('upload_max_filesize'))); ?>M
        <p>
        <div class="progress" style="display: none;">
            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="0"
                aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
        </div>
        <div id="uploadResult"></div>

    </form>
    <!-- /File Uploader -->

    <?php
}
else
{
    ?>
    <p class="text-warning">There are no assigned deliverables.</p>
    <?php
}
?>