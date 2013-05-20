Element.implement(
{
	getSelectedText:function(){
		if(Browser.Engine.trident){
			this.focus();
			return document.selection.createRange().text;
		}
		return this.getAttribute("value").substring(this.selectionStart,this.selectionEnd);
	},
	wrapSelectedText:function(b,k,a,g){
		g=(g==null)?true:g;
		var j=this.scrollTop;
		this.focus();
		if(Browser.Engine.trident){
			var i=document.selection.createRange();
			i.text=k+b+a;
			if(g){
				i.select();
			}
		}
		else{
			var c=this.selectionStart;
			var d=this.selectionEnd;
			this.value=this.getAttribute("value").substring(0,c)+k+b+a+this.get("value").substring(d);
			if(g==false){
				this.setSelectionRange(c+k.length,c+k.length+b.length);
			}
			else{
				var h=c+b.length+k.length+a.length;
				this.setSelectionRange(h,h);
			}
		}
		this.scrollTop=j;
	},
	replaceSelectedText:function(b,a){
		a=(a==null)?true:a;
		this.wrapSelectedText(b,"","",a);
	}
});

var _currentElement="";
var _previewActive=false;

function ToggleOrSwap(a){
	e=document.getElementById(a);
	if(e){
		if(e.getStyle("display")=="none"){
			if(_currentElement!=""){
				_currentElement.setStyle("display","none");
			}
			e.setStyle("display","block");
			_currentElement=e;
		}
		else{
			e.setStyle("display","none");
			_currentElement="";
		}
	}
}

function ToggleOrSwapPreview(b){
	e=document.getElementById("bbcode-preview");
	f=document.getElementById("bbcode-message");
	if(e){
		if(e.getStyle("display")=="none"||e.getProperty("class")!=b){
			e.setStyle("display","block");
			if(b=="bbcode-preview-right"){
				f.setStyle("width","47%");
			}
			else{
				f.setStyle("width","95%");
			}
			_previewActive=true;
			PreviewHelper();
		}
		else{
			e.setStyle("display","none");
			f.setStyle("width","95%");
			_previewActive=false;
		}
		e.setProperty("class",b);
		var a=f.getStyle("height");
		e.setStyle("height",f.getStyle("height"));
	}
}
	
function GenerateColorPalette(h,c){
	var j=0,i=0,a=0;
	var k=new Array(6);
	var d="";
	k[0]="00";
	k[1]="44";
	k[2]="88";
	k[3]="BB";
	k[4]="FF";
	document.writeln('<table id="bbcode-colortable" class="bbcode-colortable" cellspacing="1" cellpadding="0" border="0" style="width: 100%;">');
	for(j=0;j<5;j++){
		document.writeln("<tr>");
		for(i=0;i<5;i++){
			for(a=0;a<5;a++){
				d=String(k[j])+String(k[i])+String(k[a]);
				document.writeln('<td style="background-color:#'+d+"; width: "+h+"; height: "+c+';">&nbsp;</td>');
			}
		}
		document.writeln("</tr>");
	}
	document.writeln("</table>");
}

