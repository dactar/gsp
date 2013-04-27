//v.3.6 build 130417

/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
You allowed to use this component or parts of it under GPL terms
To use it on other terms or get Professional edition of the component please contact us at sales@dhtmlx.com
*/
dhtmlXForm.prototype.items.btn2state={setChecked:function(a,b){a._checked=b===!0?!0:!1;a.childNodes[a._ll?1:0].lastChild.className="dhxform_img "+a._cssName+"_"+(a._checked?"1":"0");this.doCheckValue(a)}};(function(){for(var a in dhtmlXForm.prototype.items.checkbox)dhtmlXForm.prototype.items.btn2state[a]||(dhtmlXForm.prototype.items.btn2state[a]=dhtmlXForm.prototype.items.checkbox[a])})();dhtmlXForm.prototype.items.btn2state.render2=dhtmlXForm.prototype.items.btn2state.render;
dhtmlXForm.prototype.items.btn2state.render=function(a,b){b._autoInputWidth=!1;this.render2(a,b);a._type="btn2state";a._cssName=typeof b.cssName=="undefined"?"btn2state":b.cssName;a._updateImgNode=function(){};a._doOnFocus=function(){a.getForm().callEvent("onFocus",[a._idd])};a._doOnBlur=function(){a.getForm().callEvent("onBlur",[a._idd])};a._doOnKeyUpDown=function(a){this.callEvent(a,[this.childNodes[this._ll?0:1].childNodes[0],e,this._idd])};this.setChecked(a,a._checked);return this};
dhtmlXForm.prototype.setFormData_btn2state=function(a,b){this[b==!0||parseInt(b)==1||b=="true"||b==this.getItemValue(a)?"checkItem":"uncheckItem"](a)};dhtmlXForm.prototype.getFormData_btn2state=function(a){return this.isItemChecked(a)?this.getItemValue(a):0};

//v.3.6 build 130417

/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
You allowed to use this component or parts of it under GPL terms
To use it on other terms or get Professional edition of the component please contact us at sales@dhtmlx.com
*/