<?php
/**
 * $Project: Pastebin $
 * $Id: layout.php,v 1.1 2006/04/27 16:22:39 paul Exp $
 * 
 * Pastebin Collaboration Tool
 * http://pastebin.com/
 *
 * This file copyright (C) 2006 Paul Dixon (paul@elphin.com)
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the Affero General Public License 
 * Version 1 or any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * Affero General Public License for more details.
 * 
 * You should have received a copy of the Affero General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
 
echo "<?xml version=\"1.0\" encoding=\"".$charset_code[$charset]['http']."\"?>\n";


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<!--
pastebin.com Copyright 2006 Paul Dixon - email suggestions to lordelph at gmail.com
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Pastebin</title>
<meta name="ROBOTS" content="NOARCHIVE" />
<link rel="stylesheet" type="text/css" media="screen" href="../pastebin/pastebin.css?ver=5" />

<?php if (isset($page['post']['codecss']))
{
	echo '<style type="text/css">';
	echo $page['post']['codecss'];
	echo '</style>';
}
?>
<script type="text/javascript" src="../pastebin/pastebin.js?ver=3"></script>
</head>


<body onload="initPastebin()">

<div id="titlebar">Pastebin - collaborative debugging tool</div>
<div id="menu">

<?php echo '<h1>'.t('Recent Posts').'</h1>'?>

<ul>
<?php  
	foreach($page['recent'] as $idx=>$entry)
	{
		if ($entry['pid']==$pid)
			$cls=" class=\"highlight\"";
		else
			$cls="";
			
		echo "<li{$cls}><a href=\"{$entry['url']}\">";
		echo $entry['poster'];
		echo "</a><br/>{$entry['agefmt']}</li>\n";
	}

	echo "<li><a rel=\"nofollow\" href=\"{$CONF['this_script']}\">".t('Make new post').'</a></li>';
?>
</ul>

<?php

echo '<h1>'.t('About').'</h1><p>';

echo t('Pastebin is a tool for collaborative debugging or editing.');
echo t('For latest news see author\'s <a href="http://blog.dixo.net/category/pastebin/">blog</a>.');

echo '</p>';

echo '<h1>'.t('Credits').'</h1><p>';
	
	echo t('Software developed by ');
	echo '<a href="http://blog.dixo.net">Paul Dixon</a></p>';

?>

</div>
<div id="content">

<?php
///////////////////////////////////////////////////////////////////////////////
// show processing errors
//
if (count($pastebin->errors))
{
	echo '<h1>'.t('Errors').'</h1><ul>';
	foreach($pastebin->errors as $err)
	{
		echo "<li>$err</li>";
	}
	echo "</ul>";
	echo "<hr />";
}

if (!empty($page['delete_message']))
{
	echo "<h1>{$page['delete_message']}</h1><br/>";
}

if (isset($_REQUEST["diff"]))
{
	
	$newpid=$pastebin->cleanPostId($_REQUEST['diff']);
	
	$newpost=$pastebin->getPost($newpid);
	if (count($newpost))
	{
		$oldpost=$pastebin->getPost($newpost['parent_pid']);	
		if (count($oldpost))
		{
			$page['pid']=$newpid;
			$page['current_format']=$newpost['format'];
			$page['editcode']=$newpost['code'];
			$page['posttitle']='';
	
			//echo "<div style=\"text-align:center;border:1px red solid;padding:5px;margin-bottom:5px;\">Diff feature is in BETA! If you have feedback, send it to lordelph at gmail.com</div>";
			
			echo "<h1>";
			printf(t('Difference between<br/>modified post %s by %s on %s and<br/>'.
				'original post %s by %s on %s'),
				"<a href=\"".$pastebin->getPostUrl($newpost['pid'])."\">{$newpost['pid']}</a>",
				$newpost['poster'],
				$newpost['postdate'],
				'<a href="'.$pastebin->getPostUrl($oldpost['pid'])."\">{$oldpost['pid']}</a>",
				$oldpost['poster'],
				$oldpost['postdate']);
				
			echo "<br/>";	
			
			echo t('Show');
			echo " <a title=\"".t('Don\'t show inserted or changed lines')."\" style=\"padding:1px 4px 3px 4px;\" id=\"oldlink\" href=\"javascript:showold()\">".t('old version')."</a> | ";
			echo "<a title=\"".t('Don\'t show lines removed from old version')."\" style=\"padding:1px 4px 3px 4px;\" id=\"newlink\" href=\"javascript:shownew()\">".t('new version')."</a> | ";
			echo "<a title=\"".t('Show both insertions and deletions')."\"  style=\"background:#ddd;padding:1px 4px 3px 4px;\" id=\"bothlink\" href=\"javascript:showboth()\">".t('both versions')."</a> ";
			echo "</h1>";
			
			$newpost['code']=preg_replace('/^'.$CONF['highlight_prefix'].'/m', '', $newpost['code']);
			$oldpost['code']=preg_replace('/^'.$CONF['highlight_prefix'].'/m', '', $oldpost['code']);
			
			$a1=explode("\n", $newpost['code']);
			$a2=explode("\n", $oldpost['code']);
			
			$diff=new Diff($a2,$a1, 1);
			
			echo "<table cellpadding=\"0\" cellspacing=\"0\" class=\"diff\">";
			echo "<tr><td></td><td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td></td></tr>";
			echo $diff->output;
			echo "</table>";
		}
		
	}
	
	
}

///////////////////////////////////////////////////////////////////////////////
// show a post
//

if (isset($_GET['help']))
	$page['posttitle']="";
	
if (strlen($page['post']['posttitle']))
{
		echo "<h1>{$page['post']['posttitle']}";
		if (strlen($page['post']['parent_pid']))
		{
			echo ' (';
			printf(t("modification of post by %s"),
				"<a href=\"{$page['post']['parent_url']}\" title=\"".t('view original post')."\">{$page['post']['parent_poster']}</a>");
			
			echo " <a href=\"{$page['post']['parent_diffurl']}\" title=\"".t('compare differences')."\">".t('view diff')."</a>)";
		}
		
		echo "<br/>";
		
		if (strlen($page['post']['token']) && ($page['token']==$page['post']['token']))
		{
			echo "<a href=\"{$page['post']['deleteurl']}\" title=\"".t('delete post')."\">".t('delete post')."</a> | ";
		}
		
		$followups=count($page['post']['followups']);
		if ($followups)
		{
			echo t('View followups from ');
			$sep="";
			foreach($page['post']['followups'] as $idx=>$followup)
			{
				echo $sep."<a title=\"posted {$followup['postfmt']}\" href=\"{$followup['followup_url']}\">{$followup['poster']}</a>";
				$sep=($idx<($followups-2))?", ":(' '.t('and').' ');	
			}
			
			echo " | ";
		}
		
		if ($page['post']['parent_pid']>0)
		{
			echo "<a href=\"{$page['post']['parent_diffurl']}\" title=\"".t('compare differences')."\">".t('diff')."</a> | ";
		} 
		
		echo "<a href=\"{$page['post']['downloadurl']}\" title=\"".t('download file')."\">".t('download')."</a> | ";
		
		echo "<span id=\"copytoclipboard\"></span>";
		
		echo "<a href=\"/\" title=\"".t('make new post')."\">".t('new post')."</a>";
		
		echo "</h1>";
}
if (isset($page['post']['pid']))
{
	echo "<div class=\"syntax\">".$page['post']['codefmt']."</div>";
	echo "<br /><b>".t('Submit a correction or amendment below')." (<a href=\"{$CONF['this_script']}\">".t('click here to make a fresh posting')."</a>)</b><br/>";
	echo t('After submitting an amendment, you\'ll be able to view the differences between the old and new posts easily').'.';
}	
?>
<form name="editor" method="post" action="<?php echo $CONF['this_script']?>">
<input type="hidden" name="parent_pid" value="<?php echo $page['post']['pid'] ?>"/>

<br/> 
<?php

echo t('Syntax highlighting:').'<select name="format">';

//show the popular ones
foreach ($CONF['all_syntax'] as $code=>$name)
{
	if (in_array($code, $CONF['popular_syntax']))
	{
		$sel=($code==$page['current_format'])?"selected=\"selected\"":"";
		echo "<option $sel value=\"$code\">$name</option>";
	}
}

echo "<option value=\"text\">----------------------------</option>";

//show all formats
foreach ($CONF['all_syntax'] as $code=>$name)
{
	$sel=($code==$page['current_format'])?"selected=\"selected\"":"";
	if (in_array($code, $CONF['popular_syntax']))
		$sel="";
	echo "<option $sel value=\"$code\">$name</option>";
	
}
?>
</select><br/>
<br/>

<?php printf(t('To highlight particular lines, prefix each line with %s'),$CONF['highlight_prefix']); ?>
<br/>
<textarea id="code" class="codeedit" name="code2" cols="80" rows="10" onkeydown="return catchTab(this,event)"><?php 
echo htmlentities($page['post']['editcode'], ENT_COMPAT,$CONF['htmlentity_encoding']) ?></textarea>

<div id="namebox">
	
<label for="poster"><?php echo t('Your Name')?></label><br/>
<input type="text" maxlength="24" size="24" id="poster" name="poster" value="<?php echo $page['poster'] ?>" />
<input type="submit" name="paste" value="<?php echo t('Send')?>"/>
<br />
<?php echo '<input type="checkbox" name="remember" value="1" '.$page['remember'].' />'.t('Remember me'); ?>

</div>


<div id="expirybox">


<div id="expiryradios">
<label><?php echo t('How long should your post be retained?') ?></label><br/>

<input type="radio" id="expiry_day" name="expiry" value="d" <?php if ($page['expiry']=='d') echo 'checked="checked"'; ?> />
<label id="expiry_day_label" for="expiry_day"><?php echo t('a day') ?></label>

<input type="radio" id="expiry_month" name="expiry" value="m" <?php if ($page['expiry']=='m') echo 'checked="checked"'; ?> />
<label id="expiry_month_label" for="expiry_month"><?php echo t('a month') ?></label>

<input type="radio" id="expiry_forever" name="expiry" value="f" <?php if ($page['expiry']=='f') echo 'checked="checked"'; ?> />
<label id="expiry_forever_label" for="expiry_forever"><?php echo t('forever') ?></label>
</div>
<div id="expiryinfo"></div>
</div>
<div id="end"></div>
</form>
</div>
</body>
</html>