var bbcode=new Class(
	{
		Implements:Options,
		options:{
			displatchChangeEvent:false,
			changeEventDelay:1000,
			interceptTabs:true
		},
		initialize:function(b,c,a){
			this.el=document.getElementById(b);
			this.setOptions(a);
			if(this.options.dispatchChangeEvent){
				this.el.addEvents(
				{
					focus:function(){
						this.timer=this.watchChange.periodical(this.options.changeEventDelay,this);
					}.bind(this),
					blur:function(){
						this.timer=$clear(this.timer);
					}.bind(this)
				});
			}
			if(this.options.interceptTabs){
				this.el.addEvent("keypress",
				function(d){
					d=new Event(d);
					if(d.key=="tab"){
						d.preventDefault();
						this.replaceSelection("\t");
					}
				}.bind(this));
			}
			if(!$defined(c)||c==""){
				c=new Element("li");
				c.inject(this.el,"before");
				this.list=c;
			}
			else{
				this.list=document.getElementById(c);
			}
			this.oldContent=this.el.get("value");
		},
		watchChange:function(){
			if(this.oldContent!=this.el.get("value")){
				this.oldContent=this.el.get("value");
				this.el.fireEvent("change");
			}
		},
		getSelection:function(){
			return this.el.getSelectedText();
		},
		wrapSelection:function(c,a,b){
			b=(b==null)?true:b;
			this.el.wrapSelectedText(this.el.getSelectedText(),c,a,b);
		},
		insert:function(g,b,d){
			d=(d==null)?true:d;
			var h="";
			var a="";
			b=(b=="")?"after":b;
			var c=(b=="before")?h=g:a=g;
			this.el.wrapSelectedText(this.el.getSelectedText(),h,a,d);
		},
		replaceSelection:function(b,a){
			a=(a==null)?true:a;
			this.el.replaceSelectedText(b,a);
		},
		processEachLine:function(d,c){
			c=(c==null)?true:c;
			var a=this.el.getSelectedText().split("\n");
			var b=[];
			a.each(function(g){
				if(g!=""){
					b.push(d.attempt(g,this));
				}
				else{
					b.push("");
				}
			}.bind(this));
			this.el.replaceSelectedText(b.join("\n"),c);
		},
		getValue:function(){
			return this.el.get("value");
		},
		setValue:function(a){
			this.el.set("value",a);
			this.el.focus();
		},
		addFunction:function(c,g,b){
			var d=new Element("li");
			var a=new Element("a",{
				events:{
					click:function(h){
						new Event(h).stop();
						g.attempt(null,this);
					}.bind(this)
				},
				href:"#"});
			a.set("html","<span>"+c+"</span>");
			a.setProperties(b||{});
			a.inject(d,"bottom");
			d.injectInside(this.list);
		}
	}
);

function InsertCode(){
	var a=document.getElementById("codetype").get("value");
	if(a!=""){
		a=" type="+a;
	}
	bbcode.wrapSelection("[code"+a+"]","[/code]",false);
	ToggleOrSwap("bbcode-code-options");
}

function InsertImageLink(){
	var a=document.getElementById("bbcode-image_size").get("value");
	if(a==""){
		bbcode.replaceSelection("[img]"+document.getElementById("bbcode-image_url").getAttribute("value")+"[/img]");
	}
	else{
		bbcode.replaceSelection("[img size="+a+"]"+document.getElementById("bbcode-image_url").getAttribute("value")+"[/img]");
	}
	ToggleOrSwap("bbcode-image-options");
}

function GrowShrinkMessage(g){
	var a=document.getElementById("bbcode-message");
	var d=document.getElementById("bbcode-preview");
	var b=parseInt(a.getStyle("height"));
	var c=b+g;
	if(c>100){
		a.setStyle("height",c+"px");
		d.setStyle("height",c+"px");
	}
	else{
		a.setStyle("height","100px");
		d.setStyle("height","100px");
	}
}

function myValidate(a){
	if(document.formvalidator.isValid(a)){
		return true;
	}
	return false;
}

function cancelForm(){
	document.forms.postform.action.value="cancel";
	return true;
}

var __attachment_counter=0;

function newAttachment(){
	if(__attachment_counter<"8"){
		__attachment_counter++;
	}
	else{
		return false;
	}
	var d=document.getElementById("attachment-id");
	if(!d){
		return;
	}
	d.setStyle("display","none");
	d.getElement("input").setProperty("value","");
	var b=d.retrieve("nextid",1);
	d.store("nextid",b+1);
	var c=d.clone().inject(d,"before").set("id","attachment"+b).setStyle("display");
	c.getElement("span.attachment-id-container").set("text",b+". ");
	var a=c.getElement("input.file-input").set("name","attachment"+b).removeProperty("onchange");
	a.addEvent("change",
		function(){
			this.removeEvents("change");
			var g=this.get("value");
			if(g.lastIndexOf("\\")>-1){
				g=g.substring(1+g.lastIndexOf("\\"));
			}
			this.addEvent("change",
				function(){
					c.getElement("input.file-input-textbox").set("value",g);
				}
			);
			c.getElement("input.file-input-textbox").set("value",g);
			c.getElement(".attachment-insert").removeProperty("style").addEvent("click",
				function(){
					bbcode.insert("\n[attachment:"+b+"]"+g+"[/attachment]\n","after",true);
					return false;
				}
			);
			c.getElement(".attachment-remove").removeProperty("style").addEvent("click",
				function(){
					c.dispose();
					return false;
				}
			);
			newAttachment();
		}
	);
}

