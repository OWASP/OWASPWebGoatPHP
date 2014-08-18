-- MySQL dump 10.13  Distrib 5.5.38, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: webgoatphp
-- ------------------------------------------------------
-- Server version 5.5.38-0ubuntu0.12.04.1

--
-- Table structure for table `app_user`
--

CREATE TABLE `app_user` (
  `Firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  CONSTRAINT `FK_88BDF3E911D3633A` FOREIGN KEY (`ID`) REFERENCES `PREFIX_users` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Table structure for table `PREFIX__test_logs`
--

CREATE TABLE `PREFIX__test_logs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Subject` varchar(256) COLLATE utf8_bin NOT NULL,
  `Data` text COLLATE utf8_bin NOT NULL,
  `Severity` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `SessionID` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `Timestamp` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `PREFIX__test_options`
--

CREATE TABLE `PREFIX__test_options` (
  `UserID` int(11) NOT NULL,
  `Name` varchar(200) COLLATE utf8_bin NOT NULL,
  `Value` text COLLATE utf8_bin NOT NULL,
  `Expiration` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`Name`),
  KEY `Expiration` (`Expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `PREFIX__test_rbac_permissions`
--

CREATE TABLE `PREFIX__test_rbac_permissions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Lft` int(11) NOT NULL,
  `Rght` int(11) NOT NULL,
  `Title` char(64) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Title` (`Title`),
  KEY `Lft` (`Lft`),
  KEY `Rght` (`Rght`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `PREFIX__test_rbac_rolepermissions`
--

CREATE TABLE `PREFIX__test_rbac_rolepermissions` (
  `RoleID` int(11) NOT NULL,
  `PermissionID` int(11) NOT NULL,
  `AssignmentDate` int(11) NOT NULL,
  PRIMARY KEY (`RoleID`,`PermissionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `PREFIX__test_rbac_roles`
--

CREATE TABLE `PREFIX__test_rbac_roles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Lft` int(11) NOT NULL,
  `Rght` int(11) NOT NULL,
  `Title` varchar(128) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Title` (`Title`),
  KEY `Lft` (`Lft`),
  KEY `Rght` (`Rght`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `PREFIX__test_rbac_userroles`
--

CREATE TABLE `PREFIX__test_rbac_userroles` (
  `UserID` int(11) NOT NULL,
  `RoleID` int(11) NOT NULL,
  `AssignmentDate` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`RoleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `PREFIX__test_session`
--

CREATE TABLE `PREFIX__test_session` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SessionID` char(64) COLLATE utf8_bin NOT NULL,
  `UserID` int(11) NOT NULL,
  `IP` char(15) COLLATE utf8_bin NOT NULL,
  `LoginDate` int(11) NOT NULL,
  `LastAccess` int(11) NOT NULL,
  `AccessCount` int(11) NOT NULL DEFAULT '1',
  `CurrentRequest` varchar(1024) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `SessionID` (`SessionID`),
  KEY `UserID` (`UserID`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `PREFIX__test_users`
--

CREATE TABLE `PREFIX__test_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` char(128) COLLATE utf8_bin NOT NULL,
  `Password` char(128) COLLATE utf8_bin NOT NULL,
  `Salt` varchar(128) COLLATE utf8_bin NOT NULL,
  `Protocol` float NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `PREFIX__test_xuser`
--

CREATE TABLE `PREFIX__test_xuser` (
  `ID` int(11) NOT NULL,
  `Email` varchar(256) COLLATE utf8_bin NOT NULL,
  `PasswordChangeTimestamp` int(11) NOT NULL DEFAULT '0',
  `TemporaryResetPassword` varchar(256) COLLATE utf8_bin NOT NULL DEFAULT '',
  `TemporaryResetPasswordTimeout` int(11) NOT NULL DEFAULT '0',
  `LastLoginTimestamp` int(11) NOT NULL DEFAULT '0',
  `FailedLoginAttempts` int(11) NOT NULL DEFAULT '0',
  `LockTimeout` int(16) NOT NULL DEFAULT '0',
  `Activated` int(11) NOT NULL DEFAULT '0',
  `CreateTimestamp` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Email` (`Email`(128))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `PREFIX_logs`
--

CREATE TABLE `PREFIX_logs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Subject` varchar(256) COLLATE utf8_bin NOT NULL,
  `Data` text COLLATE utf8_bin NOT NULL,
  `Severity` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `SessionID` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `Timestamp` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `PREFIX_options`
--

CREATE TABLE `PREFIX_options` (
  `UserID` int(11) NOT NULL,
  `Name` varchar(200) COLLATE utf8_bin NOT NULL,
  `Value` text COLLATE utf8_bin NOT NULL,
  `Expiration` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`Name`),
  KEY `Expiration` (`Expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `PREFIX_rbac_permissions`
--

CREATE TABLE `PREFIX_rbac_permissions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Lft` int(11) NOT NULL,
  `Rght` int(11) NOT NULL,
  `Title` char(64) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Title` (`Title`),
  KEY `Lft` (`Lft`),
  KEY `Rght` (`Rght`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `PREFIX_rbac_rolepermissions`
--

CREATE TABLE `PREFIX_rbac_rolepermissions` (
  `RoleID` int(11) NOT NULL,
  `PermissionID` int(11) NOT NULL,
  `AssignmentDate` int(11) NOT NULL,
  PRIMARY KEY (`RoleID`,`PermissionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `PREFIX_rbac_roles`
--

CREATE TABLE `PREFIX_rbac_roles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Lft` int(11) NOT NULL,
  `Rght` int(11) NOT NULL,
  `Title` varchar(128) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Title` (`Title`),
  KEY `Lft` (`Lft`),
  KEY `Rght` (`Rght`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `PREFIX_rbac_userroles`
--

CREATE TABLE `PREFIX_rbac_userroles` (
  `UserID` int(11) NOT NULL,
  `RoleID` int(11) NOT NULL,
  `AssignmentDate` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`RoleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `PREFIX_session`
--

CREATE TABLE `PREFIX_session` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SessionID` char(64) COLLATE utf8_bin NOT NULL,
  `UserID` int(11) NOT NULL,
  `IP` char(15) COLLATE utf8_bin NOT NULL,
  `LoginDate` int(11) NOT NULL,
  `LastAccess` int(11) NOT NULL,
  `AccessCount` int(11) NOT NULL DEFAULT '1',
  `CurrentRequest` varchar(1024) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `SessionID` (`SessionID`),
  KEY `UserID` (`UserID`)
) ENGINE=MEMORY AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `PREFIX_stats`
--

CREATE TABLE `PREFIX_stats` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `SessionID` varchar(128) COLLATE utf8_bin NOT NULL,
  `Timestamp` int(11) NOT NULL,
  `Page` varchar(1024) COLLATE utf8_bin NOT NULL,
  `Query` varchar(1024) COLLATE utf8_bin NOT NULL,
  `IP` char(15) COLLATE utf8_bin NOT NULL,
  `Host` varchar(256) COLLATE utf8_bin NOT NULL,
  `Protocol` varchar(10) COLLATE utf8_bin NOT NULL,
  `UserAgent` varchar(1024) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `UserID` (`UserID`),
  KEY `SessionID` (`SessionID`),
  KEY `Timestamp` (`Timestamp`),
  KEY `Page` (`Page`(255)),
  KEY `IP` (`IP`),
  KEY `Host` (`Host`(255)),
  KEY `Protocol` (`Protocol`)
) ENGINE=InnoDB AUTO_INCREMENT=49070 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `PREFIX_users`
--

CREATE TABLE `PREFIX_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(255) COLLATE utf8_bin NOT NULL,
  `Password` varchar(255) COLLATE utf8_bin NOT NULL,
  `Salt` varchar(255) COLLATE utf8_bin NOT NULL,
  `Protocol` float NOT NULL,
  `discriminator` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `PREFIX_xuser`
--

CREATE TABLE `PREFIX_xuser` (
  `ID` int(11) NOT NULL,
  `Email` varchar(255) COLLATE utf8_bin NOT NULL,
  `PasswordChangeTimestamp` int(11) NOT NULL,
  `TemporaryResetPassword` varchar(255) COLLATE utf8_bin NOT NULL,
  `TemporaryResetPasswordTimeout` int(11) NOT NULL,
  `LastLoginTimestamp` int(11) NOT NULL,
  `FailedLoginAttempts` int(11) NOT NULL,
  `LockTimeout` int(11) NOT NULL,
  `Activated` tinyint(1) NOT NULL,
  `CreateTimestamp` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  CONSTRAINT `FK_54C202BC11D3633A` FOREIGN KEY (`ID`) REFERENCES `PREFIX_users` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `lesson_AccessControlMatrix_users`
--

CREATE TABLE `lesson_AccessControlMatrix_users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `lesson_BusinessLayerAccessControl_users`
--

CREATE TABLE `lesson_BusinessLayerAccessControl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(30) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `cc_no` varchar(20) DEFAULT NULL,
  `cc_limit` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Table structure for table `lesson_NumericSQLInjection_weather`
--

CREATE TABLE `lesson_NumericSQLInjection_weather` (
  `station` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `state` varchar(5) NOT NULL,
  `min_temp` int(11) NOT NULL,
  `max_temp` int(11) NOT NULL,
  PRIMARY KEY (`station`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Table structure for table `lesson_XSS2_messages`
--

CREATE TABLE `lesson_XSS2_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `contest_details`
--

CREATE TABLE `contest_details` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ContestName` varchar(100) NOT NULL,
  `ContestAdmin` varchar(100) DEFAULT NULL,
  `StartTimestamp` int(11) NOT NULL,
  `EndTimestamp` int(11) NOT NULL,
  `Prize` varchar(50) DEFAULT NULL,
  `WinnerID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `WinnerID` (`WinnerID`),
  CONSTRAINT `contest_details_ibfk_1` FOREIGN KEY (`WinnerID`) REFERENCES `PREFIX_users` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;


--
-- Table structure for table `contest_challenges`
--

CREATE TABLE `contest_challenges` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ContestID` int(11) NOT NULL,
  `ChallengeName` varchar(100) NOT NULL,
  `NameToDisplay` varchar(100) NOT NULL,
  `Points` int(11) NOT NULL,
  `TotalAttempts` int(11) DEFAULT '0',
  `CompletedCount` int(11) DEFAULT '0',
  `CorrectFlag` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ChallengeName` (`ChallengeName`),
  KEY `ContestID` (`ContestID`),
  CONSTRAINT `contest_challenges_ibfk_1` FOREIGN KEY (`ContestID`) REFERENCES `contest_details` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Table structure for table `contest_submissions`
--

CREATE TABLE `contest_submissions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `ChallengeID` int(11) NOT NULL,
  `Flag` varchar(100) NOT NULL,
  `IP` char(15) DEFAULT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `UserID` (`UserID`),
  KEY `ChallengeID` (`ChallengeID`),
  CONSTRAINT `contest_submissions_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `PREFIX_users` (`ID`),
  CONSTRAINT `contest_submissions_ibfk_2` FOREIGN KEY (`ChallengeID`) REFERENCES `contest_challenges` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
