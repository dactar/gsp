<?
//  @~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~@
// @                                                                                                       @
// @                                     GSP Global Support Platform                                       @
// @                                                                                                       @
//  @~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~@
// @                                                                                                       @
// @   Name  : GSP Access Control    Initiale Release : 1.0   03-10-2006    Author : Jean-Claude Schopfer  @
// @                                                                                                       @
//  @~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~@
// @                                                                                                       @
// @   Changes : Version  | When       | Who  |  What                                                      @
// @             ---------------------------------------------------------------------------------------   @
// @                      |            |      |                                                            @
// @                      |            |      |                                                            @
// @                                                                                                       @
//  @~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~@
// @                                                                                                       @
// @  GSP Access Control is written in PHP.  The script call the login script if the user is not logged,   @
// @  open the database connection and call the install or menu scripts depend of the the situation        @
// @                                                                                                       @
//  @~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~@
?>
<? 
ini_set("magic_quotes_runtime",0);
date_default_timezone_set('Europe/Zurich');

error_reporting(E_ERROR);

// Prevent IE to cache
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sun, 31 Dec 1995 00:00:00 GMT");

// Test the state of GSP installation 
// and call installation script if needed

$incfile="config.php";
if (file_exists($incfile))
{
	try
	{
		$conn = new PDO('sqlite::memory:');
		require_once($incfile);
	}
	catch (PDOException $e)
	{
		echo "ERROR : There's no (more ?) PDO SQLITE driver on this system. GSP cannot be used.";
		exit;
	}
}
else
{
	$incfile="_install/install.php";
	if (file_exists($incfile))
	{
		if ($_REQUEST["MODL"] != "IMAP")
		{
			require_once($incfile);
		}
		exit;
	}
	else
	{
		echo "ERROR : GSP is broken (config file and install script not found)";
		exit;
	}
}

// Reset user cookies if the user logged out

if ($_REQUEST["MODL"] == "LOGO")
{
	setcookie("GSP_USER",FALSE,FALSE,"/");
	setcookie("GSP_PASS",FALSE,FALSE,"/");
}
else
{
	if (($_COOKIE["GSP_USER"] != "" && $_COOKIE["GSP_PASS"]) || ($_POST["USER"] != "" && $_POST["PASS"] != ""))
	{
		// The user is already logged

		if ($_COOKIE["GSP_USER"] != "")
		{
			$USER=$_COOKIE["GSP_USER"] ;
			$PASS=$_COOKIE["GSP_PASS"] ;
		}

		// The user just logged in

		if ($_POST["USER"] != "")
		{
			$USER=$_POST["USER"];
			$PASS=$_POST["PASS"];
		}

		// Reverse hash user password

		$MD5REVPASS=strrev(md5($PASS));

		// Get current user (function called by the database)

		function sqlite_getuser()
		{
			global $USER;
			return ("$USER");
		}

                // Return hash (function called by the database)

                function sqlite_md5($string)
                {
                        return md5($string);
                }

                // Return reverse hash (function called by the database)

                function sqlite_md5rev($string)
                {
                        return strrev(md5($string));
                }

		// Return string size (function called by the database)

                function sqlite_strlen($string)
                {
                        return strlen($string);
                }

		// Open connection to the database

		if (file_exists($GSP_DB))
		{
			try
			{
				$db = new PDO("sqlite:$GSP_DB");
			}
			catch (PDOException $e)
			{
				echo "ERROR : GSP is broken (database file <tt>$GSP_DB</tt> found but cannot be opened)";
				exit;
			}
			$db->sqliteCreateFunction('getuser','sqlite_getuser');
			$db->sqliteCreateFunction('md5','sqlite_md5');
			$db->sqliteCreateFunction('md5rev','sqlite_md5rev');
			$db->sqliteCreateFunction('strlen','sqlite_strlen');
			$query="select password_c from user where code = getuser()";
			$row = $db->query($query);
			$result=$row->fetch(PDO::FETCH_ASSOC);
			$row->closeCursor();
			
		}
		else
		{
	                echo "ERROR : GSP is broken (database file <tt>$GSP_DB</tt> not found)";
       		        exit;
		}

		// Set user cookies and call the main menu script if access is granted
		
		if ($result[password_c] == $MD5REVPASS)
		{
			setcookie("GSP_USER","$USER",FALSE,"/");
			setcookie("GSP_PASS","$PASS",FALSE,"/");

			include("include/menu.php");
			exit;
		}
		else
		{
			setcookie("GSP_USER",FALSE,FALSE,"/");
			setcookie("GSP_PASS",FALSE,FALSE,"/");

			$ERROR="<div class='ERR'>ERROR ! LOGIN INCORRECT !</div>";
		}
	}
}
include("include/login.php");
?>
