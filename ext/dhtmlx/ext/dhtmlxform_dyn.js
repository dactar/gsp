//v.3.6 build 130417

/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
You allowed to use this component or parts of it under GPL terms
To use it on other terms or get Professional edition of the component please contact us at sales@dhtmlx.com
*/
dhtmlXForm.prototype.addItem=function(b,e,c){var f;var a=null;b instanceof Array&&(a=b[1],b=b[0]);var d=null;if(b!=null&&(d=this._getParentForm(b,a),d!=null)){if(d.item._list==null){if(!e.listParent)e.listParent=d.item._idd;d.form._addItem("list",d.item._idd,[e],null,d.item._idd,c)}else d.item._list[0].addItem(null,e,c);f=d.form=d.item=null,d=f;this._autoCheck();return}this._prepareItem(e,c);this._autoCheck()};dhtmlXForm.prototype.removeItem=function(b,e){this._removeItem(b,e)};
dhtmlXForm.prototype._getParentForm=function(b,e){if(this.itemPull[this.idPrefix+b]!=null)return{form:this,item:this.itemPull[this.idPrefix+b]};for(var c in this.itemPull)if(this.itemPull[c]._type=="ra"&&this.itemPull[c]._group==b&&this.itemPull[c]._value==e)return{form:this,item:this.itemPull[c]};var a=null;for(c in this.itemPull)if(!a&&this.itemPull[c]._list!=null)for(var d=0;d<this.itemPull[c]._list.length;d++)a||(a=this.itemPull[c]._list[d]._getParentForm(b,e));return a};
(function(){for(var b in dhtmlXForm.prototype.items)if(!dhtmlXForm.prototype.items[b]._getItemNode)dhtmlXForm.prototype.items[b]._getItemNode=function(b){return b}})();dhtmlXForm.prototype._getItemNode=function(b,e){e!=null&&(b=[b,e]);return this.doWithItem(b,"_getItemNode")};
dhtmlXForm.prototype.setRequired=function(b,e,c){typeof c=="undefined"?c=e:b=[b,e];var a=this._getItemNode(b);if(a){c=this._s2b(c);a._required=c==!0;if(a._required){if(!a._validate)a._validate=[];for(var d=!1,f=0;f<a._validate.length;f++)d=a._validate[f]=="NotEmpty"||d;d||a._validate.push("NotEmpty");var g=a.childNodes[a._ll?0:1].childNodes[0];if(!g.lastChild||!(g.lastChild.className&&g.lastChild.className.search(/required/)>=0)){var h=document.createElement("SPAN");h.className="dhxform_item_required";
h.innerHTML="*";g.appendChild(h);h=g=null}}else{if(a._validate!=null){d=a._validate;a._validate=[];for(f=0;f<d.length;f++)d[f]!="NotEmpty"&&a._validate.push(d[f]);if(a._validate.length==0)a._validate=null}g=a.childNodes[a._ll?0:1].childNodes[0];g.lastChild&&g.lastChild.className&&g.lastChild.className.search(/required/)>=0&&(g.removeChild(g.lastChild),g=null)}this._resetValidateCss(a);a=null}};
dhtmlXForm.prototype.setNote=function(b,e,c){typeof c=="undefined"?c=e:b=[b,e];var a=this._getItemNode(b);if(a){var d=this._getNoteNode(a);if(!d){if(!c.width)c.width=a.childNodes[a._ll?1:0].childNodes[0].offsetWidth;d=document.createElement("DIV");d.className="dhxform_note";({ch:1,ra:1})[a._type]?a.childNodes[a._ll?1:0].insertBefore(d,a.childNodes[a._ll?1:0].lastChild):a.childNodes[a._ll?1:0].appendChild(d)}d.innerHTML=c.text;if(c.width!=null)d.style.width=c.width+"px",d._w=c.width;d=null}};
dhtmlXForm.prototype.clearNote=function(b,e){typeof e!="undefined"&&(b=[b,e]);var c=this._getItemNode(b);if(c){var a=this._getNoteNode(c);a!=null&&(a.parentNode.removeChild(a),a=null)}};dhtmlXForm.prototype._getNoteNode=function(b){for(var e=null,c=0;c<b.childNodes[b._ll?1:0].childNodes.length;c++)if(String(b.childNodes[b._ll?1:0].childNodes[c].className).search(/dhxform_note/)>=0)e=b.childNodes[b._ll?1:0].childNodes[c];b=null;return e};
dhtmlXForm.prototype.setValidation=function(b,e,c){typeof note=="undefined"?c=e:b=[b,e];var a=this._getItemNode(b);if(a){if(a._validate!=null)for(var d=0;d<a._validate.length;d++)a._validate[d]=null;a._validate=[];a._validate=typeof c=="function"||typeof window[c]=="function"?[c]:String(c).split(this.separator);if(a._required){for(var f=!1,d=0;d<a._validate.length;d++)f=a._validate[d]=="NotEmpty"||f;f||a._validate.push("NotEmpty")}a=null}};
dhtmlXForm.prototype.clearValidation=function(b,e){typeof e!="undefined"&&(b=[b,e]);var c=this._getItemNode(b);if(c){if(c._validate!=null)for(var a=0;a<c._validate.length;a++)c._validate[a]=null;c._validate=c._required?["NotEmpty"]:null;c=null}};

//v.3.6 build 130417

/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
You allowed to use this component or parts of it under GPL terms
To use it on other terms or get Professional edition of the component please contact us at sales@dhtmlx.com
*/