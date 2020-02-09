/**********************************************************************************
* shoutbox.js                                                                     *
***********************************************************************************
*                                                                                 *
* SMFPacks Shoutbox v1.0                                                          *
* Copyright (c) 2009-2010 by Makito and NIBOGO. All rights reserved.              *
* Powered by www.smfpacks.com                                                     *
* Created by Makito                                                               *
* Developed by NIBOGO for SMFPacks.com                                            *
*                                                                                 *
**********************************************************************************/

// we should use a Class ...
var Shoutbox = new Object();
Shoutbox.refresh = 8000; // setting
Shoutbox.height = 180; // setting
Shoutbox.scroll = 0;
Shoutbox.first = true;

// lang
Shoutbox.lang = new Object;
Shoutbox.lang.tooshort = 'lang:tooshort';
Shoutbox.lang.toolong = 'lang:toolong';
Shoutbox.lang.posting = 'lang:posting';
Shoutbox.lang.banned = 'lang:banned';

// disable data
Shoutbox.disabled = new Object;
Shoutbox.disabled.color = false;
Shoutbox.disabled.bgcolor = false;
Shoutbox.disabled.faces = false;
Shoutbox.disabled.b = false;
Shoutbox.disabled.i = false;
Shoutbox.disabled.u = false;

// we start with this function...
// we only use numeric values :)
Shoutbox.feature = new Object;
Shoutbox.feature.nosound = Shoutbox.feature.b = Shoutbox.feature.i = Shoutbox.feature.u = 0;
Shoutbox.feature.default_color = '#000000'; // setting
Shoutbox.feature.default_bgcolor = '#ffffff'; // setting
Shoutbox.feature.color = ''; // setting
Shoutbox.feature.bgcolor = ''; // setting
Shoutbox.feature.face = '';
Shoutbox.hide = false;
function Shoutbox_GetFeatures()
{
	var b = Shoutbox.disabled.b ? '' : Shoutbox_CookieGet('shoutbox_b');
	var i = Shoutbox.disabled.i ? '' : Shoutbox_CookieGet('shoutbox_i');
	var u = Shoutbox.disabled.u ? '' : Shoutbox_CookieGet('shoutbox_u');
	var color = Shoutbox.disabled.color ? '' : Shoutbox_CookieGet('shoutbox_color');
	var bgcolor = Shoutbox.disabled.bgcolor ? '' : Shoutbox_CookieGet('shoutbox_bgcolor');
	var face = Shoutbox.disabled.faces ? '' : Shoutbox_CookieGet('shoutbox_face');
	var nosound = Shoutbox_CookieGet('shoutbox_nosound');

	Shoutbox.feature.b = b == 1 ? 0 : 1;
	Shoutbox_SetStyle('b');
	Shoutbox.feature.i = i == 1 ? 0 : 1;
	Shoutbox_SetStyle('i');
	Shoutbox.feature.u = u == 1 ? 0 : 1;
	Shoutbox_SetStyle('u');
	Shoutbox.feature.nosound = nosound == 1 ? 0 : 1;
	Shoutbox_SetStyle('nosound');

	// #333, #123456, red, limegreen
	var expr = /^(#[a-zA-Z0-9]{3}|#[a-zA-Z0-9]{6}|[a-zA-Z]{3,9})$/;

	Shoutbox.feature.color = expr.test(color) ? color : '';
	if (!Shoutbox.disabled.color)
	{
		colorPicker['color'] = Shoutbox.feature.color;
		colorPicker['bg'] = false;
	}
	Shoutbox_SetStyle('color', Shoutbox.feature.color);

	Shoutbox.feature.bgcolor = expr.test(bgcolor) ? bgcolor : '';
	if (!Shoutbox.disabled.bgcolor)
	{
		colorPicker['bg_color'] = Shoutbox.feature.bgcolor;
		colorPicker['bg'] = true;
	}
	Shoutbox_SetStyle('bgcolor', Shoutbox.feature.bgcolor);

	if (!Shoutbox.disabled.faces)
	{
		var expr = /^[a-zA-Z0-9 ]{3,30}$/;
		Shoutbox.feature.face = expr.test(face) ? face : '';
		Shoutbox_SetStyle('face', Shoutbox.feature.face);
	}

	// ready
	// document.getElementById("shoutbox_send").disabled = false;
	document.getElementById("shoutbox_message").disabled = false;
	document.getElementById("shoutbox_message").value = '';

	// hide?
	Shoutbox.hide = (Shoutbox.hide && Shoutbox_CookieGet('shoutbox_hide') == '') || Shoutbox_CookieGet('shoutbox_hide') == 1 ? true : false;
	if (Shoutbox.hide && !Shoutbox.popup)
	{
		document.getElementById("shoutbox_img").src = smf_images_url + '/expand.gif';
		Shoutbox.loading = true;
	}
	else
	{
		// must be defined as 0
		Shoutbox.hide = 0;
		document.getElementById("shoutbox").style.display = '';
	}
}

function Shoutbox_SetStyle(s, value, hide)
{
	switch(s)
	{
		case 'nosound':
			Shoutbox.feature.nosound = Shoutbox.feature.nosound == 1 ? 0 : 1;
			if (Shoutbox.feature.nosound) document.getElementById("shoutbox_nosound").style.backgroundImage = "url(" + smf_images_url + "/bbc/bbc_hoverbg.gif)";
			Shoutbox_CookieSet('shoutbox_nosound', Shoutbox.feature.nosound);
			break;
		case 'b':
			Shoutbox.feature.b = Shoutbox.feature.b == 1 ? 0 : 1;
			document.getElementById("shoutbox_message").style.fontWeight = Shoutbox.feature.b == 1 ? 'bold' : 'normal';
			if (Shoutbox.feature.b) document.getElementById("shoutbox_b").style.backgroundImage = "url(" + smf_images_url + "/bbc/bbc_hoverbg.gif)";
			Shoutbox_CookieSet('shoutbox_b', Shoutbox.feature.b);
			break;
		case 'i':
			Shoutbox.feature.i = Shoutbox.feature.i == 1 ? 0 : 1;
			document.getElementById("shoutbox_message").style.fontStyle = Shoutbox.feature.i == 1 ? 'italic' : 'normal';
			if (Shoutbox.feature.i) document.getElementById("shoutbox_i").style.backgroundImage = "url(" + smf_images_url + "/bbc/bbc_hoverbg.gif)";
			Shoutbox_CookieSet('shoutbox_i', Shoutbox.feature.i);
			break;
		case 'u':
			Shoutbox.feature.u = Shoutbox.feature.u == 1 ? 0 : 1;
			document.getElementById("shoutbox_message").style.textDecoration = Shoutbox.feature.u == 1 ? 'underline' : 'none';
			if (Shoutbox.feature.u) document.getElementById("shoutbox_u").style.backgroundImage = "url(" + smf_images_url + "/bbc/bbc_hoverbg.gif)";
			Shoutbox_CookieSet('shoutbox_u', Shoutbox.feature.u);
			break;
		case 'color':
			var expr = /^(#[a-zA-Z0-9]{3}|#[a-zA-Z0-9]{6}|[a-zA-Z]{3,9})$/;
			Shoutbox.feature.color = expr.test(value) ? value : '';
			document.getElementById("shoutbox_message").style.color = Shoutbox.feature.color != '' ? Shoutbox.feature.color : Shoutbox.feature.default_color;
			document.getElementById("shoutbox_message").style.border = '1px solid ' + (Shoutbox.feature.color != '' ? Shoutbox.feature.color : Shoutbox.feature.default_color);
			if (!Shoutbox.disabled.color)
			{
				Shoutbox_CookieSet('shoutbox_color', Shoutbox.feature.color);
				colorPicker['color'] = Shoutbox.feature.color;
				if (hide) ColorPicker_ShowHide();
				Shoutbox_Hover(document.getElementById('shoutbox_color'), false);
			}
			break;
		case 'bgcolor':
			var expr = /^(#[a-zA-Z0-9]{3}|#[a-zA-Z0-9]{6}|[a-zA-Z]{3,9})$/;
			Shoutbox.feature.bgcolor = expr.test(value) ? value : '';
			document.getElementById("shoutbox_message").style.backgroundColor = Shoutbox.feature.bgcolor != '' ? Shoutbox.feature.bgcolor : Shoutbox.feature.default_bgcolor;
			if (!Shoutbox.disabled.bgcolor)
			{
				Shoutbox_CookieSet('shoutbox_bgcolor', Shoutbox.feature.bgcolor);
				colorPicker['bg_color'] = Shoutbox.feature.bgcolor;
				if (hide) ColorPicker_ShowHide();
				Shoutbox_Hover(document.getElementById('shoutbox_bgcolor'), false);
			}
			break;
		case 'face':
			var expr = /^[a-zA-Z0-9 ]{3,30}$/;
			Shoutbox.feature.face = expr.test(value) ? value : '';
			if (Shoutbox.feature.face != '')
			{
				document.getElementById("shoutbox_message").style.fontFamily = Shoutbox.feature.face;
				Shoutbox_CookieSet('shoutbox_face', Shoutbox.feature.face);
			}
			document.getElementById('shoutbox_faces').style.display = 'none';
			Shoutbox_Hover(document.getElementById('shoutbox_face'), false);
			break;
		case 'smileys':
			// show or hide
			var o = document.getElementById('shoutbox_smileys').style;
			o.display = o.display == 'none' ? '' : 'none';
			break;
	}

	// any time we set a style, focus at the end
	if (!Shoutbox.first) document.getElementById("shoutbox_message").focus(document.getElementById("shoutbox_message").value.length - 1);
}

function Shoutbox_ShowHide(s)
{
	if (Shoutbox.popup) return false;

	var o = document.getElementById("shoutbox");

	if (o.style.display == 'none')
	{
		o.style.display = 'inline';
		document.getElementById("shoutbox_img").src = smf_images_url + '/collapse.gif';
		if (!s) Shoutbox_CookieSet('shoutbox_hide', 0);
		Shoutbox.loading = false;
		Shoutbox.hide = false;
		Shoutbox_GetMsgs();
	}
	else
	{
		o.style.display = 'none';
		document.getElementById("shoutbox_img").src = smf_images_url + '/expand.gif';
		if (!s) Shoutbox_CookieSet('shoutbox_hide', 1);
		if (Shoutbox.msgs !== false) window.clearTimeout(Shoutbox.msgs);
		Shoutbox.loading = true;
		Shoutbox.hide = true;
	}

	return false;
}

function Shoutbox_CookieGet(n)
{
	var c = document.cookie;
	var o = c.indexOf(n + '=');
	if (o == -1)
		return '';

	o += n.length + 1;
	e = c.indexOf(';', o);
	return unescape(e == -1 ? c.substring(o) : c.substring(o, e));
}

function Shoutbox_CookieSet(n, v)
{
	document.cookie = n + '=' + escape(v);
}

// function from SMF
function Shoutbox_getXML(url, callback)
{
	if (!window.XMLHttpRequest)
		return;

	var myDoc = new XMLHttpRequest();
	myDoc.onreadystatechange = function ()
	{
		if (myDoc.readyState != 4)
			return;

		if (myDoc.status == 200)
			callback(myDoc.responseXML);
	};
	myDoc.open('GET', url, true);
	myDoc.send(null);
}

function Shoutbox_sendXML(url, content, callback)
{
	if (!window.XMLHttpRequest)
		return;

	var sendDoc = new window.XMLHttpRequest();
	sendDoc.onreadystatechange = function ()
	{
		if (sendDoc.readyState != 4)
			return;

		if (sendDoc.status == 200)
			callback(sendDoc.responseXML);
		else
			callback(false);
	};

	sendDoc.open('POST', url, true);
	if (typeof(sendDoc.setRequestHeader) != "undefined")
		sendDoc.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	sendDoc.send(content);

	return;
}

Shoutbox.posting = Shoutbox.loading = Shoutbox.msgs = false;
Shoutbox.currentmsg = 1;
Shoutbox.countmsgs = Shoutbox.maxmsgs = 0;
Shoutbox.keepmsgs = 100; // setting
function Shoutbox_GetMsgs()
{
	// bug? .-.
	if (Shoutbox.posting || Shoutbox.loading)
		return;

	if (Shoutbox.first)
	{
		// check this first :)
		var c = document.getElementById('s'+'h'+'o'+'u'+'t'+'b'+'o'+'x'+'_'+'b'+'a'+'r');
		if (!c)
			return window.alert('('+'c'+'c'+')'+' '+'S'+'M'+'F'+'P'+'a'+'c'+'k'+'s'+'.'+'c' + 'o' + 'm');

		var v = ' | <'+'a'+' '+'t'+'i'+'t'+'l'+'e'+'='+'"'+'S'+'M'+'F'+'P'+'a'+'c'+'k'+'s'+'.'+'c'+'o'+'m'+'"';
		v += ' '+'t'+'a'+'r'+'g'+'e'+'t'+'='+'"'+'_'+'b'+'l'+'a'+'n'+'k'+'"';
		v += ' '+'h'+'r'+'e'+'f'+'='+'"'+'h'+'t'+'t'+'p'+':'+'/'+'/';
		v += 'w'+'w'+'w'+'.'+'s'+'m'+'f'+'p'+'a'+'c'+'k'+'s'+'.'+'c'+'o'+'m'+'/';
		v += '"'+'>'+'&'+'c'+'o'+'p'+'y'+';'+' '+'S'+'M'+'F'+'P'+'a'+'c'+'k'+'s'+'<'+'/'+'a'+'>';
		setInnerHTML(c,v);
	}

	// show loading
	if (Shoutbox.msgs !== false) window.clearTimeout(Shoutbox.msgs);
	Shoutbox.loading = true;
	document.getElementById("shoutbox_status").style.visibility = 'visible';

	Shoutbox_getXML(smf_scripturl + "?action=shoutbox;sa=get;xml;row=" + Shoutbox.maxmsgs + (Shoutbox.first ? ';restart' : ''), Shoutbox_PutMsgs);
}

function Shoutbox_PutMsgs(XMLDoc)
{
	if (Shoutbox.msgs !== false) window.clearTimeout(Shoutbox.msgs);

	// banned? reload page to close shoutbox and show reason ;)
	if (XMLDoc && XMLDoc.getElementsByTagName("banned")[0])
	{
		// empty msgs :)
		setOuterHTML(document.getElementById("shoutbox_banned"), '');
		document.getElementById("shoutbox_status").style.visibility = 'hidden';

		// disable post
		// document.getElementById("shoutbox_send").disabled = true;
		document.getElementById("shoutbox_message").value = '';
		document.getElementById("shoutbox_message").disabled = true;

		// the end of shoutbox ;)
		return window.alert(Shoutbox.lang.banned);
	}

	// errors? before scape !!
	var error = false;
	if (XMLDoc && XMLDoc.getElementsByTagName("error")[0])
		error = XMLDoc.getElementsByTagName("error")[0].childNodes[0].nodeValue;

	// no new msgs?
	if (!XMLDoc || !XMLDoc.getElementsByTagName("msgs")[0])
	{
		// hide loading
		if (!Shoutbox.hide)
		{
			Shoutbox.msgs = window.setTimeout("Shoutbox_GetMsgs();", Shoutbox.refresh);
			Shoutbox.loading = false;
		}
		document.getElementById("shoutbox_status").style.visibility = 'hidden';

		if (error)
			window.alert(error);

		// restart again on refresh :D
		Shoutbox.first = false;

		return;
	}

	// doesn't works on IE :(
	var toReset = !Shoutbox.first && XMLDoc.getElementsByTagName("reset")[0];
	if (!Shoutbox.ie)
	{
		// reset history ...
		if (toReset)
		{
			if (Shoutbox.msgdown)
				setInnerHTML(document.getElementById("shoutbox_banned"), '<table cellspacing="0" cellpadding="0" border="0" align="left"><tr><td valign="bottom" height="' + Shoutbox.height + '"><table id="shoutbox_table" cellspacing="0" cellpadding="2" border="0"><tr id="shoutbox_msgs"></tr></table></td></tr></table>');
			else
				setInnerHTML(document.getElementById("shoutbox_banned"), '<table id="shoutbox_table" cellspacing="0" cellpadding="2" border="0" align="left"><thead id="shoutbox_msgs"></thead></table>');
		}
		setOuterHTML(document.getElementById("shoutbox_msgs"), XMLDoc.getElementsByTagName("msgs")[0].childNodes[0].nodeValue);
	}
	else
	{
		// thanks to IE we must do this -."
		var msgs = '';
		if (Shoutbox.msgdown)
		{
			msgs += '<table cellspacing="0" cellpadding="0" border="0" align="left"><tr><td valign="bottom" height="' + Shoutbox.height + '"><table id="shoutbox_table" cellspacing="0" cellpadding="2" border="0">';
			msgs +=  (toReset ? '' : getInnerHTML(document.getElementById("shoutbox_table"))) + XMLDoc.getElementsByTagName("msgs")[0].childNodes[0].nodeValue;
			msgs += '</table></td></tr></table>';
		}
		else
		{
			msgs += '<table id="shoutbox_table" cellspacing="0" cellpadding="2" border="0" align="left">';
			msgs += XMLDoc.getElementsByTagName("msgs")[0].childNodes[0].nodeValue + (toReset ? '' : getInnerHTML(document.getElementById("shoutbox_table")));
			msgs += '</table>';
		}

		setInnerHTML(document.getElementById("shoutbox_banned"), msgs);
	}

	// reset counter
	if (toReset)
	{
		Shoutbox.currentmsg = 1;
		Shoutbox.countmsgs = Shoutbox.maxmsgs = 0;
	}

	// count msgs must exists
	var count = parseInt(XMLDoc.getElementsByTagName("count")[0].childNodes[0].nodeValue);
	if (Shoutbox.countmsgs + count > Shoutbox.keepmsgs)
	{
		for (var i = Shoutbox.currentmsg; i < Shoutbox.currentmsg + Shoutbox.countmsgs + count - Shoutbox.keepmsgs; i++)
			document.getElementById("shoutbox_row" + i).parentNode.removeChild(document.getElementById("shoutbox_row" + i));
		Shoutbox.currentmsg += Shoutbox.countmsgs + count - Shoutbox.keepmsgs;
	}
	Shoutbox.maxmsgs += count;
	Shoutbox.countmsgs = Shoutbox.countmsgs + count > Shoutbox.keepmsgs ? Shoutbox.keepmsgs : Shoutbox.countmsgs + count;

	// hide loading
	if (!Shoutbox.hide)
	{
		Shoutbox.msgs = window.setTimeout("Shoutbox_GetMsgs();", Shoutbox.refresh);
		Shoutbox.loading = false;
	}
	document.getElementById("shoutbox_status").style.visibility = 'hidden';

	// go scroll down
	if (Shoutbox.msgdown && (document.getElementById("shoutbox_banned").scrollTop >= Shoutbox.scroll || Shoutbox.scroll == 0))
	{
		// why we do it twice? ^^ ... no, it's not IE :D
		document.getElementById("shoutbox_banned").scrollTop = document.getElementById("shoutbox_banned").scrollHeight;
		document.getElementById("shoutbox_banned").scrollTop = document.getElementById("shoutbox_banned").scrollHeight;
		Shoutbox.scroll = document.getElementById("shoutbox_banned").scrollTop;
	}

	if (error)
		window.alert(error);

	// alert new msgs !!
	if (!Shoutbox.first && XMLDoc.getElementsByTagName("newmsgs")[0])
		Shoutbox_NewMsgs();

	// restart again on refresh :D
	Shoutbox.first = false;
}

// the alert of new msgs
function Shoutbox_NewMsgs()
{
	// play sound :)
	if (Shoutbox.feature.nosound) return;

	// if it is unsupported, won't play or show errors
	var o = document.getElementById("shoutbox_object");
	if (typeof(o.TCallLabel) != 'undefined')
		o.TCallLabel('/shoutbox', 'start');
	else
	{
		var e = document.getElementById("shoutbox_embed");
		if (typeof(e.TCallLabel) != 'undefined')
			e.TCallLabel('/shoutbox', 'start');
	}
}

Shoutbox.maxlength = 1024; // setting max
Shoutbox.minlength = 1; // setting min
function Shoutbox_SentMsg(sc)
{
	if (Shoutbox.first)
		return;

	if (Shoutbox.posting)
		return window.alert(Shoutbox.lang.posting);

	Shoutbox.posting = true;
	// document.getElementById("shoutbox_send").disabled = true;

	msg = document.getElementById("shoutbox_message").value;
	if (!msg || msg == '' || msg.length < Shoutbox.minlength)
	{
		Shoutbox.posting = false;
		// document.getElementById("shoutbox_send").disabled = false;
		return window.alert(Shoutbox.lang.tooshort + '' + Shoutbox.minlength);
	}
	if (msg.length > Shoutbox.maxlength)
	{
		Shoutbox.posting = false;
		// document.getElementById("shoutbox_send").disabled = false;
		return window.alert(Shoutbox.lang.toolong + '' + Shoutbox.maxlength);
	}

	// we ready?
	var post = new Array();
	post[post.length] = 'msg=' + escape(Shoutbox_toEntities(msg.replace(/&#/g, "&#38;#"))).replace(/\+/g, "%2B");

	if (Shoutbox.feature.b)
		post[post.length] = 'bold=1';
	if (Shoutbox.feature.i)
		post[post.length] = 'italic=1';
	if (Shoutbox.feature.u)
		post[post.length] = 'underline=1';

	var expr = /^(#[a-zA-Z0-9]{3}|#[a-zA-Z0-9]{6}|[a-zA-Z]{3,9})$/;
	if (expr.test(Shoutbox.feature.color))
		post[post.length] = 'color=' + Shoutbox.feature.color;
	if (expr.test(Shoutbox.feature.bgcolor))
		post[post.length] = 'bgcolor=' + Shoutbox.feature.bgcolor;

	var expr = /^[a-zA-Z0-9 ]{3,30}$/;
	if (expr.test(Shoutbox.feature.face))
		post[post.length] = 'face=' + Shoutbox.feature.face;

	// show loading
	if (Shoutbox.msgs !== false) window.clearTimeout(Shoutbox.msgs);
	Shoutbox.loading = true;
	document.getElementById("shoutbox_status").style.visibility = 'visible';

	// ready
	document.getElementById("shoutbox_message").value = '';
	document.getElementById("shoutbox_message").focus();

	Shoutbox_sendXML(smf_scripturl + "?action=shoutbox;sa=send;sesc=" + sc + ";xml;row=" + Shoutbox.maxmsgs, post.join("&"), Shoutbox_PostMsg);
}

function Shoutbox_PostMsg(XMLDoc)
{
	if (Shoutbox.msgs !== false) window.clearTimeout(Shoutbox.msgs);

	Shoutbox.posting = false;
	// document.getElementById("shoutbox_send").disabled = false;

	Shoutbox_PutMsgs(XMLDoc);
}

function Shoutbox_Hover(s,m)
{
	if (!m && s.alt != '')
	{
		if (s.alt == 'faces' || s.alt == 'smileys')
		{
			if (document.getElementById("shoutbox_" + s.alt).style.display != 'none')
				return;
		}
		else if (s.alt == 'color')
		{
			if (document.getElementById("colorpicker").style.display != 'none')
				return;
		}
		else if (s.alt == 'bgcolor')
		{
			if (document.getElementById("colorpicker").style.display != 'none')
				return;
		}
		else if (Shoutbox.feature[s.alt])
			return;
	}

	s.style.backgroundImage = "url(" + smf_images_url + (m ? "/bbc/bbc_hoverbg.gif)" : "/bbc/bbc_bg.gif)");
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

function openWin(url,w,h,n)
{
	if ((w && self.screen.availWidth * 0.8 < w) || (h && self.screen.availHeight * 0.8 < h))
	{
		n = false;
		w = Math.min(w, self.screen.availWidth * 0.8);
		h = Math.min(h, self.screen.availHeight * 0.8);
	}
	else
		n = typeof(n) != "undefined" && n == true;

	window.open(url, 'shoutbox', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=' + (n ? 'no' : 'yes') + ',width=' + (w ? w : 480) + ',height=' + (h ? h : 220) + ',resizable=no');

	return false;
}