<?
class sla
{
	public $db;
	public $id;
	public $code;
	public $name;
	public $organisation_dict_id;
	public $global_calc_rule_dict_id;
	public $active_f;
	public $last_modif;

	function __tostring()
	{
	    return "Cette classe permet de définir et manipuler un sla<br/>";
	}

	function __construct()
	{
	    global $db;
	    $this->db = $db;
	    if($_REQUEST[ID] != "")
	    {
	    	$this->id			= $_REQUEST[ID];
	    	$this->code 			= return_query($this->db,"SELECT code from sla where id = $this->id");
	    	$this->name 			= return_query($this->db,"SELECT name from sla where id = $this->id");
		$this->organisation_dict_id 	= return_query($this->db,"SELECT organisation_dict_id from sla where id = $this->id");
		$this->global_calc_rule_dict_id = return_query($this->db,"SELECT global_calc_rule_dict_id from sla where id = $this->id");
	    	$this->rank_n			= return_query($this->db,"SELECT rank_n from sla where id = $this->id");
	    	$this->active_f 		= return_query($this->db,"SELECT active_f from sla where id = $this->id");
		$this->last_modif		= return_query($this->db,"SELECT last_modif_d || ' / ' || last_user_code from sla_vw where id = $this->id");
	    }

	}

	function set_id($id)
	{
		$this->id = $id;
		$this->organisation_dict_id     = return_query($this->db,"SELECT organisation_dict_id from sla where id = $this->id");
	}

	function alert()
	{
		$sla_compo=new sla_compo();
		$sla_compo->sla_id=$this->id;
		$sla_compo->from_d=date('Y-m-d',mktime(0,0,0,date('n'),1));
                $sla_compo->till_d=date('Y-m-d',time()); 
		return $sla_compo->get_global_pct();
	}

	function verif_events($assigned=true)
	{
		$sla_compo=new sla_compo();
		$sla_compo->sla_id=$this->id;
		return $sla_compo->get_sla_by_events($assigned);
	}

