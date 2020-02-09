// File Version: 1
// Hack's Park Shoutbox: www.hackspark.com

var shoutbox = new Object();
shoutbox.loading = false;
shoutbox.limit = 0;
shoutbox.sc = '';

// lang
shoutbox.lang = new Object();
shoutbox.lang.editmsg = 'lang:editmsg';
shoutbox.lang.tooshort = 'lang:tooshort';
shoutbox.lang.toolong = 'lang:toolong';
shoutbox.lang.outstyle = 'lang:outstyle';
shoutbox.lang.emptyusers = 'lang:emptyusers';
shoutbox.lang.wreason = 'lang:wreason';
shoutbox.lang.wdays = 'lang:wdays';

function Shoutbox_GetMsgs(s)
{
	if (shoutbox.loading)
		return;

	shoutbox.loading = true;
	document.getElementById("shoutbox_status").style.visibility = 'visible';

	start = typeof(s) == 'undefined' ? 0 : s;
	shoutbox.limit = typeof(s) == 'undefined' ? 0 : shoutbox.limit;

	getXMLDocument(smf_scripturl + "?action=shoutbox;sa=moderate_getmsgs;xml;start=" + start + ";limit=" + shoutbox.limit, Shoutbox_PutMsgs);
}

function Shoutbox_PutMsgs(XMLDoc)
{
	if (XMLDoc.getElementsByTagName("msgs")[0])
		setInnerHTML(document.getElementById("shoutbox_content"), XMLDoc.getElementsByTagName("msgs")[0].childNodes[0].nodeValue);

	if (XMLDoc.getElementsByTagName("limit")[0])
		shoutbox.limit = XMLDoc.getElementsByTagName("limit")[0].childNodes[0].nodeValue;

	// pageindex
	if (XMLDoc.getElementsByTagName("pageindex")[0])
		setInnerHTML(document.getElementById("shoutbox_pageindex"), XMLDoc.getElementsByTagName("pageindex")[0].childNodes[0].nodeValue);

	// alert any msg
	if (XMLDoc.getElementsByTagName("msg")[0])
		window.alert(XMLDoc.getElementsByTagName("msg")[0].childNodes[0].nodeValue);

	shoutbox.loading = false;
	document.getElementById("shoutbox_status").style.visibility = 'hidden';
}

