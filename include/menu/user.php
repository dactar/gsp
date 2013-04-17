<?
global $db;
$admin = return_query($db,"SELECT admin_f from user where code = '$_COOKIE[GSP_USER]'");

if (isset($web_page))
{
	$web_page->render();
}

function treesubaction($db,$group_dict_id,$parent_id)
{
        $query="SELECT * from contact_vw where group_dict_id = $group_dict_id;";
        $subrow = $db->query($query);
        while ($subresult=$subrow->fetch(PDO::FETCH_ASSOC))
        {
                $jsfill="javascript:fill_subform(\"$subresult[id]\",\"$subresult[code]\",\"$subresult[name]\",\"$subresult[group_dict_id]\",\"$subresult[group_code]\",\"$subresult[organisation_code]\",\"$subresult[active_f]\",\"$subresult[phone]\",\"$subresult[email]\",\"$subresult[last_user_code] / $subresult[last_modif_d]\")";
                $jsfill=str_replace("\"","'",str_replace("'","\\\'",$jsfill));
                print ",\n";
                $end=$space . "        ],\n";
                $oldspace=$space;
                $space=$space . "        ";
                print $space . "        [null, \"$subresult[code]\",\"$jsfill\",null, \"$subresult[description]\"";
                $space = $oldspace;
                print $end;
        }
}

if ($_REQUEST[ACTION] == "Créer utilisateur" )
{
	echo "<b>Création d'un utilisateur</b><br>";
	$appl_user = new user();
	echo "<br><center><a href='index.php?MODL=$_REQUEST[MODL]' alt='RETOUR'><img src='pict/back.png' border=0></a></center><br>";
	$appl_user->create_prepare();
}

if ($_REQUEST[ACTION] == "Sauvegarder" )
{
	$appl_user = new user();
	$appl_user->create_submit();
}

if ($_REQUEST[ACTION] == "Modification")
{
	echo "<b>Modification d'un utilisateur</b><br>";
	$appl_user = new user();
	$appl_user->display_actions($admin);
	echo "<br>";
	$appl_user->update_prepare();
}

if ($_REQUEST[ACTION] == "Valider")
{
	$appl_user = new user();
	$appl_user->update_submit();
}

if ($_REQUEST[ACTION] == "Activer")
{
	$appl_user = new user();
	$appl_user->enable();
}

if ($_REQUEST[ACTION] == "Désactiver")
{
	$appl_user = new user();
	$appl_user->disable();
}

if ($_REQUEST[ACTION] == "Changer mot de passe")
{
	$appl_user = new user();
	$appl_user->update_prepare();
}

if ($_REQUEST[ACTION] == "getxml")
{
	$appl_user = new user();
	$appl_user->getxml();
}

if (($_REQUEST[ACTION] != "Créer utilisateur") && ($_REQUEST[ACTION] != "Sauvegarder") && ($_REQUEST[ACTION] != "Modification") && ($_REQUEST[ACTION] != "Valider") && ($_REQUEST[ACTION] != "Changer mot de passe") && ($_REQUEST[ACTION] != "getxml") )
{
echo "<b>Gestion des utilisateurs</b><br>";
$appl_user = new user();
echo "<form action = 'index.php' method='post'>";
echo "<input type='hidden' name='MODL' value='AUSR'></input>";
$appl_user->list_users($admin);
echo "<br>";
$appl_user->display_actions($admin);
echo "</form>";
}
?>