	function verify($from_d, $till_d, $step, $severity, $type, $sla_time, $global_calc_rule, $getresult=0, $tempdb=false)
	{
		$result = array();
		
		$workdaysperweek=5;
		$weekendstring="(((strftime('%s',date(\$end_d)) - strftime('%s',date(\$begin_d)))/86400) - (\$workdaysperweek*((((strftime('%s',date(\$end_d)) - strftime('%s',date(\$begin_d)))/86400) - strftime ('%w',\$end_d) + strftime ('%w',date(\$begin_d)))/7) + strftime ('%w',\$end_d) - strftime ('%w',\$begin_d))) * 24";

		$cond_from="";
		if($from_d != "")
		{
			$from_d.=" 00:00:00";
			$cond_from="and opened_d > '$from_d'";
		}

		$cond_till="";
		if($till_d != "")
		{
			$till_d.=" 23:59:59";
			$cond_till="and opened_d < '$till_d'";
		}

		$cond_tempdb="";
		if($tempdb)
		{
			$cond_tempdb="and e.id in (select id from tempdb.calc)";
		}

		$cond_orga="and contact_id in (select id from contact where group_dict_id in (select dict_id from dict where parent_dict_id = $this->organisation_dict_id))";
		$cond_notuser="and contact_id not in (select distinct contact_id from user where active_f = 1)";

		$planif="ifnull(strftime('%s',planif_d) - strftime('%s',logged_d),0)";

		$planifweekendstring="ifnull((((strftime('%s',date(planif_d)) - strftime('%s',date(logged_d)))/86400) - (\$workdaysperweek*((((strftime('%s',date(planif_d)) - strftime('%s',date(logged_d)))/86400) - strftime ('%w',planif_d) + strftime ('%w',date(logged_d)))/7) + strftime ('%w',planif_d) - strftime ('%w',logged_d))) * 24,0)";

		$external="
select sum(x.a)
from
(
select (select sum(strftime('%s',(select case when (select date_d from event_history where event_id = e.id and type_dict_id = 57 and date_d > eh.date_d) is null then datetime('now', 'localtime') else (select date_d from event_history where event_id = e.id and type_dict_id = 57 and date_d > eh.date_d) end)) - (strftime('%s',eh.date_d)) 
- (
(((strftime('%s',date((select case when (select date_d from event_history where event_id = e.id and type_dict_id = 57 and date_d > eh.date_d) is null then datetime('now', 'localtime') else (select date_d from event_history where event_id = e.id and type_dict_id = 57 and date_d > eh.date_d) end))) - strftime('%s',date(eh.date_d)))/86400) 
- (5*((((strftime('%s',date((select case when (select date_d from event_history where event_id = e.id and type_dict_id = 57 and date_d > eh.date_d) is null then datetime('now', 'localtime') else (select date_d from event_history where event_id = e.id and type_dict_id = 57 and date_d > eh.date_d) end))) - strftime('%s',date(eh.date_d)))/86400) 
- strftime ('%w',(select case when (select date_d from event_history where event_id = e.id and type_dict_id = 57 and date_d > eh.date_d) is null then datetime('now', 'localtime') else (select date_d from event_history where event_id = e.id and type_dict_id = 57 and date_d > eh.date_d) end)) + strftime ('%w',date(eh.date_d)))/7) 
+ strftime ('%w',(select case when (select date_d from event_history where event_id = e.id and type_dict_id = 57 and date_d > eh.date_d) is null then datetime('now', 'localtime') else (select date_d from event_history where event_id = e.id and type_dict_id = 57 and date_d > eh.date_d) end)) - strftime ('%w',eh.date_d))) * 86400
))
from event_history eh 
where eh.event_id = e.id and eh.type_dict_id = 57 and eh.status_dict_id in (53,54,55)) as a
union
select 0 as a
) x";
		if($step == 0)
		{
			$begin_d="opened_d";
			$end_d="closed_d";
			eval( "\$weekend = \"$weekendstring\";" );
			eval( "\$planifweekend = \"$planifweekendstring\";" );

			if($_REQUEST[MODL] == "VSLA" || $_REQUEST[MODL] == "RFSH")
			{
				$result[ko]=return_query($this->db,"select count(*) from event e where severity_dict_id = $severity and type_dict_id = $type and $end_d is not null and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($external) - ($planif))/ 3600.0 ) - ($weekend - $planifweekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb");
				if ($global_calc_rule == "elimination" || $tempdb)
				{
					$this->db->query("update tempdb.calc set global = 1 where id in (select e.id from event e where severity_dict_id = $severity and type_dict_id = $type and $end_d is not null and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($external) - ($planif))/ 3600.0 ) - ($weekend - $planifweekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb)");
				}
			}
			else
			{
				if($getresult==0)
				{
					display_table_href($this->db,"select '<a href=\"javascript:parent.open_event(' || id || ')\">' || code || '</a>' as No, round((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($external) - ($planif))/ 3600.0 ) - ($weekend - $planifweekend),1) as Temps, owner as Suivi, summary as Description from event_vw e where severity_dict_id = $severity and type_dict_id = $type and $end_d is not null and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($external) - ($planif))/ 3600.0 ) - ($weekend - $planifweekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb order by 2");
				}
				else
				{
					display_table_href($this->db,"select '<a href=\"javascript:parent.open_event(' || id || ')\">' || code || '</a>' as No, round((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($external) - ($planif))/ 3600.0 ) - ($weekend - $planifweekend),1) as Temps, owner as Suivi, summary as Description from event_vw e where id not in (select id from event e where severity_dict_id = $severity and type_dict_id = $type and $end_d is not null and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($external) - ($planif))/ 3600.0 ) - ($weekend - $planifweekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb) and severity_dict_id = $severity and type_dict_id = $type and $end_d is not null $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb order by 2");
				}
			}

			$end_d="'now', 'localtime'";
			eval( "\$weekend = \"$weekendstring\";" );

			if($_REQUEST[MODL] == "VSLA" || $_REQUEST[MODL] == "RFSH")
			{
				$result[ko]=$result[ko]+return_query($this->db,"select count(*) from event e where severity_dict_id = $severity and type_dict_id = $type and closed_d is null and ((((strftime('%s','now', 'localtime')  - strftime('%s',$begin_d)) - ($external) - ($planif))/ 3600.0 ) - ($weekend - $planifweekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb");
				if ($global_calc_rule == "elimination" || $tempdb)
				{
					$this->db->query("update tempdb.calc set global = 1 where id in (select e.id from event e where severity_dict_id = $severity and type_dict_id = $type and closed_d is null and ((((strftime('%s','now', 'localtime')  - strftime('%s',$begin_d)) - ($external) - ($planif))/ 3600.0 ) - ($weekend - $planifweekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb)");
				}
			}
			else
			{
				if($getresult==0)
				{
					display_table_href($this->db,"select '<a href=\"javascript:parent.open_event(' || id || ')\">' || code || '</a>' as No, round((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($external) - ($planif))/ 3600.0 ) - ($weekend - $planifweekend),1) as Temps, owner as Suivi, summary as Description from event_vw e where severity_dict_id = $severity and type_dict_id = $type and closed_d is null and ((((strftime('%s','now', 'localtime')  - strftime('%s',$begin_d)) - ($external) - ($planif))/ 3600.0 ) - ($weekend - $planifweekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb order by 2");
				}
				else
				{
					display_table_href($this->db,"select '<a href=\"javascript:parent.open_event(' || id || ')\">' || code || '</a>' as No, round((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($external) - ($planif))/ 3600.0 ) - ($weekend - $planifweekend),1) as Temps, owner as Suivi, summary as Description from event_vw e where id not in (select id from event where severity_dict_id = $severity and type_dict_id = $type and closed_d is null and ((((strftime('%s','now', 'localtime')  - strftime('%s',$begin_d)) - ($external) - ($planif))/ 3600.0 ) - ($weekend - $planifweekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb) and severity_dict_id = $severity and type_dict_id = $type and closed_d is null $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb order by 2");
				}
			}

			if($_REQUEST[MODL] == "VSLA" || $_REQUEST[MODL] == "RFSH")
			{
				$result[ok]=return_query($this->db,"select count(*) from event e where severity_dict_id = $severity and type_dict_id = $type $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb") - $result[ko];
			}
		}
		else
		{
			$step_rank=return_query($this->db,"select rank_n from dict where dict_id = $step");

			if ($step_rank == 1)
			{
				$begin_d="opened_d";
				$end_d="logged_d";
				eval( "\$weekend = \"$weekendstring\";" );

				if($_REQUEST[MODL] == "VSLA" || $_REQUEST[MODL] == "RFSH")
				{

					$result[ko]=return_query($this->db,"select count(*) from event e where severity_dict_id = $severity and type_dict_id = $type and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) ) / 3600.0 ) - ($weekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb");

					$result[ok]=return_query($this->db,"select count(*) from event e where severity_dict_id = $severity and type_dict_id = $type $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb") - $result[ko];

					if ($global_calc_rule == "elimination" || $tempdb)
                                	{
                                        	$this->db->query("update tempdb.calc set step1 = 1 where id in (select e.id from event e where severity_dict_id = $severity and type_dict_id = $type and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) ) / 3600.0 ) - ($weekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb)");
					}
				}
				else
				{
					if($getresult==0)
					{
						display_table_href($this->db,"select '<a href=\"javascript:parent.open_event(' || id || ')\">' || code || '</a>' as No, round((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) ) / 3600.0 ) - ($weekend),2) as Temps, owner as Suivi, summary as Description from event_vw e where severity_dict_id = $severity and type_dict_id = $type and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) ) / 3600.0 ) - ($weekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb order by 2");
					}
					else
					{
						display_table_href($this->db,"select '<a href=\"javascript:parent.open_event(' || id || ')\">' || code || '</a>' as No, round((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) ) / 3600.0 ) - ($weekend),2) as Temps, owner as Suivi, summary as Description from event_vw e where id not in (select id from event_vw e where severity_dict_id = $severity and type_dict_id = $type and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) ) / 3600.0 ) - ($weekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb) and severity_dict_id = $severity and type_dict_id = $type $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb order by 2");
					}
				}
			}
			else
			{
				if ($step_rank == 2)
				{
					$begin_d="logged_d";
					$end_d="(select case when e.owner_id is null then datetime('now', 'localtime') else (select min(date_d) from event_history where event_id = e.id and type_dict_id = 57 and status_dict_id = 50) end)";
					eval( "\$weekend = \"$weekendstring\";" );
					eval( "\$planifweekend = \"$planifweekendstring\";" );

					if($_REQUEST[MODL] == "VSLA" || $_REQUEST[MODL] == "RFSH")
					{
						$result[ko]=return_query($this->db,"select count(*) from event e where severity_dict_id = $severity and type_dict_id = $type and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($planif) ) / 3600.0 ) - ($weekend - $planifweekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb");

						$result[ok]=return_query($this->db,"select count(*) from event e where severity_dict_id = $severity and type_dict_id = $type $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb") - $result[ko];

						if ($global_calc_rule == "elimination" || $tempdb)
						{
							$this->db->query("update tempdb.calc set step2 = 1 where id in (select e.id from event e where severity_dict_id = $severity and type_dict_id = $type and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($planif) ) / 3600.0 ) - ($weekend - $planifweekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb)");
						}
					}
					else
					{
						if($getresult==0)
						{
							display_table_href($this->db,"select '<a href=\"javascript:parent.open_event(' || id || ')\">' || code || '</a>' as No, round((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($planif) ) / 3600.0 ) - ($weekend - $planifweekend),2) as Temps, owner as Suivi, summary as Description from event_vw e where severity_dict_id = $severity and type_dict_id = $type and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($planif) ) / 3600.0 ) - ($weekend - $planifweekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb order by 2");
						}
						else
						{
							display_table_href($this->db,"select '<a href=\"javascript:parent.open_event(' || id || ')\">' || code || '</a>' as No, round((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($planif) ) / 3600.0 ) - ($weekend - $planifweekend),2) as Temps, owner as Suivi, summary as Description from event_vw e where id not in (select id from event_vw e where severity_dict_id = $severity and type_dict_id = $type and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($planif) ) / 3600.0 ) - ($weekend - $planifweekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb) and severity_dict_id = $severity and type_dict_id = $type $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb order by 2");
						}
					}
				}
				else
				{
					if ($step_rank == 3)
					{
						$begin_d="(select min(date_d) from event_history where event_id = e.id and type_dict_id = 57 and status_dict_id = 50)";
						$end_d="(select min(date_d) from event_history where event_id = e.id and type_dict_id = 57 and status_dict_id = 51)";
						eval( "\$weekend = \"$weekendstring\";" );
						eval( "\$planifweekend = \"$planifweekendstring\";" );

						if($_REQUEST[MODL] == "VSLA" || $_REQUEST[MODL] == "RFSH")
						{
							$result[ko]=return_query($this->db,"select count(*) from event e where severity_dict_id = $severity and type_dict_id = $type and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($planif) ) / 3600.0 ) - ($weekend - $planifweekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb");

							$result[ok]=return_query($this->db,"select count(*) from event e where severity_dict_id = $severity and type_dict_id = $type $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb") - $result[ko];

							if ($global_calc_rule == "elimination" || $tempdb)
							{
								$this->db->query("update tempdb.calc set step3 = 1 where id in (select e.id from event e where severity_dict_id = $severity and type_dict_id = $type and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($planif) ) / 3600.0 ) - ($weekend - $planifweekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb)");
							}
						}
						else
						{
							if($getresult==0)
							{
								display_table_href($this->db,"select '<a href=\"javascript:parent.open_event(' || id || ')\">' || code || '</a>' as No, round((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($planif) ) / 3600.0 ) - ($weekend - $planifweekend),1) as Temps, owner as Suivi, summary as Description from event_vw e where severity_dict_id = $severity and type_dict_id = $type and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) ) / 3600.0 ) - ($weekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb order by 2");
							}
							else
							{
								display_table_href($this->db,"select '<a href=\"javascript:parent.open_event(' || id || ')\">' || code || '</a>' as No, round((((strftime('%s',$end_d)  - strftime('%s',$begin_d))- ($planif) ) / 3600.0 ) - ($weekend - $planifweekend),1) as Temps, owner as Suivi, summary as Description from event_vw e where id not in (select id from event_vw e where severity_dict_id = $severity and type_dict_id = $type and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($planif) ) / 3600.0 ) - ($weekend - $planifweekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb) and severity_dict_id = $severity and type_dict_id = $type $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb order by 2");
							}
						}
					}
					else
					{
						$begin_d="(select min(date_d) from event_history where event_id = e.id and type_dict_id = 57 and status_dict_id = 51)";
						$end_d="(select min(date_d) from event_history where event_id = e.id and type_dict_id = 57 and status_dict_id in (select dict_id from dict_vw where parent_code = 'closed' and parent_dict_id in (select dict_id from dict_vw where parent_code = 'status')))";
						eval( "\$weekend = \"$weekendstring\";" );
						eval( "\$planifweekend = \"$planifweekendstring\";" );

						if($_REQUEST[MODL] == "VSLA" || $_REQUEST[MODL] == "RFSH")
						{
							$result[ko]=return_query($this->db,"select count(*) from event e where severity_dict_id = $severity and type_dict_id = $type and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($external) - ($planif)) / 3600.0 ) - ($weekend - $planifweekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb");

							$result[ok]=return_query($this->db,"select count(*) from event e where severity_dict_id = $severity and type_dict_id = $type $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb") - $result[ko];

							if ($global_calc_rule == "elimination" || $tempdb)
							{
								$this->db->query("update tempdb.calc set step4 = 1 where id in (select e.id from event e where severity_dict_id = $severity and type_dict_id = $type and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($external) - ($planif)) / 3600.0 ) - ($weekend - $planifweekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb)");
							}
						}
						else
						{
							if($getresult==0)
							{
								 display_table_href($this->db,"select '<a href=\"javascript:parent.open_event(' || id || ')\">' || code || '</a>' as No, round((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($external) - ($planif)) / 3600.0 ) - ($weekend - $planifweekend),1) as Temps, owner as Suivi, summary as Description from event_vw e where severity_dict_id = $severity and type_dict_id = $type and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($external) - ($planif)) / 3600.0 ) - ($weekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb order by 2");
							}
							else
							{
								display_table_href($this->db,"select '<a href=\"javascript:parent.open_event(' || id || ')\">' || code || '</a>' as No, round((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($external) - ($planif)) / 3600.0 ) - ($weekend - $planifweekend),1) as Temps, owner as Suivi, summary as Description from event_vw e where id not in (select id from event_vw e where severity_dict_id = $severity and type_dict_id = $type and ((((strftime('%s',$end_d)  - strftime('%s',$begin_d)) - ($external) - ($planif)) / 3600.0 ) - ($weekend - $planifweekend)) > $sla_time $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb) and severity_dict_id = $severity and type_dict_id = $type $cond_orga $cond_notuser $cond_from $cond_till $cond_tempdb order by 2");
							}
						}
					}
				}
			}
		}
                if ($result[ok] + $result[ko] == 0)
                {
                        $result[pct] = "-.-";
                }
                else
                {
                        $result[pct]=$result[ok] / ($result[ok] + $result[ko]) * 100;
                }

		return $result;
	}

