<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');
$PromoteMember = new PromoteMember($_REQUEST['uid'], $_REQUEST['gid']);

if ($PromoteMember->promote())
{
    $Student = new Student($_REQUEST['uid'], $_REQUEST['gid']);
    ?>
    <script>
        $(function ()
        {
            var gid = '<?php echo $_REQUEST['gid'];?>';
            var newLeader = '<?php echo $Student->getFirstName() . ' ' . $Student->getLastName(); ?>';
            groupMembers.ajax.reload();
            groups.rows().every(function (rowIdx, tableLoop, rowLoop)
            {
                var data = this.data();
                console.log(data.gid);
                if (data.gid == gid)
                {
                    data.leaderName = newLeader;
                    this.invalidate();
                }
            });
            groups.draw();
        });
    </script>

    <?php
}
else
{ ?>

    <?php

}