//v.3.6 build 130417

/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
You allowed to use this component or parts of it under GPL terms
To use it on other terms or get Professional edition of the component please contact us at sales@dhtmlx.com
*/
dhtmlXForm.prototype.items.calendar={render:function(a,b){var c=this;a._type="calendar";a._enabled=!0;this.doAddLabel(a,b);this.doAddInput(a,b,"INPUT","TEXT",!0,!0,"dhxform_textarea calendar");this.doAttachChangeLS(a);a.childNodes[a._ll?1:0].childNodes[0]._idd=a._idd;a.childNodes[a._ll?1:0].childNodes[0].onblur=function(){var a=this.parentNode.parentNode;a.getForm()._ccDeactivate(a._idd);c.checkEnteredValue(this.parentNode.parentNode);a.getForm().callEvent("onBlur",[a._idd]);a=null};a._f=b.dateFormat||
null;a._f0=b.serverDateFormat||a._f;a._c=new dhtmlXCalendarObject(a.childNodes[a._ll?1:0].childNodes[0],b.skin||a.getForm().skin||"dhx_skyblue");a._c._nullInInput=!0;a._c.enableListener(a.childNodes[a._ll?1:0].childNodes[0]);a._f!=null&&a._c.setDateFormat(a._f);b.enableTime||a._c.hideTime();isNaN(b.weekStart)||a._c.setWeekStartDay(b.weekStart);typeof b.calendarPosition!="undefined"&&a._c.setPosition(b.calendarPosition);a._c._itemIdd=a._idd;a._c.attachEvent("onBeforeChange",function(b){if(a._value!=
b){if(a.checkEvent("onBeforeChange")&&a.callEvent("onBeforeChange",[a._idd,a._value,b])!==!0)return!1;a._value=b;c.setValue(a,b);a.callEvent("onChange",[this._itemIdd,a._value])}return!0});this.setValue(a,b.value);return this},getCalendar:function(a){return a._c},setSkin:function(a,b){a._c.setSkin(b)},setValue:function(a,b){!b||b==null||typeof b=="undefined"||b==""?(a._value=null,a.childNodes[a._ll?1:0].childNodes[0].value=""):(a._value=b instanceof Date?b:a._c._strToDate(b,a._f0||a._c._dateFormat),
a.childNodes[a._ll?1:0].childNodes[0].value=a._c._dateToStr(a._value,a._f||a._c._dateFormat));a._c.setDate(a._value)},getValue:function(a,b){var c=a._c.getDate();return b===!0&&c==null?"":b===!0?a._c._dateToStr(c,a._f0||a._c._dateFormat):c},setDateFormat:function(a,b,c){a._f=b;a._f0=c||a._f;a._c.setDateFormat(a._f);this.setValue(a,this.getValue(a))},destruct:function(a){a._c.disableListener(a.childNodes[a._ll?1:0].childNodes[0]);a._c.unload();a._c=null;try{delete a._c}catch(b){}a._f=null;try{delete a._f}catch(c){}a._f0=
null;try{delete a._f0}catch(d){}a.childNodes[a._ll?1:0].childNodes[0]._idd=null;a.childNodes[a._ll?1:0].childNodes[0].onblur=null;this.d2(a);a=null},checkEnteredValue:function(a){this.setValue(a,a._c.getDate())}};(function(){for(var a in{doAddLabel:1,doAddInput:1,doUnloadNestedLists:1,setText:1,getText:1,enable:1,disable:1,isEnabled:1,setWidth:1,setReadonly:1,isReadonly:1,setFocus:1,getInput:1})dhtmlXForm.prototype.items.calendar[a]=dhtmlXForm.prototype.items.input[a]})();
dhtmlXForm.prototype.items.calendar.doAttachChangeLS=dhtmlXForm.prototype.items.select.doAttachChangeLS;dhtmlXForm.prototype.items.calendar.d2=dhtmlXForm.prototype.items.input.destruct;dhtmlXForm.prototype.getCalendar=function(a){return this.doWithItem(a,"getCalendar")};dhtmlXForm.prototype.setCalendarDateFormat=function(a,b,c){this.doWithItem(a,"setDateFormat",b,c)};

//v.3.6 build 130417

/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
You allowed to use this component or parts of it under GPL terms
To use it on other terms or get Professional edition of the component please contact us at sales@dhtmlx.com
*/