	function getxml()
	{
            $object=$this;
            $array = (array) $object;
            unset($array[db]);
            $xml=array2xml("data",$array);
	    echo $xml;
            return "retourne le sla sous forme xml";
	}

	function display_actions($admin)
	{
	    echo "<center>";
	    echo "<form id='SLA_DETAIL_ACTION_FORM' action = '' method='post'>";
	    if ($admin == 1)
	    {
		echo "<input type='hidden' name='ACTION' value='create'></input>";
		echo "<input type='submit' name='submit' value='Créer'></input>";
		echo "<br><br>";	
	    }
	    echo "</form>";
	    echo "</center>";
	    return "On affiche les actions disponibles";
	}

	function display_data($admin)
	{
	    if ($admin == 1)
	    {
	    manage_table("MODL=$_REQUEST[MODL]&ACTION=Modification","ID",$this->db,"SELECT id, code as 'Code', name as 'Nom', organisation_code as 'Organisation', global_calc_rule_code as 'Règle de calcul', active_f as 'Actif', rank_n as 'Rank' from sla_vw",0,0);
	    }
	    else
	    {
	    display_table($this->db,"SELECT code as 'Code', name as 'Nom', organisation_code as 'Organisation', global_calc_rule_code as 'Règle de calcul', active_f as 'Actif', rank_n as 'Rang' from sla_vw");
	    }
	    return "On affiche les slas";
	}

