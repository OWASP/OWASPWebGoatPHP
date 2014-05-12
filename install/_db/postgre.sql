-- phpMyAdmin SQL Dump
-- version 2.11.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 31, 2009 at 01:06 AM
-- Server version: 5.0.67
-- PHP Version: 5.2.6
-- jFramework Version 2.7.5
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `jf`
--

-- --------------------------------------------------------

--
-- Table structure for table `jf_logs`
--

CREATE TABLE NOT EXISTS jf_logs (
  ID SERIAL,
  Subject varchar(256) NOT NULL,
  Data text NOT NULL,
  Severity integer NOT NULL,
  UserID integer default NULL,
  SessionID varchar(64) ,
  Timestamp integer NOT NULL,
  CONSTRAINT log_ID PRIMARY KEY  (ID)
) ;
--
-- Dumping data for table `jf_logs`
--


-- --------------------------------------------------------

--
-- Table structure for table `jf_options`
--

CREATE TABLE jf_options (
  UserID SERIAL NOT NULL,
  Name varchar(200) NOT NULL,
  Value text NOT NULL,
  Expiration integer NOT NULL,
  CONSTRAINT userid_name PRIMARY KEY  (UserID,Name)
   --KEY Expiration (Expiration)
);
CREATE INDEX Expiration ON jf_options (Expiration);

-- --------------------------------------------------------

--
-- Table structure for table `jf_rbac_permissions`
--

CREATE TABLE jf_rbac_permissions (
  ID SERIAL NOT NULL,
  Lft integer NOT NULL,
  Rght integer NOT NULL,
  Title char(64) NOT NULL,
  Description text NOT NULL,
  CONSTRAINT jf_rbac_permissions_id PRIMARY KEY  (ID)
) ;
CREATE INDEX jf_rbac_permissions_title ON jf_rbac_permissions (Title);
CREATE INDEX jf_rbac_permissions_left ON jf_rbac_permissions (Lft);
CREATE INDEX jf_rbac_permissions_right ON jf_rbac_permissions (Rght);
--
-- Dumping data for table jf_rbac_permissions
--

INSERT INTO jf_rbac_permissions (ID, Lft, Rght, Title, Description) VALUES
(0, 1, 2, 'root', 'root');

-- --------------------------------------------------------

--
-- Table structure for table jf_rbac_rolepermissions
--

CREATE TABLE jf_rbac_rolepermissions (
  RoleID integer NOT NULL,
  PermissionID integer NOT NULL,
  AssignmentDate integer NOT NULL,
  CONSTRAINT jf_rbac_rolepermissions_pk PRIMARY KEY  (RoleID,PermissionID)
) ;

--
-- Dumping data for table jf_rbac_rolepermissions
--

INSERT INTO jf_rbac_rolepermissions (RoleID, PermissionID, AssignmentDate) VALUES
(0, 0, 2009);

-- --------------------------------------------------------

--
-- Table structure for table jf_rbac_roles
--

CREATE TABLE jf_rbac_roles (
  ID SERIAL NOT NULL ,
  Lft integer NOT NULL,
  Rght integer NOT NULL,
  Title varchar(128) NOT NULL,
  Description text NOT NULL,
  CONSTRAINT jf_rbac_roles_id PRIMARY KEY  (ID)
  );
CREATE INDEX jf_rbac_roles_title ON jf_rbac_roles (Title);
CREATE INDEX jf_rbac_roles_left ON jf_rbac_roles (Lft);
CREATE INDEX jf_rbac_roles_right ON jf_rbac_roles (Rght);
-- Dumping data for table jf_rbac_roles
--

INSERT INTO jf_rbac_roles (ID, Lft, Rght, Title, Description) VALUES
(0, 1, 2, 'root', 'root');

-- --------------------------------------------------------

--
-- Table structure for table jf_rbac_userroles
--

CREATE TABLE jf_rbac_userroles (
  UserID integer NOT NULL,
  RoleID integer NOT NULL,
  AssignmentDate integer NOT NULL,
  CONSTRAINT jf_rbac_userroles_pk PRIMARY KEY  (UserID,RoleID)
) ;

--
-- Dumping data for table jf_rbac_userroles
--

INSERT INTO jf_rbac_userroles (UserID, RoleID, AssignmentDate) VALUES
(1, 0, 2009);

-- --------------------------------------------------------

--
-- Table structure for table `jf_sessions`
--


CREATE TABLE jf_sessions (
  ID SERIAL NOT NULL ,
  SessionID char(64) NOT NULL,
  UserID integer NOT NULL,
  IP char(15) NOT NULL,
  LoginDate integer NOT NULL,
  LastAccess integer NOT NULL,
  AccessCount integer NOT NULL DEFAULT '1',
  CurrentRequest varchar(1024) NOT NULL,
  CONSTRAINT jf_session_ID PRIMARY KEY (ID)
   
);
CREATE INDEX UserID ON jf_sessions (UserID);
CREATE UNIQUE INDEX SessionID ON jf_sessions (SessionID);
-- --------------------------------------------------------

--
-- Table structure for table `jf_users`
--

CREATE TABLE jf_users (
  ID SERIAL NOT NULL ,
  Username char(128) NOT NULL,
  Password char(128) NOT NULL,
  CONSTRAINT jf_users_id PRIMARY KEY  (ID)
  
);
CREATE UNIQUE INDEX jf_users_username ON jf_users (Username);
--
-- Dumping data for table `jf_users`
--

INSERT INTO jf_users (ID, Username, Password) VALUES
(1, 'root', '119ba00fd73711a09fa82177f48f4e4ac32b1e1d73925fc4f654851b617b2a96fd5a5b3095d59b59e5cdfd71312ba3f61195414758478feced69544447360003');


--
-- Table structure for table `jf_i18n`
--

CREATE TABLE jf_i18n (
  Language varchar(32) NOT NULL,
  Phrase text NOT NULL,
  Translation text NOT NULL,
  TimeAdded integer NOT NULL,
  TimeModified integer NOT NULL,
  CONSTRAINT jf_i18n_pk PRIMARY KEY  (Language,Phrase)
) ;