shoutbox.maxlength = 1024; // max
shoutbox.minlength = 1; // min
function Shoutbox_EditMsg(m,s)
{
	if (shoutbox.loading)
		return;

	var edit = window.prompt(shoutbox.lang.editmsg, m);

	if (!edit)
		return;
	if (edit.length > shoutbox.maxlength)
		return window.alert(shoutbox.lang.toolong + shoutbox.maxlength);
	if (edit.length < shoutbox.minlength)
		return window.alert(shoutbox.lang.tooshort + shoutbox.minlength);

	var style = window.confirm(shoutbox.lang.outstyle)
	// nothing to do, nothing to edit
	if (style && edit == m)
		return;

	shoutbox.loading = true;
	document.getElementById("shoutbox_status").style.visibility = 'visible';

	var x = new Array();
	if (edit != m)
		x[x.length] = 'msg=' + escape(Shoutbox_toEntities(edit.replace(/&#/g, "&#38;#"))).replace(/\+/g, "%2B");
	if (!style)
		x[x.length] = 'style=1';
	x[x.length] = 'shout=' + s;

	sendXMLDocument(smf_scripturl + "?action=shoutbox;sa=moderate_editmsg;sesc=" + shoutbox.sc + ";xml;start=0;limit=" + shoutbox.limit, x.join("&"), Shoutbox_PutMsgs);
}

function Shoutbox_DeleteMsg(s)
{
	if (shoutbox.loading)
		return;

	shoutbox.loading = true;
	document.getElementById("shoutbox_status").style.visibility = 'visible';

	getXMLDocument(smf_scripturl + "?action=shoutbox;sa=moderate_editmsg;sesc=" + shoutbox.sc + ";xml;start=0;limit=" + shoutbox.limit + ";delete;shout=" + s, Shoutbox_PutMsgs);
}

function Shoutbox_PruneMsgs()
{
	if (shoutbox.loading)
		return;

	shoutbox.loading = true;
	document.getElementById("shoutbox_status").style.visibility = 'visible';

	getXMLDocument(smf_scripturl + "?action=shoutbox;sa=moderate_getmsgs;xml;start=0;limit=0;prune", Shoutbox_PutMsgs);
}

function Shoutbox_GetUsers()
{
	if (shoutbox.loading)
		return;

	shoutbox.loading = true;
	document.getElementById("shoutbox_status").style.visibility = 'visible';

	getXMLDocument(smf_scripturl + "?action=shoutbox;sa=moderate_getusers;xml", Shoutbox_PutUsers);
}

function Shoutbox_PutUsers(XMLDoc)
{
	if (XMLDoc.getElementsByTagName("list")[0])
		setInnerHTML(document.getElementById("shoutbox_content"), XMLDoc.getElementsByTagName("list")[0].childNodes[0].nodeValue);

	// pageindex
	if (XMLDoc.getElementsByTagName("pageindex")[0])
		setInnerHTML(document.getElementById("shoutbox_pageindex"), XMLDoc.getElementsByTagName("pageindex")[0].childNodes[0].nodeValue);

	// alert any msg
	if (XMLDoc.getElementsByTagName("msg")[0])
		window.alert(XMLDoc.getElementsByTagName("msg")[0].childNodes[0].nodeValue);

	shoutbox.loading = false;
	document.getElementById("shoutbox_status").style.visibility = 'hidden';
}

function Shoutbox_BanUsers()
{
	// print the form :P
	if (shoutbox.loading)
		return;

	shoutbox.loading = true;
	document.getElementById("shoutbox_status").style.visibility = 'visible';

	getXMLDocument(smf_scripturl + "?action=shoutbox;sa=moderate_banusers;xml", Shoutbox_FormBan);
}

function Shoutbox_FormBan(XMLDoc)
{
	if (XMLDoc.getElementsByTagName("form")[0])
		setInnerHTML(document.getElementById("shoutbox_content"), XMLDoc.getElementsByTagName("form")[0].childNodes[0].nodeValue);

	// pageindex
	if (XMLDoc.getElementsByTagName("pageindex")[0])
		setInnerHTML(document.getElementById("shoutbox_pageindex"), XMLDoc.getElementsByTagName("pageindex")[0].childNodes[0].nodeValue);

	// alert any msg
	if (XMLDoc.getElementsByTagName("msg")[0])
		window.alert(XMLDoc.getElementsByTagName("msg")[0].childNodes[0].nodeValue);

	shoutbox.loading = false;
	document.getElementById("shoutbox_status").style.visibility = 'hidden';
}

function Shoutbox_SendUsers()
{
	if (shoutbox.loading)
		return;

	// users
	var users = document.getElementById('form_users');
	// reason
	var reason = document.getElementById('form_reason');
	// days for expire
	var days = document.getElementById('form_days');

	if (!users || !users.value || users.value == '')
		return window.alert(shoutbox.lang.emptyusers);

	users = users.value;
	reason = !reason || !reason.value ? '' : reason.value;

	var expr = /^[0-9]{1,3}$/;
	days = !days || !days.value || days.value == '' || !expr.test(days.value) || parseInt(days.value) > 362 ? 0 : days.value;

	shoutbox.loading = true;
	document.getElementById("shoutbox_status").style.visibility = 'visible';

	var x = new Array();
	x[x.length] = 'users=' + escape(Shoutbox_toEntities(users.replace(/&#/g, "&#38;#"))).replace(/\+/g, "%2B");
	x[x.length] = 'reason=' + escape(Shoutbox_toEntities(reason.replace(/&#/g, "&#38;#"))).replace(/\+/g, "%2B");
	x[x.length] = 'days=' + days;

	sendXMLDocument(smf_scripturl + "?action=shoutbox;sa=moderate_banusers;xml;sesc=" + shoutbox.sc, x.join("&"), Shoutbox_FormBan);
}

function Shoutbox_DeleteUser(u)
{
	if (shoutbox.loading)
		return;

	shoutbox.loading = true;
	document.getElementById("shoutbox_status").style.visibility = 'visible';

	getXMLDocument(smf_scripturl + "?action=shoutbox;sa=moderate_getusers;xml;sesc=" + shoutbox.sc + ";delete=" + u, Shoutbox_PutUsers);
}

function Shoutbox_EditUser(u,d,r)
{
	if (shoutbox.loading)
		return;

	var reason = window.prompt(shoutbox.lang.wreason, r);
	var days = window.prompt(shoutbox.lang.wdays, d);

	if ((!days || days == d) && (!reason || reason == r || reason == ''))
		return;

	var expr = /^[0-9]{1,3}$/;
	days = days == '' || !expr.test(days) || parseInt(days) > 362 ? 0 : days;

	shoutbox.loading = true;
	document.getElementById("shoutbox_status").style.visibility = 'visible';

	var x = new Array();
	x[x.length] = 'user=' + u;
	if (reason && reason != r && reason != '')
		x[x.length] = 'reason=' + escape(Shoutbox_toEntities(reason.replace(/&#/g, "&#38;#"))).replace(/\+/g, "%2B");
	if (days && days != d)
		x[x.length] = 'days=' + days;

	sendXMLDocument(smf_scripturl + "?action=shoutbox;sa=moderate_getusers;sesc=" + shoutbox.sc + ";xml", x.join("&"), Shoutbox_PutUsers);
}

// ...
function Shoutbox_toEntities(text)
{
	var entities = "";
	for (var i = 0; i < text.length; i++)
	{
		if (text.charCodeAt(i) > 127)
			entities += "&#" + text.charCodeAt(i) + ";";
		else
			entities += text.charAt(i);
	}

	return entities;
}