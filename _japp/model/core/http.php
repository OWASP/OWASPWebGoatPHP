<?php
/**
 * HttpRequest class
 * gives access to different parts of HTTP request parameters
 * @version 2.0
 */

namespace jf;
class HttpRequest
{
	/**
	 * Returns IP address of client
	 * returns ::127 for localhost
	 * @return string IP
	 */
	static function IP()
	{
		if (jf::$RunMode->IsCLI())
			return "127.0.0.1";
// 		return (getenv ( "HTTP_X_FORWARDED_FOR" )) ? getenv ( "HTTP_X_FORWARDED_FOR" ) : getenv ( "REMOTE_ADDR" );
		return $_SERVER['REMOTE_ADDR'];
	}
	/**
	 * Returns the current URL of the browser
	 *
	 * @return String URL
	 */
	static function URL ($QueryString=true)
	{
		if (jf::$RunMode->IsCLI())
			return null;
		if ($QueryString && self::QueryString() )
			return (self::Protocol()."://".self::ServerName().self::PortReadable().self::RequestURI()."?".self::QueryString());
		else
			return (self::Protocol()."://".self::ServerName().self::PortReadable().self::RequestURI());
	}	
	/**
	 * Returns the client User Agent
	 * @return string
	 */	
	static function UserAgent()
	{
		return $_SERVER['HTTP_USER_AGENT'];
	}	
	/**
	 * Port of client connection
	 *
	 * @return String Port
	 */
	static function Port ()
	{
		if (jf::$RunMode->IsCLI())
			return null;
		return isset($_SERVER['SERVER_PORT'])?$_SERVER['SERVER_PORT']:"";
	}
	static function PortReadable()
	{
		$port=self::Port();
		if ($port=="80" && strtolower(self::Protocol())=="http")
		$port="";
		else if ($port=="443" && strtolower(self::Protocol())=="https")
		$port="";
		else
		$port=":".$port;
	}
	/**
	 * Protocol of client connection, HTTP or HTTPS
	 *
	 * @return String Protocol
	 */
	static function Protocol ()
	{
		if (jf::$RunMode->IsCLI())
			return "cli";
		if (isset($_SERVER['HTTPS']))
		$x = $_SERVER['HTTPS'];
		else
			$x="";
		if ($x=="off" or $x=="")
		return "http";
		else
		return "https";
	}
	/**
	 * Request Path, e.g http://somesite.com/this/is/the/request/path/index.php
	 *
	 * @return string Path
	 */
	static function Path ()
	{
		if (jf::$RunMode->IsCLI())
		return jf::$Request;
		$RequestURI=$_SERVER['REQUEST_URI'];
		if (strpos($RequestURI,"?")!==false)
			$URIwithoutQuery=substr($RequestURI,0,strpos($RequestURI,"?"));
		else
			$URIwithoutQuery=$RequestURI;
		
		if (jf::$Request)
			$Path=substr($URIwithoutQuery,0,-strlen(jf::$Request));
		else
			$Path=$URIwithoutQuery;
		return $Path;
	}
	/**
	 * Contains Path+RequestFile+QueryString formatted as the browser URL
	 * @return string URI
	 */
	static function URI()
	{
		if (jf::$RunMode->IsCLI())
			return null;
		if (isset($_SERVER['REDIRECT_URL']))
		return $_SERVER["REDIRECT_URL"];
		else
		return $_SERVER['REQUEST_URI'];
	}

	/**
	 * HTTP Host, aka Domain name
	 *
	 * @return string Host
	 */
	static function Host ()
	{
		if (jf::$RunMode->IsCLI())
			return "localhost";
		if (isset($_SERVER['HTTP_HOST']))
			return $_SERVER['HTTP_HOST'];
		else
			return "";
	}
	static function ServerName()
	{
		if (isset($_SERVER['SERVER_NAME']))
			return $_SERVER['SERVER_NAME'];
		else
			return "";
		
	}
	/**
	 * Request method, either GET/POST
	 *
	 * @return string RequestMethod
	 */
	static function Method ()
	{
		if (jf::$RunMode->IsCLI())
			return "get";
		return $_SERVER['REQUEST_METHOD'];
	}
	/**
	 * Requested file, the last part in URL between last / and ?
	 *
	 * @return string RequestFile
	 */
	static function File ()
	{
		if (jf::$RunMode->IsCLI())
			return null;
		if (isset($_SERVER['REDIRECT_QUERY_STRING']))
		{
			$a=explode("&", $_SERVER['REDIRECT_QUERY_STRING']);
			$x = array_shift($a);
			$x = explode("=", $x);
			$x=$x[1];
			return $x;
		}
		else
		return ""; //index.php
	}
	/**
	 * Query String, the last part in url after ? (not including jFramework request)
	 *
	 * @return String QueryString
	 */
	static function QueryString ()
	{
		if (jf::$RunMode->IsCLI())
			return http_build_query($_GET);
		if (isset($_SERVER['REDIRECT_QUERY_STRING']))
		{
			$a=explode("&", $_SERVER['REDIRECT_QUERY_STRING']);
			$x = array_shift($a);
			return substr($_SERVER['REDIRECT_QUERY_STRING'], strlen($x) + 1);
		}
		else
		return isset($_SERVER['QUERY_STRING'])?$_SERVER['QUERY_STRING']:"";
	}
	/**
	 * Root of website without trailing slash
	 *
	 * @return string SiteRoot
	 */
	static function Root ()
	{
		if (jf::$RunMode->IsCLI())
			return null;
		elseif (defined("jfembed"))
			return jf::$BaseRequest;
		$x=self::Protocol() . "://" . self::Host() . self::PortReadable() . self::Path();
		return substr($x,0,-strlen(jf::$BaseRequest)-1);
	}

	static function Request()
	{
		if (jf::$RunMode->IsCLI())
			return jf::$Request;
		return self::RequestFile() . (self::QueryString() ? "?" . self::QueryString() : "");
	}
}
?>