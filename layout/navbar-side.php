<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');
$WebUser = WebUser::getUser();
?>
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <?php
            if($WebUser->isStudent())
            {
                ?>
                <li>
                    <a href="home.php"><i class="fa fa-home fa-fw"></i> Home</a>
                </li>
                <?php
            }
            else
            {
                ?>
                <li>
                    <a href="admin.php"><i class="fa fa-wrench fa-fw"></i>Admin</a>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->