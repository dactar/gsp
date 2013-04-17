<?
class sla_compo
{
	public $db;
	public $sla_id;
	public $type_dict_id;
	public $severity_dict_id;
        public $step_dict_id;
	public $max_time_h;
	public $from_d;
	public $till_d;

	function __tostring()
	{
	    return "Cette classe permet de définir et manipuler un sla_compo<br/>";
	}

	function __construct()
	{
	    global $db;
	    $this->db = $db;
            $this->sla_id = $_REQUEST[SLA_ID];
	}

	function display_actions()
	{
	    if ($_REQUEST[MODL] == "VSLA")
	    {
	    if ($this->sla_id == "")
            {
		$this->sla_id=return_query($this->db, "select min(id) from sla where active_f = 1 and rank_n = (select min(rank_n) from sla where active_f = 1)");
		$this->from_d=date('Y-m-d',mktime(0,0,0,date('n'),1));
		$this->till_d=date('Y-m-d',time());
	    }
            else
            {
		$this->from_d=$_REQUEST[from_d];
		$this->till_d=$_REQUEST[till_d];
            }
           
	    echo "<center>";
	    echo "<form action = 'index.php?MODL=$_REQUEST[MODL]' method='post'>
		  Sélection du SLA <select name='SLA_ID' size='1'>";
	    echo return_query_form_options($this->db, $this->sla_id, "select id, organisation_code || ' : ' || code from sla_vw where active_f = 1 order by rank_n");
	    echo "</select> Depuis <input type=text size=10 name=from_d value=$this->from_d>";
	    echo "Jusqu'à <input type=text size=10 name=till_d value=$this->till_d><input type='submit' value='ok'></form></center>";
	    }
	}

