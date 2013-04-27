<?
//  @~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~@
// @                                                                                                       @
// @                                     GSP Global Support Platform                                       @
// @                                                                                                       @
//  @~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~@
// @                                                                                                       @
// @   Name  : GSP Menu Dispatcher   Initiale Release : 1.0   30-06-2006    Author : Jean-Claude Schopfer  @
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
// @  GSP Menu Dispatcher is written in PHP.                                                               @
// @                                                                                                       @
//  @~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~@
?>
<?
if ($_REQUEST[MODL] == "GETA") {include("include/getatt.php"); exit;}
if ($_REQUEST[MODL] == "GETX") {include("include/getxml.php"); exit;}

// Automatic classes and functions loading
$dir="classes/model" ;   include("include/load_code.php");
$dir="classes/view" ;   include("include/load_code.php");
$dir="classes/ctrl" ;   include("include/load_code.php");
$dir="functions" ; include("include/load_code.php");

// Inbox database path
$GSP_INBOX_DB_ID=return_query($db,"select id from config_inbox where automatic_f = 1 and default_f = 1 and active_f = 1");
$GSP_INBOX_DB_PATH=return_query($db,"select inbox_db_path from config_inbox where default_f = 1 and active_f = 1");
$GSP_INBOX_DB_TABLE=return_query($db,"select inbox_db_table from config_inbox where default_f = 1 and active_f = 1");

if ($_REQUEST[MODL] == "AUSR" && ($_REQUEST[ACTION] == "Sauvegarder" || $_REQUEST[ACTION] == "Valider" || $_REQUEST[ACTION] == "getxml")) {include("include/menu/user.php"); exit;}
if ($_REQUEST[MODL] == "PACK" && ($_REQUEST[ACTION] == "Sauvegarder" || $_REQUEST[ACTION] == "Valider" || $_REQUEST[ACTION] == "Supprimer")) {include("include/menu/packaging.php"); exit;}
if ($_REQUEST[MODL] == "CONT" && ($_REQUEST[ACTION] == "Sauvegarder" || $_REQUEST[ACTION] == "Valider" || $_REQUEST[ACTION] == "Supprimer")) {include("include/menu/contact.php"); exit;}
if ($_REQUEST[MODL] == "DICT" && ($_REQUEST[ACTION] == "Sauvegarder" || $_REQUEST[ACTION] == "Valider" || $_REQUEST[ACTION] == "Supprimer")) {include("include/menu/dict.php"); exit;}

if ($_REQUEST[MODL] == "PUTX") {include("include/putajax.php"); exit;}
if ($_POST[EXPORT_PDF] != "") {create_pdf($db,"event_open");exit;}

$LOGTIME_START=date("d-m-Y H:i:s");

$web_page = new web_page("GSP Global Support Platform");

$web_page->add_css("css/gsp.css");
$web_page->add_css("ext/dhtmlx/css/dhtmlxtree.css");
$web_page->add_css("ext/dhtmlx/css/dhtmlxform_dhx_skyblue.css");

$web_page->add_meta("Content-Script-Type","text/javascript");

if($_REQUEST[MODL] == "RFSH") { include("include/refresh.php");           $LOGTIME_END=date("H:i:s"); writelog($LOGTIME_START,$LOGTIME_END,$_REQUEST[MODL],$USER,$RESULT); exit; } 
if($_REQUEST[MODL] == "IMAP") { $RESULT=include("include/imap.php");      $LOGTIME_END=date("H:i:s"); writelog($LOGTIME_START,$LOGTIME_END,$_REQUEST[MODL],$USER,$RESULT); exit; }
if($_REQUEST[MODL] == "OPNE" && $_REQUEST[ACTION] != "getxml" && $_REQUEST[ACTION] != "Valider") { $RESULT=include("include/menu/main.php"); $LOGTIME_END=date("H:i:s"); writelog($LOGTIME_START,$LOGTIME_END,$_REQUEST[MODL],$USER,$RESULT); exit; }
if($_REQUEST[MODL] == "OPNM") { $RESULT=include("include/menu/main.php"); $LOGTIME_END=date("H:i:s"); writelog($LOGTIME_START,$LOGTIME_END,$_REQUEST[MODL],$USER,$RESULT); exit; }

$web_page->add_jsfile("ext/dhtmlx/dhtmlxcommon.js");

// Module Dispatcher

switch ($_REQUEST[MODL])
{
	case "":     include("include/menu/main.php"); break;		// Main page
	case "MAIN": include("include/menu/main.php"); break;		// Main page
	case "LOGI": include("include/menu/main.php"); break;		// Main page when Login
	case "DICT": include("include/menu/dict.php"); break;		// Attributes administration
	case "AUSR": include("include/menu/user.php"); break;		// Users administration
	case "PACK": include("include/menu/packaging.php"); break;      // Packaging administration
	case "SEGM": new module("SEGM","$_REQUEST[ACTION]"); break;     // Segmentation administration
	case "CONT": include("include/menu/contact.php"); break;	// Contacts administration
	case "EVNT": include("include/menu/event.php"); break;		// Events management
	case "IBOX": include("include/menu/inbox.php"); break;		// Inbox management
	case "OPNE": new module("OPNE","$_REQUEST[ACTION]"); break;     // Ouvrir événement en XML
	case "STAT": new module("STAT","$_REQUEST[ACTION]"); break;     // Statistiques
	case "CSLA": new module("CSLA","$_REQUEST[ACTION]"); break;	// Configuration SLA
	case "TSLA": new module("TSLA","$_REQUEST[ACTION]"); break;     // Configuration des temps SLA
	case "VSLA": new module("VSLA","$_REQUEST[ACTION]"); break;     // VÃ©rification des temps SLA
        case "ESLA": new module("ESLA","$_REQUEST[ACTION]"); break;     // Liste des événements pour SLA
	case "CPLG": new module("CPLG","$_REQUEST[ACTION]"); break;     // Configuration des plugins
	case "LPLG": new module("LPLG","$_REQUEST[ACTION]"); break;     // Lancement d'un plugin
	case "JRNL": new module("JRNL","$_REQUEST[ACTION]"); break;     // Lancement du journal
	case "CPWD": new module("CPWD","$_REQUEST[ACTION]"); break;     // Changement de mot de passe
	case "CBOX": new module("CBOX","$_REQUEST[ACTION]"); break;     // Inbox config administration
	case "CVNT": include("include/menu/create_event.php"); break;	// Event creation
	case "DOCU": include("include/menu/docu.php"); break;		// End-user Documentation
	case "ABOU": include("include/menu/about.php"); break;		// About page
	default : echo "Module $_REQUEST[MODL] inconnu"; break;		// Error : Module unknown
}

$LOGTIME_END=date("H:i:s");
writelog($LOGTIME_START,$LOGTIME_END,$_REQUEST[MODL],$USER,$RESULT);
?>
</body>
</html>