	function create_prepare()
	{
	    echo "<center><a href='index.php?MODL=$_REQUEST[MODL]' title='RETOUR'><img src='pict/back.png' border=0 alt='RETOUR'></a></center>";
            $web_form = new web_form("sla","create");
	    $web_form->set_list_options("organisation_dict_id, global_calc_rule_dict_id");
            $web_form->display();

	    return_query_webform_options($this->db, "organisation_dict_id", "", "select dict_id, code from dict_vw where parent_code = 'organisation' and active_f = 1 order by rank_n");
	    return_query_webform_options($this->db, "global_calc_rule_dict_id", "", "select dict_id, description from dict_vw where parent_code = 'rule' and active_f = 1 order by rank_n");


	    return "On affiche le formulaire de création du sla";
	}

	function create_submit()
	{
	    $_POST=utf8_array_decode($_POST); 	 
	    if ($_POST[code] == ""){print "<div class='ERR'>ERREUR : AUCUN CODE INDIQUE</div>"; exit;}
	    if ($_POST[organisation_dict_id] == "0"){print "<div class='ERR'>ERREUR : AUCUNE ORGANISATION INDIQUEE</div>"; exit;}
	    $query="INSERT INTO sla (code, name, organisation_dict_id, global_calc_rule_dict_id, active_f, rank_n) values (:CODE,:NAME,:ORGA,:RULE,:ACTIF,:RANK)";
            $row = $this->db->prepare($query);
	    sqlerror($this->db,$query);
	    $row->bindParam(':CODE',            $_POST[code],                   	PDO::PARAM_STR);
            $row->bindParam(':NAME',            $_POST[name],                   	PDO::PARAM_STR);
            $row->bindParam(':ORGA',            $_POST[organisation_dict_id],   	PDO::PARAM_INT);
            $row->bindParam(':RULE',            $_POST[global_calc_rule_dict_id],       PDO::PARAM_INT);
            $row->bindParam(':ACTIF',           $_POST[active_f],               	PDO::PARAM_INT);
            $row->bindParam(':RANK',            $_POST[rank_n],                 	PDO::PARAM_INT);
            $row->execute();
            sqlerror($this->db,$query);

            return "On crée le sla";

	}
	function update_prepare()
	{
	    echo "<center><a href='index.php?MODL=$_REQUEST[MODL]' title='RETOUR'><img src='pict/back.png' border=0 alt='RETOUR'></a></center>";
            $web_form = new web_form("sla","edit");
	    $web_form->set_list_options("organisation_dict_id, global_calc_rule_dict_id");
            $web_form->display($this->id);

            return_query_webform_options($this->db, "organisation_dict_id", "", "select dict_id, code from dict_vw where parent_code = 'organisation' and active_f = 1 order by rank_n");
	    return_query_webform_options($this->db, "global_calc_rule_dict_id", "", "select dict_id, description from dict_vw where parent_code = 'rule' and active_f = 1 order by rank_n");

            return "On met à jour le sla";
	}

