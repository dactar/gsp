//v.3.6 build 130417

/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
You allowed to use this component or parts of it under GPL terms
To use it on other terms or get Professional edition of the component please contact us at sales@dhtmlx.com
*/
dhtmlXForm.prototype.items.container={render:function(a,b){a._type="container";a._enabled=!0;this.doAddLabel(a,b);this.doAddInput(a,b,"DIV",null,!0,!0,"dhxform_container");return this},getContainer:function(a){return a.childNodes[a._ll?1:0].childNodes[0]},enable:function(a){a._enabled=!0;if(String(a.className).search("disabled")>=0)a.className=String(a.className).replace(/disabled/gi,"");a.callEvent("onEnable",[a._idd])},disable:function(a){a._enabled=!1;String(a.className).search("disabled")<0&&
(a.className+=" disabled");a.callEvent("onDisable",[a._idd])},doAttachEvents:function(){},setValue:function(){},getValue:function(){return null}};dhtmlXForm.prototype.getContainer=function(a){return this.doWithItem(a,"getContainer")};(function(){for(var a in dhtmlXForm.prototype.items.input)dhtmlXForm.prototype.items.container[a]||(dhtmlXForm.prototype.items.container[a]=dhtmlXForm.prototype.items.input[a])})();

//v.3.6 build 130417

/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
You allowed to use this component or parts of it under GPL terms
To use it on other terms or get Professional edition of the component please contact us at sales@dhtmlx.com
*/