	function display_data()
	{
	    if ($_REQUEST[MODL] == "ESLA") {$this->display_event();}
	    if (!$this->sla_id) {exit;}
	    $list_step=return_query_array($this->db,"select dict_id, code from dict_vw where parent_code = 'step' and parent_dict_id in (select dict_id from dict_vw where parent_code = 'sla') and dict_id in (select step_dict_id from sla_compo where sla_id = $this->sla_id)");
            $list_step[]=array(0,'<b>Temps Global</b>');
            $list_severity=return_query_array($this->db,"select dict_id, code from dict_vw where parent_code = 'severity' and parent_dict_id in (select dict_id from dict_vw where parent_code = 'level') and dict_id in (select severity_dict_id from sla_compo where sla_id = $this->sla_id)");
            $list_type=return_query_array($this->db,"select dict_id, description from dict_vw where parent_code = 'type' and code != 'PR' and parent_dict_id in (select dict_id from dict_vw where parent_code is null) and dict_id in (select type_dict_id from sla_compo where sla_id = $this->sla_id)");
	    $global_calc_rule=return_query($this->db,"select code from dict where dict_id = (select global_calc_rule_dict_id from sla where $this->sla_id)");
            $sla=new sla();
	    $sla->set_id($this->sla_id);

	    if($global_calc_rule == "elimination")
	    {
		$this->db->query("attach database ':memory:' as tempdb");
		return_query($this->db,"create table tempdb.calc as select id, NULL as step1, NULL as step2, NULL as step3, NULL as step4, NULL as global from event where opened_d > '$this->from_d 00:00:00' and opened_d < '$this->till_d 23:59:59'");
	    }
	    echo "<br><center>";
	    echo "<input type=hidden name=MODL value=TSLA>";
	    echo "<input type=hidden name=SLA_ID value=$this->sla_id>";
            echo "<table class=T5 cellpadding=3>";
            echo "<tr><th>Type</th><th>Importance</th>";
            $totalok=0;
            $totalko=0;
            foreach ($list_step as $id => $step)
	    {
		echo "<td>$step[1]</td>";
	    }
	    foreach ($list_type as $id => $type)
	    {
		echo "<tr><td>$type[1]</td><td align=center>";
			echo "<table class=T5 cellpadding=20>";
			foreach ($list_severity as $id => $severity)
			{
				echo "<tr><td>$severity[1]</td></tr>";
			}
			echo "</table>";
		echo "</td>";
		foreach ($list_step as $id => $step)
		{
			echo "<td><table align=center>";
			foreach ($list_severity as $id => $severity)
			{
				$sla_time=return_query($this->db,"select max_time_h from sla_compo where sla_id = $this->sla_id and step_dict_id = $step[0] and severity_dict_id = $severity[0] and type_dict_id = $type[0]");
				if ($sla_time != "") 
				{
					$result=$sla->verify($this->from_d, $this->till_d, $step[0],$severity[0],$type[0],$sla_time,$global_calc_rule);
					$totalok=$totalok+$result[ok];
					$totalko=$totalko+$result[ko];
					if($sla_time >= 24)
					{
						$unit="j";
						$sla_display=$sla_time / 24;
					}
					else
					{
						if($sla_time < 1)
						{
							$unit="min";
							$sla_display=$sla_time * 60;
						}
						else
						{
							$unit="h";
							$sla_display=$sla_time;
						}
					}	
					$callstringok="parent.menu_call_module(\"ESLA\",\"$this->sla_id,$this->from_d,$this->till_d,$step[0],$severity[0],$type[0],$sla_time,1\",800,600,\"Liste des événements concernés OK\")";
					$callstringko="parent.menu_call_module(\"ESLA\",\"$this->sla_id,$this->from_d,$this->till_d,$step[0],$severity[0],$type[0],$sla_time,0\",800,600,\"Liste des événements concernés KO\")";
					echo "<tr><td colspan=2 align=center bgcolor=#FFFFFF>";
					echo "<table border=1><tr>";
					if ($result[ko] > 0)
					{
						echo "<td align=center width='50%' bgcolor='#f2dcdc'><a href='javascript:$callstringko;'>$result[ko]</a></td>";
					}
					else
					{
						echo "<td align=center width='50%' bgcolor='#f2dcdc'>$result[ko]</td>";
					}
					if ($result[ok] > 0)
					{
						echo "<td align=center width='50%' bgcolor='#cfe8bd'><a href='javascript:$callstringok;'>$result[ok]</a></td>";
					}
					else
					{
						echo "<td align=center width='50%' bgcolor='#cfe8bd'>$result[ok]</td>";
					}
					echo "</tr>";
					echo "<tr><td colspan=2 align=center width=120 bgcolor=#ebebeb>" . round($result[pct],0) . "% ( " . round($sla_display,2) . " $unit )</td></tr>";
					echo "</table>";
					echo "</td></tr>";
				}
			}
			echo "</table></td>";
		}
		echo "</tr>";
	    }
	    echo "</table>";
	    if ($totalok + $totalko > 0)
            {
		echo "<br>SLA respecté à <b>";

		$totalall=$totalok + $totalko;

            	if($global_calc_rule == "elimination")
            	{
			$totalko=return_query($this->db,"select count(*) from tempdb.calc where step1=1 or step2=1 or step3=1 or step4=1 or global=1");
			$totalall=$totalall / 5;
			$totalok=$totalall - $totalko;
                	$this->db->query("detach database tempdb");
			
            	}
		echo round($totalok / $totalall * 100,1) . "%</b> (méthode par $global_calc_rule)</b>";
	    }

	}


