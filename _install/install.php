<?
//  @~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~@
// @                                                                                                       @
// @                                     GSP Global Support Platform                                       @
// @                                                                                                       @
//  @~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~@
// @                                                                                                       @
// @   Name  : GSP Installation    Initiale Release : 1.0   01-10-2006    Author : Jean-Claude Schopfer    @
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
// @  GSP Installation is written in PHP. GSP Installation check your config and if all is ok, give the    @
// @  right to install GSP.                                                                                @
// @                                                                                                       @
//  @~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~@
?>
<?
function verif($package,$min,$curr)
{
	echo "<tr><td>Checking for $package</td>";
	if(version_compare($curr,$min,">="))
	{
		echo "<td align='center'>$min</td><td align='center'>$curr</td><td align='center' bgcolor='#66FF66'>ok<br/>";
		return 0;
	}
	else
	{
		echo "<td align='center'>$min</td><td align='center'>$curr</td><td align='center' bgcolor='#FF6666'>ko<br/>";
		return 1;
	}
	echo "</td></tr>";
}

// Get current user (function called by the database)

function sqlite_getuser()
{
        return ("sa");
}


// Return reverse hash (function called by the database)

function sqlite_md5rev($string)
{
         return strrev(md5($string)); 
}

function randomkeys($length)
{
  $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
  for($i=0;$i<$length;$i++)
  {
   if(isset($key))
     $key .= $pattern{rand(0,35)};
   else
     $key = $pattern{rand(0,35)};
  }
  return $key;
}

$incfile="_install/config.php";
require_once($incfile);

echo "
<center>
<table>
<tr>
        <td align='center'><img src='pict/logo.png' alt='GSP'/></td>
</tr>
<tr>
        <td align='center'><img src='pict/logo_text.png' alt='Global Support Platform'/></td>
</tr>
<tr>
        <td align='center'></br>$GSP_VERSION</td>
</tr>
<tr>
</table>
";

if ($_REQUEST[ACTION] == "INSTALL_CREATE_DB")
{
	echo "<hr/><b>Installation : Selection of databases location</b><br/>";
	echo "<br/>";
	echo "<form action='index.php' method='post'>";
	echo "<input type='hidden' name='ACTION' value='INSTALL_CREATE_DB_GO'>";
	echo "<table><tr><td>";
	echo "Main database";
	echo "</td><td>";
	echo "<input type='text' size=20 name='GSP_DB' value='$GSP_DB'>";
	echo "</td><tr><tr><td>";
	echo "Inbox database";
	echo "</td><td>";
	$GSP_INBOX_DB=preg_split("/\./",$GSP_DB);
	$GSP_INBOX_DB=$GSP_INBOX_DB[0] . "_inbox.db";
	echo "<input type='text' size=20 name='GSP_INBOX_DB' value='$GSP_INBOX_DB'>";
	echo "</td></tr></table>";
	echo "<br/><br/>";
	echo "<input type='submit' value='CONFIRM'>";
	echo "</form>";
	echo "</center>";
	exit;
}

