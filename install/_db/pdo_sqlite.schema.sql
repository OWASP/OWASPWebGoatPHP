BEGIN TRANSACTION;

--
-- Table structure for table `PREFIX_logs`
--

CREATE TABLE IF NOT EXISTS `PREFIX_logs` (
  `ID` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `Subject` varchar(256)   NOT NULL,
  `Data` text   NOT NULL,
  `Severity` INTEGER NOT NULL,
  `UserID` INTEGER default NULL,
  `SessionID` varchar(64)   DEFAULT NULL,
  `Timestamp` INTEGER NOT NULL
);

--
-- AutoIncrement=1
--

delete from sqlite_sequence where name='PREFIX_logs';

-- --------------------------------------------------------

--
-- Table structure for table `PREFIX_options`
--

CREATE TABLE IF NOT EXISTS `PREFIX_options` (
  `UserID` INTEGER NOT NULL,
  `Name` varchar(200)   NOT NULL,
  `Value` text   NOT NULL,
  `Expiration` INTEGER NOT NULL,
  PRIMARY KEY  (`UserID`,`Name`)
);

-- --------------------------------------------------------

--
-- Table structure for table `PREFIX_rbac_permissions`
--

CREATE TABLE IF NOT EXISTS `PREFIX_rbac_permissions` (
  `ID` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `Lft` INTEGER NOT NULL,
  `Rght` INTEGER NOT NULL,
  `Title` char(64) NOT NULL,
  `Description` text NOT NULL
);

--
-- AutoIncrement=1
--

delete from sqlite_sequence where name='PREFIX_rbac_permissions';

-- --------------------------------------------------------

--
-- Table structure for table `PREFIX_rbac_rolepermissions`
--

CREATE TABLE IF NOT EXISTS `PREFIX_rbac_rolepermissions` (
  `RoleID` INTEGER NOT NULL,
  `PermissionID` INTEGER NOT NULL,
  `AssignmentDate` INTEGER NOT NULL,
  PRIMARY KEY  (`RoleID`,`PermissionID`)
);

-- --------------------------------------------------------

--
-- Table structure for table `PREFIX_rbac_roles`
--

CREATE TABLE IF NOT EXISTS `PREFIX_rbac_roles` (
  `ID` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `Lft` INTEGER NOT NULL,
  `Rght` INTEGER NOT NULL,
  `Title` varchar(128) NOT NULL,
  `Description` text NOT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table `PREFIX_rbac_userroles`
--

CREATE TABLE IF NOT EXISTS `PREFIX_rbac_userroles` (
  `UserID` INTEGER NOT NULL,
  `RoleID` INTEGER NOT NULL,
  `AssignmentDate` INTEGER NOT NULL,
  PRIMARY KEY  (`UserID`,`RoleID`)
);

-- --------------------------------------------------------

--
-- Table structure for table `PREFIX_sessions`
--

CREATE TABLE IF NOT EXISTS `PREFIX_session` (
  `ID` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `SessionID` char(64)   NOT NULL,
  `UserID` INTEGER NOT NULL,
  `IP` char(15)   NOT NULL,
  `LoginDate` INTEGER NOT NULL,
  `LastAccess` INTEGER NOT NULL,
  `AccessCount` INTEGER NOT NULL DEFAULT '1',
  `CurrentRequest` char(1024)   NOT NULL,
  UNIQUE (`SessionID`)
);

--
-- AutoIncrement=1
--

delete from sqlite_sequence where name='PREFIX_session';

-- --------------------------------------------------------

--
-- Table structure for table `PREFIX_users`
--

CREATE TABLE IF NOT EXISTS `PREFIX_users` (
  `ID` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `Username` char(128)   NOT NULL,
  `Password` char(128)   NOT NULL,
  `Salt` varchar(128)   NOT NULL,
  `Protocol` float   NOT NULL,
  UNIQUE (`Username`)
);
--
-- AutoIncrement=1
--

delete from sqlite_sequence where name='PREFIX_users';

-- --------------------------------------------------------

--
-- Table structure for table `PREFIX_xusers`
--

CREATE TABLE IF NOT EXISTS `PREFIX_xuser` (
  `ID` INTEGER NOT NULL PRIMARY KEY,
  `Email` varchar(256)   NOT NULL,
  `PasswordChangeTimestamp` INTEGER NOT NULL DEFAULT '0',
  `TemporaryResetPassword` varchar(256)   NOT NULL DEFAULT '',
  `TemporaryResetPasswordTimeout` INTEGER NOT NULL DEFAULT '0',
  `LastLoginTimestamp` INTEGER NOT NULL DEFAULT '0',
  `FailedLoginAttempts` INTEGER NOT NULL DEFAULT '0',
  `LockTimeout` int(16) NOT NULL DEFAULT '0',
  `Activated` INTEGER NOT NULL DEFAULT '0',
  `CreateTimestamp` INTEGER NOT NULL,
  UNIQUE (`Email`)
);

COMMIT;