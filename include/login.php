<?
//  @~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~@
// @                                                                                                       @
// @                                     GSP Global Support Platform                                       @
// @                                                                                                       @
//  @~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~@
// @                                                                                                       @
// @   Name  : GSP Login Page     Initiale Release : 1.0   03-10-2006    Author : Jean-Claude Schopfer     @
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
// @  GSP Login Page is written in PHP.  The script display the user login form and the version of GSP.    @
// @                                                                                                       @
//  @~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~@
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
<head>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/gsp.css"></link>
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<title>GSP Global Support Platform</title>
</head>
<body>
<table class="center">
<tr>
	<td class="center"><img src="pict/logo.png" alt="GSP"/></td>
</tr>
<tr>
	<td class="center"><img src="pict/logo_text.png" alt="Global Support Platform"/></td>
</tr>
<tr>
	<td class="center"></br><? echo $GSP_VERSION;?></td>
</tr>
<tr>
	<td class="center">
		<br/>
		<form action="" method="post">
		<table class="T3">
		<tr>
			<td class="TDC2">
				<table class="center">
				<tr>
					<td class="TDR1">Login</td>
					<td>&nbsp;&nbsp;&nbsp;</td>
					<td valign="middle">
						<input type="text" size="10" name="USER"/>
					</td>
					<td>&nbsp;&nbsp;</td>
				</tr>
				</table>
				<table class="center">
				<tr>
					<td class="TDR1">Password</td>
					<td>&nbsp;&nbsp;&nbsp;</td>
					<td valign="middle">
						<input type="password" size="10" name="PASS"/>
					</td>
					<td>&nbsp;&nbsp;</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<table class="center">
		<tr>
			<td><br/></td>
		</tr>
		<tr>
			<td class="TDW100"><input type="submit" name="BT1" value="&nbsp;&nbsp;&nbsp; OK&nbsp;&nbsp;&nbsp;&nbsp;"/></td>
			<td class="TDW100"><input type="reset"  name="BT2" value="&nbsp; Clear&nbsp;&nbsp;"/></td>
			<td class="TDW100"><input type="button" name="BT3" value="&nbsp; Help&nbsp;&nbsp;" onclick="javascript:alert('___________________________________________________\n\n       * * *             G S P   Global Support Platform          * * *\n___________________________________________________\n\nPr&eacute;ciser votre User-ID, votre mot de passe et cliquez sur OK.\n___________________________________________________\n\nSimply entry your User-ID, your password and clic on OK.\n___________________________________________________')"/></td>
		</tr>
		<tr>	
			<td><br/></td>
		</tr>
		</table>
		</form>
		<?echo $ERROR;?>
	</td>
</tr>
</table>
</body>
</html>