if ($_REQUEST[ACTION] == "INSTALL_CREATE_DB_GO")
{
	$GSP_DB=$_REQUEST[GSP_DB];
	$GSP_INBOX_DB=$_REQUEST[GSP_INBOX_DB];
	foreach (array ($GSP_DB,$GSP_INBOX_DB) as $DB)
	{
		if(!is_writable(dirname($DB)))
		{
			echo "<hr/><b>Installation : Verification folder permissions for $DB</b><br/>";
			echo "<br/>ERROR : " . dirname($DB) . " is not writeable";
			echo "<br/><br/>";
			echo "<a href='javascript:history.go(-1)' title='BACK'><img src='pict/back.png' border=0 alt='BACK'></a><br><br>";	
			exit;
		}
		else
		{	
			if(is_file($DB))
			{
				echo "<hr/><b>Installation : Verification if database already exist</b><br/>";
				echo "<br/>ERROR : $DB exist. Please remove it if you want continue";
				echo "<br/><br/>";
				echo "<a href='javascript:history.go(-1)' title='BACK'><img src='pict/back.png' border=0 alt='BACK'></a><br><br>"; 
				exit;
			}
		}
	}
	echo "<hr/><b>Installation : Creating database</b><br/>";
	function filetodb($db,$dir,$filename)
	{
		if($dir == "gsp/values")
		{
			$lines = file("_install/sql/$dir/$filename");

			foreach ($lines as $lines_num => $query)
			{
				$row = $db->prepare($query);
				$row->execute();
			}
		}
		else
		{
			$query=file_get_contents("_install/sql/$dir/$filename");
			$row = $db->prepare($query);
			$row->execute();
		}
	}
	function sqlinstall($db,$dir)
	{
		echo "<tr><td>Installing</td><td>$dir</tr>";
		$files = scandir("_install/sql/" . $dir,1);
		foreach($files as $filename)
		{
			if ($filename != "." && $filename != "..")
         		{
				filetodb($db,$dir,$filename);
			}
        	}
	}

	$db = new PDO("sqlite:$GSP_DB");
	$db->sqliteCreateFunction('getuser','sqlite_getuser');
	$db->sqliteCreateFunction('md5rev','sqlite_md5rev');
	$db_inbox = new PDO("sqlite:$GSP_INBOX_DB");

	echo "<br/><table><tr><td>";
	ob_implicit_flush();
	sqlinstall($db,"gsp/tables");
	sqlinstall($db,"gsp/indexes");
	sqlinstall($db,"gsp/views/part1");
	sqlinstall($db,"gsp/views/part2");
	sqlinstall($db,"gsp/triggers");
	sqlinstall($db,"gsp/values");
	sqlinstall($db_inbox,"gsp_inbox/tables");
	sqlinstall($db_inbox,"gsp_inbox/indexes");
	sqlinstall($db_inbox,"gsp_inbox/triggers");
	echo "</td></tr></table></br>";

        $query="UPDATE config_inbox set inbox_db_path='$GSP_INBOX_DB' where inbox_code = 'install'";
        $row = $db->prepare($query);
        $row->execute();

	echo "<form action='index.php' method='post'>";
	echo "<input type='hidden' name='ACTION' value='INSTALL_CREATE_USER'>";
	echo "<input type='hidden' name='GSP_DB' value='$GSP_DB'>";
	echo "<input type='hidden' name='GSP_INBOX_DB' value='$GSP_INBOX_DB'>";
        echo "<input type='submit' value='CONTINUE'>";
        echo "</form>";
        echo "</center>";
	exit;
}

if ($_REQUEST[ACTION] == "INSTALL_CREATE_USER")
{
	$GSP_DB=$_REQUEST[GSP_DB];
	$GSP_INBOX_DB=$_REQUEST[GSP_INBOX_DB];

        echo "<hr/><b>Installation : User creation</b><br/>";
        echo "<br/>";
        echo "<form action='index.php' method='post'>";
        echo "<input type='hidden' name='ACTION' value='INSTALL_CREATE_USER_GO'>";
	echo "<input type='hidden' name='GSP_DB' value='$GSP_DB'>";
        echo "<input type='hidden' name='GSP_INBOX_DB' value='$GSP_INBOX_DB'>";
        echo "<table><tr><td>"; 
        echo "User code";
        echo "</td><td>";
        echo "<input type='text' size=20 name='GSP_USER_CODE'>";
        echo "</td><tr><tr><td>";
        echo "Password";
        echo "</td><td>";
        echo "<input type='password' size=20 name='GSP_USER_PASS'>";
        echo "</td></tr></table>";
        echo "<br/><br/>";
        echo "<input type='submit' value='CONFIRM'>";
        echo "</form>";
        echo "</center>";
        exit;
}

