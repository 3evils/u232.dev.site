/*
Post Ratings
Version 1.3
By: SoLoGHoST
http://www.graphicsmayhem.com
Copyright 2009 Solomon Closson

############################################
License Information:

ABOVE INFO MUST REMAIN INTACT!!
#############################################
*/

var curStars = 0;

function doMover(imageOver, imageOut, currentrating, postId, howMany) {
	curStars = parseInt(currentrating);
	
	for(i=1; i<=5; i++) {
		if (i <= howMany)
			document.getElementById(postId+i).src = imageOver;
		else
			document.getElementById(postId+i).src = imageOut;
	}
}

function alreadyRated(imageOver, imageOut, postId, howMany)
{
	curStars = parseInt(howMany);
	
	for(i=1; i<=5; i++) {
		if (i <= howMany)
			document.getElementById("img"+postId+i).src = imageOver;
		else
			document.getElementById("img"+postId+i).src = imageOut;
	}	
}

// curStars should equal their current rating 1 - 5, if they have a current rating
function doMout(imageOver, imageOut, value, postId) {
	if (curStars == 0)
	{
		for(i=1; i<=5; i++) {
			document.getElementById("img"+postId+i).src = imageOut;
		}
	} else {
		for(i=1; i<=5; i++) {
			if (i <= curStars)
				document.getElementById("img"+postId+i).src = imageOver;
			else
				document.getElementById("img"+postId+i).src = imageOut;
		}
	}
}

function submitVote(postId, value) {
	document.getElementById("rat"+postId+value).checked = "true";
}

function doExpandCollapse(messageId, imgPath)
{
	var pr_opt = document.getElementById("pr"+messageId);
	var pr_image = document.getElementById("img"+messageId);
	var htmlOut = '';
	if (pr_opt.style.display == "none") {
		pr_image.src = imgPath+"/collapse.gif";
		pr_opt.style.display = "block";
		pr_opt.style.marginBottom = "5px";
		pr_opt.style.border = "1px outset #3b3b3b";
		pr_opt.style.borderBottomStyle = "inset";
		pr_opt.style.borderRightStyle = "inset";
	} else {
		pr_opt.style.display = "none";
		pr_image.src = imgPath+"/expand.gif";
	}
}

function doSubmit(which, messageid, url, sessionvar, sessionid) {
 var mainForm = document.forms.quickModForm;

 mainForm.onSubmit = "";
 if (which == "rate")
 	mainForm.action = url + "?action=postratings;sa=rate;post=" + messageid + ";" + sessionvar + "=" + sessionid;
 else if (which == "delete")
 	mainForm.action = url + "?action=postratings;sa=delete;post=" + messageid + ";" + sessionvar + "=" + sessionid;
 else
 	return;

 mainForm.submit();
}