	function get_global_pct()
	{
	    $list_step=return_query_array($this->db,"select dict_id, code from dict_vw where parent_code = 'step' and parent_dict_id in (select dict_id from dict_vw where parent_code = 'sla') and dict_id in (select step_dict_id from sla_compo where sla_id = $this->sla_id)");
            $list_step[]=array(0,'<b>Temps Global</b>');
            $list_severity=return_query_array($this->db,"select dict_id, code from dict_vw where parent_code = 'severity' and parent_dict_id in (select dict_id from dict_vw where parent_code = 'level') and dict_id in (select severity_dict_id from sla_compo where sla_id = $this->sla_id)");
            $list_type=return_query_array($this->db,"select dict_id, description from dict_vw where parent_code = 'type' and code != 'PR' and parent_dict_id in (select dict_id from dict_vw where parent_code is null) and dict_id in (select type_dict_id from sla_compo where sla_id = $this->sla_id)");
	    $global_calc_rule=return_query($this->db,"select code from dict where dict_id = (select global_calc_rule_dict_id from sla where $this->sla_id)");
            $sla=new sla();
	    $sla->set_id($this->sla_id);

            if($global_calc_rule == "elimination")
	    {
                $this->db->query("attach database ':memory:' as tempdb");
                return_query($this->db,"create table tempdb.calc as select id, NULL as step1, NULL as step2, NULL as step3, NULL as step4, NULL as global from event where opened_d > '$this->from_d 00:00:00' and opened_d < '$this->till_d 23:59:59'");
            }

            $totalok=0;
            $totalko=0;
	    foreach ($list_type as $id => $type)
	    {
		foreach ($list_step as $id => $step)
		{
			foreach ($list_severity as $id => $severity)
			{
				$sla_time=return_query($this->db,"select max_time_h from sla_compo where sla_id = $this->sla_id and step_dict_id = $step[0] and severity_dict_id = $severity[0] and type_dict_id = $type[0]");
				if ($sla_time != "") 
				{
					$result=$sla->verify($this->from_d, $this->till_d, $step[0],$severity[0],$type[0],$sla_time,$global_calc_rule);
					$totalok=$totalok+$result[ok];
					$totalko=$totalko+$result[ko];
				}
			}
		}
	    }
	    if ($totalok + $totalko > 0)
            {
                $totalall=$totalok + $totalko;

                if($global_calc_rule == "elimination")
                {
                        $totalko=return_query($this->db,"select count(*) from tempdb.calc where step1=1 or step2=1 or step3=1 or step4=1 or global=1");
                        $totalall=$totalall / 5;
                        $totalok=$totalall - $totalko;
                        $this->db->query("detach database tempdb");
                }

                return round($totalok / ($totalall) * 100,1);
	    }
	    else
	    {
		return 0;
	    }
	}

	function get_sla_by_events($assigned)
	{
	    if($assigned)
	    {
		$cond_assigned="and owner_id is not null";
	    }
	    else
	    {
		$cond_assigned="and owner_id is null";
            }
	    $list_step=return_query_array($this->db,"select dict_id, code from dict_vw where parent_code = 'step' and parent_dict_id in (select dict_id from dict_vw where parent_code = 'sla') and dict_id in (select step_dict_id from sla_compo where sla_id = $this->sla_id)");
            $list_step[]=array(0,'<b>Temps Global</b>');
            $list_severity=return_query_array($this->db,"select dict_id, code from dict_vw where parent_code = 'severity' and parent_dict_id in (select dict_id from dict_vw where parent_code = 'level') and dict_id in (select severity_dict_id from sla_compo where sla_id = $this->sla_id)");
            $list_type=return_query_array($this->db,"select dict_id, description from dict_vw where parent_code = 'type' and code != 'PR' and parent_dict_id in (select dict_id from dict_vw where parent_code is null) and dict_id in (select type_dict_id from sla_compo where sla_id = $this->sla_id)");
	    $global_calc_rule=return_query($this->db,"select code from dict where dict_id = (select global_calc_rule_dict_id from sla where $this->sla_id)");
            $sla=new sla();
	    $sla->set_id($this->sla_id);

            $this->db->query("attach database ':memory:' as tempdb");
            return_query($this->db,"create table tempdb.calc as select id, NULL as step1, NULL as step2, NULL as step3, NULL as step4, NULL as global from event where closed_d isnull $cond_assigned");

	    foreach ($list_type as $id => $type)
	    {
		foreach ($list_step as $id => $step)
		{
			foreach ($list_severity as $id => $severity)
			{
				$sla_time=return_query($this->db,"select max_time_h from sla_compo where sla_id = $this->sla_id and step_dict_id = $step[0] and severity_dict_id = $severity[0] and type_dict_id = $type[0]");
				if ($sla_time != "") 
				{
					$result=$sla->verify("", "", $step[0],$severity[0],$type[0],$sla_time,$global_calc_rule,0,true);
				}
			}
		}
	    }
	    $events_sla=return_query_array($this->db,"select id, step1, step2, step3, step4, global from tempdb.calc");
            $this->db->query("detach database tempdb");
	    return $events_sla;
	}

