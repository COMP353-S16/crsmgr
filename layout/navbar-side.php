<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');
?>
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="sidebar-search">
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                </div>
                <!-- /input-group -->
            </li>
            <li>
                <a href="index.html"><i class="fa fa-home fa-fw"></i> Home</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Courses<span class="fa arrow"></span></a>
                <?php
                $pdo = Registry::getConnection();
                $query = $pdo->prepare("SELECT c.cName, c.cid FROM Courses c, Groups g, GroupMembers m, Users u
                                        WHERE u.uid=:uid AND m.gid = g.gid AND g.cid = c.cid AND m.uid = u.uid");
                $query->bindValue(":uid", $_SESSION['uid']);
                $query->execute();
                $users = $query->fetchAll();

                //print_r($users);
                foreach ($users as $user_data) {
                    ?>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="<?php echo 'page.php?cid='.$user_data['cid'];?>"><?php echo $user_data['cName']; ?></a>
                        </li>
                    </ul>
                    <?php
                }
                ?>
                
                <!-- /.nav-second-level -->
                <a href="#"><i class="fa fa-book fa-fw"></i> Courses<span class="fa arrow"></span></a>
            </li>
            <li>
                <a href="tables.html"><i class="fa fa-wrench fa-fw"></i>Admin</a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="flot.html">Groups</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->