function bindAttachments(){var a=$$(".attachment-old");if(!a){return;}a.each(function(b){b.getElement(".attachment-insert").removeProperty("style").addEvent("click",function(){bbcode.replaceSelection("\n[attachment="+b.getElement("input").get("value")+"]"+b.getElement(".kfilename").get("text")+"[/attachment]\n","after",true);return false;});});}function IEcompatibility(){if(Browser.Engine.trident){var a=$$("#bbcode-size-options","#bbcode-size-options span","#bbcode-colortable","#bbcode-colortable td");if(a){a.setProperty("unselectable","on");}}}Selectors.Pseudo.selected=function(){return(this.selected&&this.get("tag")=="option");};function InsertVideo1(){var d=document.getElementById("videosize").getAttribute("value");var a=document.getElementById("videowidth").getAttribute("value");if(a==""){a="425";}var c=document.getElementById("videoheight").getAttribute("value");if(c==""){c="344";}
	var g=document.getElementById("videoprovider").getAttribute("value");if(g==""){g="";}
	var b=document.getElementById("videoid").getAttribute("value");bbcode.replaceSelection("[video"+(d?" size="+d:"")+" width="+a+" height="+c+" type="+g+"]"+b+"[/video]",false);
	ToggleOrSwap("bbcode-video-options");}
	
function InsertVideo2(){
	var a=document.getElementById("videourl").getAttribute("value");
	bbcode.replaceSelection("[video]"+a+"[/video]",false);
	ToggleOrSwap("bbcode-video-options");
}

function kunenaSelectUsername(b,a){
	if(b.get("checked")){
		document.getElementById("authorname").setAttribute("value",kunena_anonymous_name).removeProperty("disabled");
		document.getElementById("anynomous-check-name").removeProperty("style");
	}
	else{
		document.getElementById("anynomous-check-name").setStyle("display","none");document.getElementById("authorname").setAttribute("value",a).set("disabled","disabled");
	}
}

window.addEvent("domready",function(){
	if(typeof(kunena_anonymous_check_url)=="undefined"){
		return;
	}
	if(document.getElementById("postcatid")!=undefined){
		document.getElementById("postcatid").getElements("option").each(function(c){c.addEvent("click",function(h){var d=kunena_ajax_url_poll;var g=new Request.JSON({url:d,onComplete:function(i){
			if(i.allowed_polls!=null&&i.allowed_polls.indexOf(c.value)>=0){
				document.getElementById("poll-hide-not-allowed").removeProperty("style");
				document.getElementById("bbcode-separator5").removeProperty("style");
				document.getElementById("bbcode-poll-button").removeProperty("style");
				document.getElementById("poll-not-allowed").setAttribute("text"," ");
			}
			else{
				document.getElementById("bbcode-separator5").setStyle("display","none");
				document.getElementById("bbcode-poll-button").setStyle("display","none");
				document.getElementById("poll-hide-not-allowed").setStyle("display","none");
				if(i.allowed_polls!=null){
					document.getElementById("poll-not-allowed").setAttribute("text",KUNENA_POLL_CATS_NOT_ALLOWED);
				}
				else{
					if(i.error){
						document.getElementById("poll-not-allowed").setAttribute("text",i.error);
					}
					else{
						document.getElementById("poll-not-allowed").setAttribute("text","Unknown error!");
					}
				}
			}
		},onFailure:function(){document.getElementById("poll-hide-not-allowed").setStyle("display","none");
			document.getElementById("poll-not-allowed").setAttribute("text","Cannot contact server!");}}).send();});});}if(typeof(kunena_anonymous_check_url)!="undefined"&&document.getElementById("authorname")!=undefined){var a=document.getElementById("authorname").getAttribute("value");
				
				var b=document.getElementById("kanonymous");
				kunenaSelectUsername(b,a);
				b.addEvent("click",function(c){
					kunenaSelectUsername(this,a);});}
					
					
					if(typeof(kunena_anonymous_check_url)!="undefined"&&document.getElementById("postcatid")!=undefined){
						document.getElementById("postcatid").getElements("option").each(function(c){c.addEvent("click",function(h){var d=kunena_anonymous_check_url;var g=new Request.JSON({url:d,onComplete:function(i){if(i.allowed_anonymous!=null&&i.allowed_anonymous.indexOf(c.value)>=0){document.getElementById("anynomous-check").removeProperty("style");
							}
							else{
								document.getElementById("anynomous-check").setStyle("display","none");
								b.removeProperty("checked");
							}
							kunenaSelectUsername(b,a);
						}
					}).send();
				});
			});
			}
		});
