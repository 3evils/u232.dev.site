<?php
/**
 |--------------------------------------------------------------------------|
 |   https://github.com/Bigjoos/                			    |
 |--------------------------------------------------------------------------|
 |   Licence Info: GPL			                                    |
 |--------------------------------------------------------------------------|
 |   Copyright (C) 2010 U-232 V5					    |
 |--------------------------------------------------------------------------|
 |   A bittorrent tracker source based on TBDev.net/tbsource/bytemonsoon.   |
 |--------------------------------------------------------------------------|
 |   Project Leaders: Mindless, Autotron, whocares, Swizzles.					    |
 |--------------------------------------------------------------------------|
  _   _   _   _   _     _   _   _   _   _   _     _   _   _   _
 / \ / \ / \ / \ / \   / \ / \ / \ / \ / \ / \   / \ / \ / \ / \
( U | - | 2 | 3 | 2 )-( S | o | u | r | c | e )-( C | o | d | e )
 \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/
 */
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
require_once (INCL_DIR . 'bbcode_functions.php');
dbconn();
loggedinorreturn();
$HTMLOUT = '';
$lang = array_merge(load_language('global'));
/*
function account_delete($userid)
{
        $htmlout = '';
        $secs = 350 * 86400;
        $maxclass = UC_STAFF;
        $references = array(
		"id" => array("users","usersachiev","likes"), // Do Not move this line
		"userid" => array("blackjack","blocks","bookmarks","casino","coins","freeslots","friends","happyhour","happylog","ips","peers","pmboxes","reputation","shoutbox","snatched","uploadapp","user_blocks","ustatus","userhits","usercomments"
			),
                "uid" => array("xbt_files_users","thankyou"),
                "user_id" => array("poll_voters","posts","topics","subscriptions","read_posts"),
		"friendid" => array(
			"friends"
			),
		);
    $ctr = 1;
    foreach($references as $field => $tablelist)
    {
        foreach($tablelist as $table)
        {
            $tables[] = $tc = "t{$ctr}";
            $joins[] = ($ctr == 1) ? "users as {$tc}":"LEFT JOIN {$table} as {$tc} on t1.id={$tc}.{$field}";
            $ctr++;
        }
    }                                                    
    $htmlout .= 'DELETE '. implode(', ', array_map('sqlesc', $tables)) . " FROM " . implode(', ', array_map('sqlesc', $joins)) . " WHERE t1.id='".sqlesc($userid)."' AND t1.class < '".sqlesc($maxclass)."';";
return $htmlout;
}
*/
/*
    $secs = 5 * 86400;
    $dt = (TIME_NOW - $secs);
    $maxclass = UC_STAFF;
    $res_in = mysqli_fetch_assoc(sql_query("SELECT id, parked, status, last_access FROM users WHERE parked='no' AND status='confirmed' AND class < $maxclass AND last_access < $dt"));
    //sql_query(account_delete($res_in['id'])) or sqlerr(__FILE__, __LINE__);
	*/
   /*
$htmlout .= account_delete(1);
*/
///first part in template
    $res = sql_query('SELECT m.*
                           FROM messages AS m LEFT JOIN friends AS f ON f.userid = ' . sqlesc($CURUSER['id']) . ' AND f.friendid = m.sender
                           LEFT JOIN blocks AS b ON b.userid = ' . sqlesc($CURUSER['id']) . ' AND b.blockid = m.sender WHERE m.id = ' . sqlesc($pm_id) . ' AND (receiver=' . sqlesc($CURUSER['id']) . ' OR (sender=' . sqlesc($CURUSER['id']) . ' AND (saved = \'yes\' || unread= \'yes\'))) LIMIT 1') or sqlerr(__FILE__, __LINE__);
    $message = mysqli_fetch_assoc($res);
 
//Second part in template
    $htmlout.= '
<div class="modal fade messages" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Messages</h4>
        </div>
            <div class="panel panel-default">
                <div class="panel-body">
<input class="form-control" type="hidden" name="id" value="' . $pm_id . '">';
$htmlout.= "<table class='table-bordered'>\n
<tr>
<td colspan='2'>Subject</td>
<td colspan='2'>Message</td>
<td colspan='2'>Date</td>";
    if ($message["sender"] != 0)
    $sender = "<a href='userdetails.php?id=".(int)$message["sender"]."'><b>".htmlsafechars($message['sender'] === $CURUSER['id'])."</b></a>";
    else $sender = "<font color='red'><b>System</b></font>";
    $msg = format_comment($message['msg']);
    $added = get_date($message['added'], '');
    $htmlout.= "<tr>
<td colspan='2' class='text-left'><span style='font-weight: bold;'>".$message['sender'] === $CURUSER['id']."</span>
<td align='left'>".format_comment($message['subject'])."</td>
<td align='left'>".format_comment($message['msg'])."</td><td align='left'>".get_date($message['added'], '')."</td><td align='center'></td></tr>\n";
$htmlout.= "</table></form>";
            $htmlout.= '</div>
            </div>
        </div>
    </div>
</div>';
echo stdhead("Testing") . $htmlout . stdfoot();
?>
