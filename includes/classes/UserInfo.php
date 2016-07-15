<?php

/**
 * Created by PhpStorm.
 * User: josep
 * Date: 7/14/2016
 * Time: 9:10 PM
 */
class UserInfo
{
    /**
     * @var User
     */
    protected $_User;

    public function __construct($user)
    {
        $this->_User = $user;
    }

    public function fetchUserCourses() {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT c.cName, c.cid FROM Courses c, Groups g, GroupMembers m, Users u
                                        WHERE u.uid=:uid AND m.gid = g.gid AND g.cid = c.cid AND m.uid = u.uid");
        $query->bindValue(":uid", $this->_User->getUid());
        $query->execute();
        $userCourses = $query->fetchAll();
        
        return $userCourses;
    }

    public function getGroup($cid) {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT g.gid, g.leaderId, g.cid, g.creatorId, g.gName FROM Groups g, Users u, GroupMembers m, Courses c 
                                WHERE u.uid=:uid AND m.uid = u.uid AND m.gid = g.gid AND g.cid=:cid");
        $query->bindValue(":uid", $this->_User->getUid());
        $query->bindValue(":cid", $cid);
        $query->execute();
        $user_group = $query->fetch();

        return $user_group;
    }


}