	function display_event()
	{
	    $options=explode(",",$_REQUEST[MODL_OPTION]);
            $sla=new sla();
            $sla->set_id($options[0]);

	    $step_code=return_query($this->db,"select code from dict where dict_id = $options[3]");
            $severity_code=return_query($this->db,"select code from dict where dict_id = $options[4]");
	    $type_code=return_query($this->db,"select code from dict where dict_id = $options[5]");

	    echo "<table align=center cellpadding=3 border=1>";
	    echo "<tr><td><b>Depuis</td><td>$options[1]</td><td><b>Jusqu'à</b></td><td>$options[2]</td></tr>";
	    echo "<tr><td><b>Step</td><td>$step_code</td><td><b>Type</b></td><td>$type_code</td></tr>";
	    echo "<tr><td><b>Importance</td><td>$severity_code</td><td><b>Temps SLA</b></td><td>$options[6] h</td></tr>";
	    echo "</table>";

 	    $sla->verify($options[1],$options[2],$options[3],$options[4],$options[5],$options[6],0,$options[7]);
	}

	function update_prepare()
	{
	    $list_step=return_query_array($this->db,"select dict_id, code from dict_vw where parent_code = 'step' and parent_dict_id in (select dict_id from dict_vw where parent_code = 'sla')");
            $list_step[]=array(0,'<b>Temps Global</b>');
            $list_severity=return_query_array($this->db,"select dict_id, code from dict_vw where parent_code = 'severity' and parent_dict_id in (select dict_id from dict_vw where parent_code = 'level')");
            $list_type=return_query_array($this->db,"select dict_id, description from dict_vw where parent_code = 'type' and code != 'PR' and parent_dict_id in (select dict_id from dict_vw where parent_code is null)");

            echo "<b>Saisie des temps maximum en heure</b><br>";

	    echo "<center><a href='javascript:history.go(-1)' title='RETOUR'><img src='pict/back.png' border=0 alt='RETOUR'></a></center>";
	    display_table($this->db,"SELECT code as 'Code', name as 'Name', organisation_code as 'Organisation' from sla_vw where id = $this->sla_id");

	    echo "<center>";
	    echo "<form id='SLA_COMPO_ACTION_FORM' action = '' method='post'>";    
	    echo "<input type=hidden name=MODL value=TSLA>";
	    echo "<input type=hidden name=SLA_ID value=$this->sla_id>";
            echo "<table class=T5 cellpadding=3>";
            echo "<tr><th>Type</th><th>Importance</th>";
            foreach ($list_step as $id => $step)
	    {
		echo "<td>$step[1]</td>";
	    }
	    foreach ($list_type as $id => $type)
	    {
		echo "<tr><td>$type[1]</td><td align=center>";
			echo "<table class=T5 cellpadding=4>";
			foreach ($list_severity as $id => $severity)
			{
				echo "<tr><td>$severity[1]</td></tr>";
			}
			echo "</table>";
		echo "</td>";
		foreach ($list_step as $id => $step)
		{
			echo "<td><table align=center>";
			foreach ($list_severity as $id => $severity)
			{
				$sla_time=return_query($this->db,"select max_time_h from sla_compo where sla_id = $this->sla_id and step_dict_id = $step[0] and severity_dict_id = $severity[0] and type_dict_id = $type[0]");
				echo "<tr><td><input type='text' name='sla_time[]' size=4 value=$sla_time></td></tr>";
			}
			echo "</table></td>";
		}
		echo "</tr>";
	    }
            echo "</table>";
	    echo "<br>";
	    echo "<input type=submit name=ACTION value='Valider'>";
            echo "</form></center>";
		
            return "On met à jour le sla_compo";
	}

