<?php
     /*-----------------------------------------------------------------------\
	|   https://github.com/Bigjoos/ -------------------------------------------|
	|--------------------------------------------------------------------------|
	|   Licence Info: WTFPL  --------------------------------------------------|
	|--------------------------------------------------------------------------|
	|   Copyright (C) 2010 U-232 V5	-------------------------------------------|
	|--------------------------------------------------------------------------|
	|   A bittorrent tracker source based on TBDev.net/tbsource/bytemonsoon. --|
	|--------------------------------------------------------------------------|
	|   Project Leaders: Mindless, Autotron, whocares, Swizzles.---------------|
	\------------------------------------------------------------------------*/
 //==Template system by Terranova
 //==Template system modified by son
{
	$htmlout.= '
<div class="modal fade" id="messages" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<h4 class="modal-title">Messages</h4>
<div class="modal-header alert alert-danger fade in">Click on the "x" symbol to the right to close me. I will "fade" out.
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>';

if (($L_Message_Cached = $mc1->get_value('last_message_' . $CURUSER['id'])) === false) {
$Mess = sql_query('SELECT msgg.receiver, msgg.subject, msgg.sender, msgg.unread, msgg.msg, msgg.added, u1.username AS u1_username, u2.username AS u2_username FROM messages AS msgg LEFT JOIN users AS u1 ON u1.id=msgg.receiver LEFT JOIN users AS u2 ON u2.id=msgg.sender WHERE msgg.receiver=' . sqlesc($CURUSER['id']) . ' OR msgg.sender=' . sqlesc($CURUSER['id']) . ' AND msgg.location = 1 ORDER BY msgg.added DESC LIMIT 1') or sqlerr(__FILE__, __LINE__);
while ($L_Message_Cache = mysqli_fetch_assoc($Mess)) $L_Message_Cached[] = $L_Message_Cache;
    $mc1->cache_value('last_message_' . $CURUSER['id'], $L_Message_Cached, 300);
}
if (count($L_Message_Cached) > 0) {
$htmlout .= '
<div class="table-responsive">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>From</th>
				<th>Subject</th>
				<th>Message</th>
				<th>Date</th>
			</tr>
		</thead>';

if ($L_Message_Cached) {
			foreach ($L_Message_Cached as $m_c) {
if ($m_c["sender"] != 0)
$sender = "<a href='userdetails.php?id=".(int)$m_c["sender"]."'><b>".htmlsafechars($m_c["u2_username"])."</b></a>";
else $sender = "<font color='red'><b>System</b></font>";
		$htmlout .= '<tbody>
			<tr>
				<td>' . $sender . '</td>
				<td>' . ($m_c['subject'] !== '' ? htmlsafechars($m_c['subject']) : 'no subject' ) . '</td>
				<td>' . htmlsafechars($m_c['msg']) . '</td>
				<td>' . get_date($m_c['added'], '') . '</td>
			</tr>
		</tbody>';
}
	$htmlout .= '</table>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
</div>';
$htmlout.= '</div></div></div>';
 } else {
        //== If there messages
        if (empty($L_Message_Cached)) $HTMLOUT.= "<tbody><tr><td class='text-left' colspan='4'>No Messages!</td></tr></tbody></table><div class='modal-footer'>
	<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button></div>
</div>'</div></div></div></div><br />";
    }
}
/////****End Quick Message in Modal By iseeyoucopy****/////
}

?>