	function update_submit()
	{
            $_POST=utf8_array_decode($_POST);
	    if ($_POST[code] == ""){print "<div class='ERR'>ERREUR : AUCUN CODE INDIQUE</div>"; exit;}
            if ($_POST[organisation_dict_id] == ""){print "<div class='ERR'>ERREUR : AUCUN ORGANISATION INDIQUEE</div>"; exit;}
            $query="UPDATE sla set code = :CODE, name = :NAME, organisation_dict_id = :ORGA, global_calc_rule_dict_id = :RULE, active_f = :ACTIF, rank_n = :RANK where id = :ID";
            $row = $this->db->prepare($query);
            sqlerror($this->db,$query);
	    $row->bindParam(':ID',              $_POST[id],				PDO::PARAM_INT);
            $row->bindParam(':CODE',            $_POST[code],                   	PDO::PARAM_STR);
            $row->bindParam(':NAME',            $_POST[name],                   	PDO::PARAM_STR);
            $row->bindParam(':ORGA',            $_POST[organisation_dict_id],   	PDO::PARAM_INT);
	    $row->bindParam(':RULE',            $_POST[global_calc_rule_dict_id],   	PDO::PARAM_INT);
            $row->bindParam(':ACTIF',           $_POST[active_f],               	PDO::PARAM_INT);
            $row->bindParam(':RANK',            $_POST[rank_n],                 	PDO::PARAM_INT);
            $row->execute();
            sqlerror($this->db,$query);

	    return "On met à jour le user";
	}

	function delete()
	{
	    $query="delete from sla where id= :ID";
	    $row = $this->db->prepare($query);
	    sqlerror($this->db,$query);
            $row->bindParam(':ID',              $_POST[id],                     PDO::PARAM_INT);
            $row->execute();
            sqlerror($this->db,$query);

	    return "On détruit le sla";
	}
}
?>