	function update_submit()
	{
	    $list_step=return_query_array($this->db,"select dict_id, code from dict_vw where parent_code = 'step' and parent_dict_id in (select dict_id from dict_vw where parent_code = 'sla')");
            $list_step[]=array(0,'<b>Temps global</b>');
            $list_severity=return_query_array($this->db,"select dict_id, code from dict_vw where parent_code = 'severity' and parent_dict_id in (select dict_id from dict_vw where parent_code = 'level')");
            $list_type=return_query_array($this->db,"select dict_id, description from dict_vw where parent_code = 'type' and code != 'PR' and parent_dict_id in (select dict_id from dict_vw where parent_code is null)");

	    $count=0;
            foreach ($list_type as $id => $type)
            {
                foreach ($list_step as $id => $step)
                {
                        foreach ($list_severity as $id => $severity)
                        {
				$new_sla_time=$_REQUEST[sla_time][$count];

				if (!is_numeric($new_sla_time) || $new_sla_time == 0)
				{
					$new_sla_time="";
				}

				$old_sla_time=return_query($this->db,"select max_time_h from sla_compo where sla_id = $this->sla_id and step_dict_id = $step[0] and severity_dict_id = $severity[0] and type_dict_id = $type[0]");

				if ($new_sla_time != "")
				{
					if ($old_sla_time == "")
					{
					      	$query="INSERT INTO sla_compo (sla_id, type_dict_id, severity_dict_id, step_dict_id, max_time_h) values (:SLA_ID,:TYPE,:SEVERITY,:STEP,:MAX_TIME)";
            					$row = $this->db->prepare($query);
            					sqlerror($this->db,$query);
            					$row->bindParam(':SLA_ID',          $this->sla_id,              PDO::PARAM_INT);
            					$row->bindParam(':TYPE',            $type[0],                   PDO::PARAM_INT);
            					$row->bindParam(':SEVERITY',        $severity[0],               PDO::PARAM_INT);
            					$row->bindParam(':STEP',            $step[0],                   PDO::PARAM_INT);
            					$row->bindParam(':MAX_TIME',        $new_sla_time,              PDO::PARAM_STR);
            					$row->execute();
            					sqlerror($this->db,$query);			
					}
					else
					{
						if ($old_sla_time != $new_sla_time)
						{
	                                                $query="UPDATE sla_compo set max_time_h = :MAX_TIME where sla_id = :SLA_ID and type_dict_id = :TYPE and severity_dict_id = :SEVERITY and step_dict_id = :STEP";
                                                	$row = $this->db->prepare($query);
                                                	sqlerror($this->db,$query);
                                                	$row->bindParam(':SLA_ID',          $this->sla_id,              PDO::PARAM_INT);
                                                	$row->bindParam(':TYPE',            $type[0],                   PDO::PARAM_INT);
                                                	$row->bindParam(':SEVERITY',        $severity[0],               PDO::PARAM_INT);
                                                	$row->bindParam(':STEP',            $step[0],                   PDO::PARAM_INT);
                                                	$row->bindParam(':MAX_TIME',        $new_sla_time,              PDO::PARAM_STR);
                                                	$row->execute();
                                                	sqlerror($this->db,$query);        
						}
					}
				}
				else
				{
					if ($old_sla_time != "")
					{
                                                $query="DELETE FROM sla_compo where sla_id = :SLA_ID and type_dict_id = :TYPE and severity_dict_id = :SEVERITY and step_dict_id = :STEP";
                                                $row = $this->db->prepare($query);
                                                sqlerror($this->db,$query);
                                                $row->bindParam(':SLA_ID',          $this->sla_id,              PDO::PARAM_INT);
                                                $row->bindParam(':TYPE',            $type[0],                   PDO::PARAM_INT);
                                                $row->bindParam(':SEVERITY',        $severity[0],               PDO::PARAM_INT);
                                                $row->bindParam(':STEP',            $step[0],                 PDO::PARAM_INT);
                                                $row->execute();
                                                sqlerror($this->db,$query);        
					}
				}
				$count++;
                        }
                }
            }
	
	    global $web_page;
            $web_page->render();
	    $this->update_prepare();

	    return "On met à jour le sla_compo";
	}
}
?>
