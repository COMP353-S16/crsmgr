<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/dbc.php');
?>
<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="../"><?php echo CoreConfig::settings()['appname']; ?></a>
</div>
<!-- /.navbar-header -->