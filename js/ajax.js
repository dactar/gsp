function send_data(data, page, method, id)
{
if (window.ActiveXObject)
{
//Internet Explorer
var XhrObj = new ActiveXObject("Microsoft.XMLHTTP") ;
}
else
{
var XhrObj = new XMLHttpRequest();
}

//définition de l'endroit d'affichage:
var content = document.getElementById(id);

//Ouverture du fichier en methode POST
XhrObj.open(method, page);

//Ok pour la page cible
XhrObj.onreadystatechange = function()
{
if (XhrObj.readyState == 4 && XhrObj.status == 200)
content.innerHTML = XhrObj.responseText;
}

XhrObj.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
XhrObj.send(data);
}
