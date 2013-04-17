<?
class plugin_test
{
	const PLUGIN_NAME = "Test";
	const PLUGIN_VERSION = "1.0";

	function __construct()
	{
		return "Ce plugin est un plugin de test";
	}

	function display_actions()
	{
		echo "Plugin test OK !";
	}
}
?>
