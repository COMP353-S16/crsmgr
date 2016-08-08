-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Aug 07, 2016 at 11:43 PM
-- Server version: 5.5.45-cll-lve
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `roc353_1`
--

-- --------------------------------------------------------

--
-- Table structure for table `DeletedFiles`
--

DROP TABLE IF EXISTS `DeletedFiles`;
CREATE TABLE IF NOT EXISTS `DeletedFiles` (
  `fid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `dateDelete` datetime NOT NULL,
  `expiresOn` datetime DEFAULT NULL,
  PRIMARY KEY (`fid`),
  KEY `DeletedFiles_fid_index` (`fid`),
  KEY `DeletedFiles_uid_index` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `DeletedFiles`
--
DROP TRIGGER IF EXISTS `ins_expiry`;
DELIMITER //
CREATE TRIGGER `ins_expiry` BEFORE INSERT ON `DeletedFiles`
 FOR EACH ROW SET NEW.expiresOn = NEW.dateDelete + INTERVAL 1 DAY
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Deliverables`
--

DROP TABLE IF EXISTS `Deliverables`;
CREATE TABLE IF NOT EXISTS `Deliverables` (
  `did` int(11) NOT NULL AUTO_INCREMENT,
  `dName` text,
  `startDate` datetime DEFAULT NULL,
  `endDate` datetime DEFAULT NULL,
  `sid` int(11) NOT NULL,
  PRIMARY KEY (`did`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Downloads`
--

DROP TABLE IF EXISTS `Downloads`;
CREATE TABLE IF NOT EXISTS `Downloads` (
  `dlid` int(11) NOT NULL AUTO_INCREMENT,
  `vid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `downloadDate` datetime DEFAULT NULL,
  PRIMARY KEY (`dlid`),
  KEY `Downloads_Users_uid_fk` (`uid`),
  KEY `Downloads_Versions_vid_fk` (`vid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Files`
--

DROP TABLE IF EXISTS `Files`;
CREATE TABLE IF NOT EXISTS `Files` (
  `fid` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) DEFAULT NULL,
  `did` int(11) DEFAULT NULL,
  `fName` text,
  `fType` text,
  `mime` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`fid`),
  KEY `Files_Groups_gid_fk` (`gid`),
  KEY `Files_Deliverables_did_fk` (`did`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `GroupDeliverables`
--

DROP TABLE IF EXISTS `GroupDeliverables`;
CREATE TABLE IF NOT EXISTS `GroupDeliverables` (
  `gid` int(11) NOT NULL,
  `did` int(11) NOT NULL,
  PRIMARY KEY (`gid`,`did`),
  KEY `groupDeliverables_Deliverables_did_fk` (`did`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='hasDel relation';

-- --------------------------------------------------------

--
-- Table structure for table `GroupMembers`
--

DROP TABLE IF EXISTS `GroupMembers`;
CREATE TABLE IF NOT EXISTS `GroupMembers` (
  `sid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`sid`,`uid`),
  KEY `GroupMembers_Groups_gid_fk` (`gid`),
  KEY `GroupMembers_StudentSemester_uid_fk` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Groups members table';

-- --------------------------------------------------------

--
-- Table structure for table `Groups`
--

DROP TABLE IF EXISTS `Groups`;
CREATE TABLE IF NOT EXISTS `Groups` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `leaderId` int(11) DEFAULT NULL,
  `gName` text,
  `creatorId` int(11) DEFAULT NULL,
  `maxUploadsSize` text,
  `sid` int(11) NOT NULL,
  PRIMARY KEY (`gid`),
  KEY `Group_Users_uid_fk` (`creatorId`),
  KEY `leaderId` (`leaderId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `RegisteredStudentsInGroup`
--
DROP VIEW IF EXISTS `RegisteredStudentsInGroup`;
CREATE TABLE IF NOT EXISTS `RegisteredStudentsInGroup` (
`uid` int(11)
,`firstName` varchar(20)
,`lastName` text
,`email` varchar(100)
,`privilege` tinyint(4)
,`username` varchar(30)
,`password` text
,`gid` int(11)
,`leaderId` int(11)
,`gName` text
,`creatorId` int(11)
,`maxUploadsSize` text
,`sid` int(11)
);
-- --------------------------------------------------------

--
-- Table structure for table `Semester`
--

DROP TABLE IF EXISTS `Semester`;
CREATE TABLE IF NOT EXISTS `Semester` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `startDate` datetime DEFAULT NULL,
  `endDate` datetime DEFAULT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Students`
--

DROP TABLE IF EXISTS `Students`;
CREATE TABLE IF NOT EXISTS `Students` (
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `StudentSemester`
--

DROP TABLE IF EXISTS `StudentSemester`;
CREATE TABLE IF NOT EXISTS `StudentSemester` (
  `uid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `sectionName` text NOT NULL,
  PRIMARY KEY (`uid`,`sid`),
  KEY `StudentSemester_Semester_sid_fk` (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
CREATE TABLE IF NOT EXISTS `Users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(20) NOT NULL,
  `lastName` text,
  `email` varchar(100) DEFAULT NULL,
  `privilege` tinyint(4) DEFAULT '0',
  `username` varchar(30) DEFAULT NULL,
  `password` text,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `Users_username_uindex` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Versions`
--

DROP TABLE IF EXISTS `Versions`;
CREATE TABLE IF NOT EXISTS `Versions` (
  `vid` int(11) NOT NULL AUTO_INCREMENT,
  `uploaderId` int(11) DEFAULT NULL,
  `physicalName` text,
  `size` text,
  `uploadDate` datetime DEFAULT NULL,
  `fid` int(11) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `data` longblob,
  `upload_dir` text,
  PRIMARY KEY (`vid`),
  KEY `Versions_Users_uid_fk` (`uploaderId`),
  KEY `Versions_Files_fid_fk` (`fid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure for view `RegisteredStudentsInGroup`
--
DROP TABLE IF EXISTS `RegisteredStudentsInGroup`;

CREATE ALGORITHM=UNDEFINED DEFINER=`roc353_1`@`%` SQL SECURITY DEFINER VIEW `RegisteredStudentsInGroup` AS select `u`.`uid` AS `uid`,`u`.`firstName` AS `firstName`,`u`.`lastName` AS `lastName`,`u`.`email` AS `email`,`u`.`privilege` AS `privilege`,`u`.`username` AS `username`,`u`.`password` AS `password`,`gt`.`gid` AS `gid`,`gt`.`leaderId` AS `leaderId`,`gt`.`gName` AS `gName`,`gt`.`creatorId` AS `creatorId`,`gt`.`maxUploadsSize` AS `maxUploadsSize`,`gt`.`sid` AS `sid` from ((`Students` `s` left join `Users` `u` on((`u`.`uid` = `s`.`uid`))) join `Groups` `gt`) where `s`.`uid` in (select `st`.`uid` from `StudentSemester` `st` where `st`.`uid` in (select `g`.`uid` from `GroupMembers` `g` where `g`.`gid` in (select `gr`.`gid` from `Groups` `gr` where (`gt`.`gid` = `gr`.`gid`))));

--
-- Constraints for dumped tables
--

--
-- Constraints for table `DeletedFiles`
--
ALTER TABLE `DeletedFiles`
  ADD CONSTRAINT `DeletedFiles_Files_fid_fk` FOREIGN KEY (`fid`) REFERENCES `Files` (`fid`) ON DELETE CASCADE,
  ADD CONSTRAINT `DeletedFiles_Users_uid_fk` FOREIGN KEY (`uid`) REFERENCES `Users` (`uid`);

--
-- Constraints for table `Downloads`
--
ALTER TABLE `Downloads`
  ADD CONSTRAINT `Downloads_Users_uid_fk` FOREIGN KEY (`uid`) REFERENCES `Users` (`uid`),
  ADD CONSTRAINT `Downloads_Versions_vid_fk` FOREIGN KEY (`vid`) REFERENCES `Versions` (`vid`) ON DELETE CASCADE;

--
-- Constraints for table `Files`
--
ALTER TABLE `Files`
  ADD CONSTRAINT `Files_Deliverables_did_fk` FOREIGN KEY (`did`) REFERENCES `Deliverables` (`did`) ON DELETE CASCADE,
  ADD CONSTRAINT `Files_Groups_gid_fk` FOREIGN KEY (`gid`) REFERENCES `Groups` (`gid`) ON DELETE CASCADE;

--
-- Constraints for table `GroupDeliverables`
--
ALTER TABLE `GroupDeliverables`
  ADD CONSTRAINT `groupDeliverables_Deliverables_did_fk` FOREIGN KEY (`did`) REFERENCES `Deliverables` (`did`) ON DELETE CASCADE,
  ADD CONSTRAINT `groupDeliverables_Group_gid_fk` FOREIGN KEY (`gid`) REFERENCES `Groups` (`gid`) ON DELETE CASCADE;

--
-- Constraints for table `GroupMembers`
--
ALTER TABLE `GroupMembers`
  ADD CONSTRAINT `GroupMembers_Groups_gid_fk` FOREIGN KEY (`gid`) REFERENCES `Groups` (`gid`) ON DELETE CASCADE,
  ADD CONSTRAINT `GroupMembers_StudentSemester_sid_fk` FOREIGN KEY (`sid`) REFERENCES `Semester` (`sid`) ON DELETE CASCADE;

--
-- Constraints for table `Groups`
--
ALTER TABLE `Groups`
  ADD CONSTRAINT `Group_Users_uid_fk` FOREIGN KEY (`creatorId`) REFERENCES `Users` (`uid`),
  ADD CONSTRAINT `leaderId` FOREIGN KEY (`leaderId`) REFERENCES `Users` (`uid`);

--
-- Constraints for table `Students`
--
ALTER TABLE `Students`
  ADD CONSTRAINT `Students_Users_uid_fk` FOREIGN KEY (`uid`) REFERENCES `Users` (`uid`);

--
-- Constraints for table `StudentSemester`
--
ALTER TABLE `StudentSemester`
  ADD CONSTRAINT `StudentSemester_Students_uid_fk` FOREIGN KEY (`uid`) REFERENCES `Students` (`uid`) ON DELETE CASCADE,
  ADD CONSTRAINT `StudentSemester_Semester_sid_fk` FOREIGN KEY (`sid`) REFERENCES `Semester` (`sid`) ON DELETE CASCADE;

--
-- Constraints for table `Versions`
--
ALTER TABLE `Versions`
  ADD CONSTRAINT `Versions_Files_fid_fk` FOREIGN KEY (`fid`) REFERENCES `Files` (`fid`) ON DELETE CASCADE,
  ADD CONSTRAINT `Versions_Users_uid_fk` FOREIGN KEY (`uploaderId`) REFERENCES `Users` (`uid`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
