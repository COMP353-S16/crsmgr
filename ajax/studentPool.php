<?php
if (!isset($_REQUEST) || empty($_REQUEST))
{
    exit;
}

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');


$pdo = Registry::getConnection();
$query = $pdo->prepare("SELECT * FROM Students s, Users u, StudentSemester st
WHERE u.uid = s.uid
      AND s.uid = st.uid
      AND st.sid = :sid

      AND (st.uid, st.sid) NOT IN
(SELECT gm.uid, gm.sid FROM GroupMembers gm) ");
$query->bindValue(":sid", $_REQUEST['sid']);
$query->execute();
$data = $query->fetchAll();
$rows = $query->rowCount();

if ($rows <= 0)
{
    echo "NO DATA";
}
?>


    <ol>
        <?php
        foreach ($data as $studentData)
        {

            $Student = new Student($studentData['uid']);

            if ($Student->getSemesters()->isRegisteredForSemester($_REQUEST['sid']) && $Student->isStudent())
            {


                ?>
                <li><?php echo $Student->getFirstName() . ' ' . $Student->getLastName() . ' - ID#' . $Student->getUid() . ' - Section ' . $Student->getSemesters()->getSectionName($_REQUEST['sid']); ?></li>
                <?php
            }
        }
        ?>

    </ol>

<?php
