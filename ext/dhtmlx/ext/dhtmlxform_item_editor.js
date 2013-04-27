//v.3.6 build 130417

/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
You allowed to use this component or parts of it under GPL terms
To use it on other terms or get Professional edition of the component please contact us at sales@dhtmlx.com
*/
dhtmlXForm.prototype.items.editor={editor:{},render:function(a,b){var c=!isNaN(b.rows);a._type="editor";a._enabled=!0;this.doAddLabel(a,b);this.doAddInput(a,b,"DIV",null,!0,!0,"dhxform_item_template dhxeditor_inside");a._value=b.value||"";var d=this;this.editor[a._idd]=new dhtmlXEditor(a.childNodes[a._ll?1:0].childNodes[0]);this.editor[a._idd].setContent(a._value);this.editor[a._idd].attachEvent("onAccess",function(b,c){_dhxForm_doClick(document.body,"click");if(b=="blur"){if(d.doOnBlur(a,this),a.callEvent("onBlur",
[a._idd]),a.getForm().skin=="dhx_terrace")a.childNodes[a._ll?1:0].childNodes[0].className="dhxform_item_template dhxeditor_inside"}else if(a.callEvent("onEditorAccess",[a._idd,b,c,this,a.getForm()]),a.callEvent("onFocus",[a._idd]),a.getForm().skin=="dhx_terrace")a.childNodes[a._ll?1:0].childNodes[0].className="dhxform_item_template dhxeditor_inside_focus"});this.editor[a._idd].attachEvent("onToolbarClick",function(b){a.callEvent("onEditorToolbarClick",[a._idd,b,this,a.getForm()])});b.readonly&&this.setReadonly(a,
!0);a.childNodes[a._ll?0:1].childNodes[0].removeAttribute("for");a.childNodes[a._ll?0:1].childNodes[0].onclick=function(){d.editor[a._idd]._focus()};if(!this.editor[a._idd]._isToolbar)this.editor[a._idd].base.firstChild.onmousedown=function(){return!1};return this},doOnBlur:function(a,b){var c=b.getContent();if(a._value!=c)a.checkEvent("onBeforeChange")&&a.callEvent("onBeforeChange",[a._idd,a._value,c])!==!0?b.setContent(a._value):(a._value=c,a.callEvent("onChange",[a._idd,c]))},setValue:function(a,
b){if(a._value!=b)a._value=b,this.editor[a._idd].setContent(a._value)},getValue:function(a){a._value=this.editor[a._idd].getContent();return a._value},enable:function(a){this.editor[a._idd].setReadonly(!1);this.doEn(a)},disable:function(a){this.editor[a._idd].setReadonly(!0);this.doDis(a)},setReadonly:function(a,b){this.editor[a._idd].setReadonly(b)},getEditor:function(a){return this.editor[a._idd]||null},destruct:function(a){a.childNodes[a._ll?0:1].childNodes[0].onclick=null;this.editor[a._idd].unload();
this.editor[a._idd]=null;this.d2(a);a=null},setFocus:function(a){this.editor[a._idd]._focus()}};(function(){for(var a in{doAddLabel:1,doAddInput:1,doUnloadNestedLists:1,setText:1,getText:1,setWidth:1,isEnabled:1})dhtmlXForm.prototype.items.editor[a]=dhtmlXForm.prototype.items.template[a]})();dhtmlXForm.prototype.items.editor.d2=dhtmlXForm.prototype.items.select.destruct;dhtmlXForm.prototype.items.editor.doEn=dhtmlXForm.prototype.items.select.enable;dhtmlXForm.prototype.items.editor.doDis=dhtmlXForm.prototype.items.select.disable;
dhtmlXForm.prototype.getEditor=function(a){return this.doWithItem(a,"getEditor")};

//v.3.6 build 130417

/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
You allowed to use this component or parts of it under GPL terms
To use it on other terms or get Professional edition of the component please contact us at sales@dhtmlx.com
*/