// <![CDATA[		
var kunena_toggler_close = "Collapse";
var kunena_toggler_open = "Expand";
// ]]>
var arrayanynomousbox={};
var pollcategoriesid = {};
// <![CDATA[
	   var KUNENA_POLL_CATS_NOT_ALLOWED = "The polls are not allowed in this category";
	   var KUNENA_EDITOR_HELPLINE_OPTION = "Poll: Option for the poll";
	   var KUNENA_POLL_OPTION_NAME = "Option";
	   var KUNENA_POLL_NUMBER_OPTIONS_MAX_NOW = "The maximum number of poll options has been reached.";
	   var KUNENA_ICON_ERROR = "http://webtrees.net/components/com_kunena/template/default/images/icons/publish_x.png";
	   var kunena_ajax_url_poll = "/en/forums/json?action=pollcatsallowed";
// ]]>
// <![CDATA[
function PreviewHelper()
{
	if (_previewActive == true){
		previewRequest = new Request.JSON({url: "/en/forums/json?action=preview",
				  							onSuccess: function(response){
			var __message = document.getElementById("bbcode-preview");
			if (__message) {
				__message.set("html", response.preview);
			}
			}}).post({body: document.getElementById("bbcode-message").getAttribute("value")
		});
	}
}

window.addEvent('domready', 
	function() {
		bbcode = new bbcode('bbcode-message', 'bbcode-toolbar',
			{
				dispatchChangeEvent: true,
				changeEventDelay: 1000,
				interceptTab: true
			}
		);

		bbcode.addFunction('Bold', 
			function() {
				this.wrapSelection('[b]', '[/b]', false);
			},
			{
				'id': 'bbcode-bold-button',
				'title': 'Bold',
				'alt': 'Bold text: [b]text[/b]',
				'onmouseover' : "javascript:document.getElementById('helpbox').setAttribute('value','Bold text: [b]text[/b]');"
			}
		);

		bbcode.addFunction('Italic', 
			function() {
				this.wrapSelection('[i]', '[/i]', false);
			},
			{
				'id': 'bbcode-italic-button',
				'title': 'Italic',
				'alt': 'Italic text: [i]text[/i]',
				'onmouseover' : "javascript:document.getElementById('helpbox').setAttribute('value','Italic text: [i]text[/i]');"
			}
		);

		bbcode.addFunction('Underline', 
			function() {
				this.wrapSelection('[u]', '[/u]', false);
			},
			{
				'id': 'bbcode-underline-button',
				'title': 'Underline',
				'alt': 'Underline text: [u]text[/u]',
				'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "Underline text: [u]text[/u]")'
			}
		);

		bbcode.addFunction('Strike', 
			function() {
				this.wrapSelection('[strike]', '[/strike]', false);
			},
			{
				'id': 'bbcode-strike-button',
				'title': 'Strikethrough',
				'alt': 'Strikethrough Text: [strike]Text[/strike]',
				'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "Strikethrough Text: [strike]Text[/strike]")'
			}
		);

		bbcode.addFunction('Sub', 
			function() {
				this.wrapSelection('[sub]', '[/sub]', false);
			},
			{
				'id': 'bbcode-sub-button',
				'title': 'Subscript',
				'alt': 'Subscript Text: [sub]Text[/sub]',
				'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "Subscript Text: [sub]Text[/sub]")'
			}
		);

		bbcode.addFunction('Sup', 
			function() {
				this.wrapSelection('[sup]', '[/sup]', false);
			},
			{
				'id': 'bbcode-sup-button',
				'title': 'Superscript',
				'alt': 'Superscript Text: [sup]Text[/sup]',
				'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "Superscript Text: [sup]Text[/sup]")'
			}
		);

bbcode.addFunction('Size', function() {
	ToggleOrSwap("bbcode-size-options");
}, {'id': 'bbcode-size-button',
	'title': 'Font size',
	'alt': 'Fontsize: Select Fontsize and Apply to current selection',
	'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "Fontsize: Select Fontsize and Apply to current selection")'});

bbcode.addFunction('Color', function() {
	ToggleOrSwap("bbcode-colorpalette");
}, {'id': 'bbcode-color-button',
	'title': 'Color',
	'alt': 'Color: [color=#FF6600]text[/color]',
	'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "Color: [color=#FF6600]text[/color]")'});

bbcode.addFunction('#', function() {
}, {'id': 'bbcode-separator1'});

bbcode.addFunction("uList", function() {
	selection = this.getSelection();
	if (selection == "") {
		this.wrapSelection("\n[ul]\n  [li]", "[/li]\n  [li][/li]\n[/ul]", false);
	}
	else {
		this.processEachLine(function(line) {
			newline = "  [li]" + line + "[/li]";
			return newline;
		}, false);
		this.insert("[ul]\n", "before", false);
		this.insert("\n[/ul]\n", "after", true); //now isLast is set to true, because it is the last one!
	}
}, {'id': 'bbcode-ulist-button',
	'title': 'Unordered List',
	'alt': 'Unordered List: [ul] [li]text[/li] [/ul] - Tip: a list must contain List Items',
	'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "Unordered List: [ul] [li]text[/li] [/ul] - Tip: a list must contain List Items")'});

bbcode.addFunction("oList", function() {
	selection = this.getSelection();
	if (selection == "") {
		this.wrapSelection("\n[ol]\n  [li]", "[/li]\n  [li][/li]\n[/ol]", false);
	}
	else {
		this.processEachLine(function(line) {
			newline = "  [li]" + line + "[/li]";
			return newline;
		}, false);
		this.insert("[ol]\n", "before", false);
		this.insert("\n[/ol]\n", "after", true); //now isLast is set to true, because it is the last one!
	}
}, {'id': 'bbcode-olist-button',
	'title': 'Ordered List',
	'alt': 'Ordered List: [ol] [li]text[/li] [/ol] - Tip: a list must contain List Items',
	'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "Ordered List: [ol] [li]text[/li] [/ol] - Tip: a list must contain List Items")'});

bbcode.addFunction('List', function() {
	this.wrapSelection('  [li]', '[/li]', false);
}, {'id': 'bbcode-list-button',
	'title': 'List Item',
	'alt': 'List Item: [li] list item [/li]',
	'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "List Item: [li] list item [/li]")'});

bbcode.addFunction('Left', function() {
	this.wrapSelection('[left]', '[/left]', false);
}, {'id': 'bbcode-left-button',
	'title': 'Align left',
	'alt': 'Align left: [left]Text[/left]',
	'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "Align left: [left]Text[/left]")'});

bbcode.addFunction('Center', function() {
	this.wrapSelection('[center]', '[/center]', false);
}, {'id': 'bbcode-center-button',
	'title': 'Align center',
	'alt': 'Align center: [center]Text[/center]',
	'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "Align center: [center]Text[/center]")'});

bbcode.addFunction('Right', function() {
	this.wrapSelection('[right]', '[/right]', false);
}, {'id': 'bbcode-right-button',
	'title': 'Align right',
	'alt': 'Align right: [right]Text[/right]',
	'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "Align right: [right]Text[/right]")'});

bbcode.addFunction('#', function() {
}, {'id': 'bbcode-separator2'});

bbcode.addFunction('Quote', function() {
	this.wrapSelection('[quote]', '[/quote]', false);
}, {'id': 'bbcode-quote-button',
	'title': 'Quote',
	'alt': 'Quote text: [quote]text[/quote]',
	'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "Quote text: [quote]text[/quote]")'});

bbcode.addFunction('Code', function() {
	this.wrapSelection('[code]', '[/code]', false);
}, {'id': 'bbcode-code-button',
	'title': 'Code',
	'alt': 'Code display: [code]code[/code]',
	'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "Code display: [code]code[/code]")'});

bbcode.addFunction("Table", function() {
	selection = this.getSelection();
	if (selection == "") {
		this.wrapSelection("\n[table]\n  [tr]\n    [td]", "[/td]\n    [td][/td]\n  [/tr]\n  [tr]\n    [td][/td]\n    [td][/td]\n  [/tr]\n[/table]", false);
	}
	else {
		this.processEachLine(function(line) {
			newline = "  [tr][td]" + line + "[/td][/tr]";
			return newline;
		}, false);
		this.insert("[table]\n", "before", false);
		this.insert("\n[/table]\n", "after", true); //now isLast is set to true, because it is the last one!
	}
}, {'id': 'bbcode-table-button',
	'title': 'Table',
	'alt': 'Create an embedded table: [table][tr][td]line1[/td][/tr][tr][td]lines[/td][/tr][/table]',
	'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "Create an embedded table: [table][tr][td]line1[/td][/tr][tr][td]lines[/td][/tr][/table]")'});


bbcode.addFunction('Hide', function() {
	this.wrapSelection('[hide]', '[/hide]', false);
}, {'id': 'bbcode-hide-button',
	'title': 'Hide text from Guests',
	'alt': 'Hidden text: [hide]any hidden text[/hide] - hide part of message from Guests',
	'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "Hidden text: [hide]any hidden text[/hide] - hide part of message from Guests")'});

bbcode.addFunction('#', function() {
}, {'id': 'bbcode-separator3'});

bbcode.addFunction('Image', function() {
	ToggleOrSwap("bbcode-image-options");
}, {'id': 'bbcode-image-button',
	'title': 'Image link',
	'alt': 'Image link: [img size=400]http://www.google.com/images/web_logo_left.gif[/img]',
	'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "Image link: [img size=400]http://www.google.com/images/web_logo_left.gif[/img]")'});

bbcode.addFunction('Link', function() {
	sel = this.getSelection();
	if (sel != "") {
		document.getElementById('bbcode-link_text').setAttribute('value', sel);
	}
	ToggleOrSwap("bbcode-link-options");
}, {'id': 'bbcode-link-button',
	'title': 'Link',
	'alt': 'Link: [url=http://www.zzz.com/]This is a link[/url]',
	'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "Link: [url=http://www.zzz.com/]This is a link[/url]")'});

bbcode.addFunction('#', function() {
}, {'id': 'bbcode-separator4'});


bbcode.addFunction('Map', function() {
	this.wrapSelection('[map]', '[/map]', false);
}, {'id': 'bbcode-map-button',
	'title': 'Map',
	'alt': 'Insert a Map into the message: [map]Address[/map]',
	'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "Insert a Map into the message: [map]Address[/map]")'});


bbcode.addFunction('#', function() {
}, {'id': 'bbcode-separator6'});

bbcode.addFunction('PreviewBottom', function() {
	ToggleOrSwapPreview("bbcode-preview-bottom");
}, {'id': 'bbcode-previewbottom-button',
	'title': 'Preview Pane Below',
	'alt': 'Display a live message preview pane below the edit area',
	'onmouseover' : 'document.getElementById("helpbox").setAttribute("value", "Display a live message preview pane below the edit area")'});

bbcode.addFunction('PreviewRight', function() {
	ToggleOrSwapPreview("bbcode-preview-right");
}, {'id': 'bbcode-previewright-button',
	'title': 'Preview Pane Right',
	'alt': 'Display a live message preview pane to the right of the edit area',
	'onmouseover' : "javascript:document.getElementById('helpbox').setAttribute('value','Display a live message preview pane to the right of the edit area');"});

bbcode.addFunction('#', function() {
}, {'id': 'bbcode-separator7'});


bbcode.addFunction('Help', function() {
	window.open('http://docs.kunena.org/index.php/bbcode');
}, {'id': 'bbcode-help-button',
	'title': 'Help',
	'alt': 'Get Help on how to use the bbcode editor',
	'onmouseover' : "javascript:document.getElementById('helpbox').setAttribute('value', 'Get Help on how to use the bbcode editor');"});

document.getElementById('bbcode-message').addEvent('change', function(){
	PreviewHelper();
});

	var color = $$("table.bbcode-colortable td");
	if (color) {
		color.addEvent("click", function(){
			var bg = this.getStyle( "background-color" );
			bbcode.wrapSelection('[color='+ bg +']', '[/color]', false);
			ToggleOrSwap("bbcode-colorpalette");
		});
	}
	var size = $$("div#bbcode-size-options span");
	if (size) {
		size.addEvent("click", function(){
			var tag = this.get( "title" );
			bbcode.wrapSelection(tag , '[/size]', false);
			ToggleOrSwap("kbbcode-size-options");
		});
	}

	bindAttachments();
	newAttachment();
	//This is need to retrieve the video provider selected by the user in the dropdownlist
	if (document.getElementById('videoprovider') != undefined) {
		document.getElementById('videoprovider').addEvent('change', function() {
			var sel = $$('#videoprovider option:selected');
			sel.each(function(el) {
				document.getElementById('videoprovider').store('videoprov',el.value);
			});
		});
	}

	// Fianlly apply some screwy IE7 and IE8 fixes to the html...
	IEcompatibility();
});
// ]]>
// <![CDATA[

