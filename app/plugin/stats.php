<?php
class StatsPlugin extends JPlugin
{
	protected function setupDB()
	{
		jf::SQL("CREATE TABLE IF NOT EXISTS `{$this->TablePrefix()}stats` (
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
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1");
	}
	function __construct()
	{
		$this->setupDB();
	}
	function Insert()
	{
		if (jf::$RunMode->IsCLI()) return false;
		$res=jf::SQL("INSERT INTO {$this->TablePrefix()}stats (UserID,SessionID,Timestamp,Page,Query,IP,Host,Protocol,UserAgent) VALUES
			(?,?,?,?,?,?,?,?,?)"
			,jf::CurrentUser()?:0,jf::$Session->SessionID(),jf::time(),HttpRequest::URI(),HttpRequest::QueryString(),HttpRequest::IP(),
				HttpRequest::Host(),HttpRequest::Protocol(),HttpRequest::UserAgent());
		
		return $res;	
		
	}
	
}