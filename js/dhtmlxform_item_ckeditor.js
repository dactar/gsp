dhtmlXForm.prototype.items.ckeditor = 
{
	render: function(item, data) 
	{
		item._type = "ckeditor";
		var p = document.createElement("DIV");
                p.className = "dhxlist_cont";
                item.appendChild(p);

                var t = document.createElement("TEXTAREA");
                t.name = item._idd;
                t._idd = item._idd;
                t.id = item._idd;
		t.rows = data.rows;

		p.appendChild(t);
                var style = "";
                if (!isNaN(data.inputWidth)) style += "width:"+parseInt(data.inputWidth)+"px;";
                if (!isNaN(data.inputHeight)) style += "height:"+parseInt(data.inputHeight)+"px;";

                t.style.cssText = style;

                CKEDITOR.replace( item._idd);
		return this;
	},

	setValue: function(item, val) 
	{
		item._value = val;
                var oEditor = CKEDITOR.instances[item._idd];
                oEditor.setData( val );
	},
	getValue: function(item) 
	{
		var oEditor = CKEDITOR.instances[item._idd];
		console.log(oEditor.getData())
		return  oEditor.getData();
	},

	destruct: function(item) 
	{
		/* your custom code here */
	},
			
	enable: function(item) 
	{
                item._is_enabled = true;
	},

	disable: function(item) 
	{
		item._is_enabled = false;
		/* your custom code here */
	}
};

dhtmlXForm.prototype.setFormData_ckeditor = function(name, value) 
{
	return this.doWithItem(name, "setValue", value);
};

dhtmlXForm.prototype.getFormData_ckeditor = function(name) 
{
	return this.doWithItem(name, "getValue");
};