window.addEvent('domready', function(){

	function kunenaSelectUsername(obj, kuser) {
		if (obj.getAttribute('checked')) {
			document.getElementById('authorname').setAttribute('value',kunena_anonymous_name).removeProperty('disabled');
			document.getElementById('anynomous-check-name').setStyle('display');
		} else {
			document.getElementById('anynomous-check-name').setStyle('display','none');
			document.getElementById('authorname').setAttribute('value',kuser).setAttribute('disabled', 'disabled');
		}
	}

	function kunenaCheckPollallowed(catid) {
		if ( pollcategoriesid[catid] !== undefined ) {
			document.getElementById('poll-hide-not-allowed').setStyle('display');
			document.getElementById('bbcode-separator5').setStyle('display');
			document.getElementById('bbcode-poll-button').setStyle('display');
			document.getElementById('poll-not-allowed').set('text', ' ');
		} else {
			document.getElementById('bbcode-separator5').setStyle('display','none');
			document.getElementById('bbcode-poll-button').setStyle('display','none');
			document.getElementById('poll-hide-not-allowed').setStyle('display','none');
		}
	}

	function kunenaCheckAnonymousAllowed(catid) {
		if ( arrayanynomousbox[catid] !== undefined ) {
			document.getElementById('anynomous-check').setStyle('display');
		} else {
			document.getElementById('anynomous-check').setStyle('display','none');
			button.removeProperty('checked');
		}

		if ( arrayanynomousbox[catid] ) {
			document.getElementById('anonymous').setAttribute('checked','checked');
		}
				kunenaSelectUsername(kbutton,kuser);
			}
	//	for hide or show polls if category is allowed
	if(document.getElementById('postcatid') !== null) {
		document.getElementById('postcatid').addEvent('change', function(e) {
			kunenaCheckPollallowed(this.value);
		});
	}

	if(document.getElementById('authorname') !== undefined) {
		if(document.getElementById('authorname') !== null) {
			var kuser = document.getElementById('authorname').getAttribute('value');
			var kbutton = document.getElementById('anonymous');
			kunenaSelectUsername(kbutton, kuser);
			kbutton.addEvent('click', function(e) {
				kunenaSelectUsername(this, kuser);
			});
		}
	}
	//	to select if anynomous option is allowed on new topic tab
	if(document.getElementById('postcatid') !== null) {
		document.getElementById('postcatid').addEvent('change', function(e) {
			var postcatid = document.getElementById('postcatid').value;
			kunenaCheckAnonymousAllowed(postcatid);
		});
	}

	if(document.getElementById('postcatid') !== null) {
		kunenaCheckPollallowed(document.getElementById('postcatid').getSelected().getAttribute("value"));
		kunenaCheckAnonymousAllowed(document.getElementById('postcatid').getSelected().getAttribute("value"));
	}
});


// ]]>
window.addEvent("domready", function() {
	var JTooltips = new Tips($$(".hasTip"), { maxTitleChars: 50, fixed: false});
});
function keepAlive() { new Ajax("index.php", {method: "get"}).request(); }
window.addEvent("domready", function() { keepAlive.periodical(3540000); });

		var kunena_anonymous_name = "Anonymous";
// ]]>
// <![CDATA[
 function ShowDetail(srcElement) {var targetID, srcElement, targetElement, imgElementID, imgElement;targetID = srcElement.getElementById + "_details";imgElementID = srcElement.getElementById + "_img";targetElement = document.getElementById(targetID);imgElement = document.getElementById(imgElementID);if (targetElement.style.display == "none") {targetElement.style.display = "";imgElement.src = "http://webtrees.net//components/com_kunena/template/default/images/emoticons/w00t.png";} else {targetElement.style.display = "none";imgElement.src = "http://webtrees.net//components/com_kunena/template/default/images/emoticons/pinch.png";}}
// ]]>