if ($_REQUEST[ACTION] == "INSTALL_CREATE_USER_GO")
{
        $GSP_DB=$_REQUEST[GSP_DB];
	$GSP_INBOX_DB=$_REQUEST[GSP_INBOX_DB];

	echo "<hr/><b>Installation : User creation</b><br/>";
	echo "<br/>";

	$db = new PDO("sqlite:$GSP_DB");
	$db->sqliteCreateFunction('getuser','sqlite_getuser');
	$db->sqliteCreateFunction('md5rev','sqlite_md5rev');

	$query="UPDATE user set code=:CODE, password_c=:PASS where code = 'install'";
	$row = $db->prepare($query);
	$row->bindParam(':CODE',$_POST[GSP_USER_CODE],PDO::PARAM_STR);
	$row->bindParam(':PASS',$_POST[GSP_USER_PASS],PDO::PARAM_STR);
	$row->execute();

	$GSP_SA_PASS=randomkeys(40);
	$query="UPDATE user set password_c='$GSP_SA_PASS' where code = 'sa'";
	$row = $db->prepare($query);
	$row->execute();

	echo "OK<br/><br/>";

        echo "<form action='index.php' method='post'>";
        echo "<input type='hidden' name='ACTION' value='INSTALL_WRITE_CONFIG'>";
        echo "<input type='hidden' name='GSP_DB' value='$GSP_DB'>";
        echo "<input type='submit' value='CONTINUE'>";
        echo "</form>";
        echo "</center>";
	exit;
}

if ($_REQUEST[ACTION] == "INSTALL_WRITE_CONFIG")
{
	$gspfolder=dirname($_SERVER["SCRIPT_FILENAME"]);
	if(!is_writable("$gspfolder"))
	{
                echo "<hr/><b>Installation : Try to write configuration file</b><br/>";
                echo "<br/>ERROR : <b>config.php</b> is not writeable.<br/><br/>Please temporary add write permission to $gspfolder<b>";
                echo "<br/><br/>";
                echo "<a href='javascript:history.go(-1)' title='BACK'><img src='pict/back.png' border=0 alt='BACK'></a><br><br>";
	}		
	else
        {
		echo "<hr/><b>Installation : Writing configuration</b><br/>";
		file_put_contents("config.php",str_replace($GSP_DB,$_REQUEST[GSP_DB],file_get_contents($incfile)));
		echo "<br/>Installation is finished :)<br/></br>";
		if(!is_writeable("$LOGFILE"))
		{
			 echo "WARNING : GSP log <b>$LOGFILE</b> is not writeable. Please add write permission to it<br/><br/>";
		}
		echo "<form action='index.php' method='post'>";
		echo "<input type='submit' value='START GSP'>";
		echo "</form>";
		echo "</center>";
        }
	exit;
}


echo "<hr/><b>Checking configuration</b><br/>";

$conn = new PDO('sqlite::memory:');

echo "</br><table border=1 cellpadding=3 cellspacing=3>";
echo "<th></th><th>Min version</th><th>Your config</th><th>Result</th>";

$minversion['php']="5.1.1";
$minversion['pdo']="1.0.3";
$minversion['pdo_sqlite']="1.0.1";
$minversion['sqlite']="3.3.0";
$minversion['imap']="loaded";

$versionsqlite=$conn->getAttribute(constant("PDO::ATTR_CLIENT_VERSION"));
(extension_loaded('imap') ? $versionimap = "loaded" : $versionimap = "not loaded");

$mandatory_verif[]=verif("PHP",$minversion['php'],phpversion());
$mandatory_verif[]=verif("PDO extension",$minversion['pdo'],phpversion('pdo'));
$mandatory_verif[]=verif("pdo_sqlite extension",$minversion['pdo_sqlite'],phpversion('pdo_sqlite'));
$mandatory_verif[]=verif("PDO : Sqlite version",$minversion['sqlite'],$versionsqlite);
$optional_verif[]=verif("imap extension",$minversion['imap'],$versionimap);

echo "</table></br>";
if(array_sum($mandatory_verif) > 0)
{
	echo "Configuration checking failed<br/>You cannot install GSP";
}
else
{	
	if(array_sum($optional_verif) > 0)
	{
		echo "<b>Warning</b> : imap extension is not loaded. You have to retrieve incoming mails yourself<br/></br>";
	}
	echo "Congratulation :)<br/>Press Install to begin installation<br/><br/>";
	echo "<form action='index.php' method='post'>";
	echo "<input type='hidden' name='ACTION' value='INSTALL_CREATE_DB'>";
	echo "<input type='submit' value='INSTALL'>";
	echo "</form>";
}
?>
