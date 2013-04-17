<?
function create_pdf($db,$report)
{
        ob_start();
	$date = date("d.m.Y"); 
?>
<link type="text/css" href="css/gsp.css" rel="stylesheet" >
<page style="font-size: 14px" backtop="8mm" backbottom="5mm" orientation="paysage">
<page_header>
	<table><tr><td>
	<table class='T1'>
        <tr valign=top>
        <td class='TD2' width=50>No</td>
        <td class='TD2' width=580>Description</td>
        <td class='TD2' width=30>Suivi</td>
        <td class='TD2' width=60>Statut</td>
        <td class='TD2' width=40>Jours</td>
        <td class='TD2' width=70>Ouverture</td>
        <td class='TD2' width=50>Urgence</td>
        <td class='TD2' width=50>Contact</td>
        </tr></table>
	</td></tr>
        </table>
</page_header>
<page_footer>
<table><tr><td>
<table style="width: 100%; border: solid 1px black;">
	<tr>
	<td style="text-align: left;	width: 100"><?echo $date?></td>
	<td style="text-align: center;  width: 840">GSP Report Light</td>
	<td style="text-align: right;	width: 100">page [[page_cu]]/{nb}</td>
	</tr>
</table>
</td></tr>
</table>
</page_footer>
<?
	if ($report == "event_open")
	{
		display_table_event_for_pdf($db,"SELECT code as No, summary as Description, owner as Suivi, status_code as Statut, last_modif_status_d as Jours, strftime('%Y.%m.%d',opened_d) as 'Ouverture', priority_code || '<pri_rank>' || priority_rank || '</pri_rank>' as 'Urgence', contact as Contact from event_vw where owner isnull=0 and state='open' order by id;");
	}
	else
	{
		echo "RAPPORT INCONNU";
	}
?>

</page>
<?
        $content = ob_get_clean();
        require_once('ext/html2pdf/html2pdf.class.php');
        $html2pdf = new HTML2PDF('P','A4','fr');
        $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output($report . '.pdf');
}
?>
