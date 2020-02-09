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
<div class="modal fade" id="staff" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content container-fluid">
<h4 class="modal-title">Staff Panel</h4>
<div class="modal-header alert alert-danger fade in">Click on the "x" symbol to the right to close me. I will "fade" out.
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>';
if ($CURUSER['class'] >= UC_STAFF) {
if (($mysql_data = $mc1->get_value('is_staff_' . $CURUSER['class'])) === false) {
$res = sql_query('SELECT * FROM staffpanel WHERE av_class <= ' . sqlesc($CURUSER['class']) . ' ORDER BY page_name ASC') or sqlerr(__FILE__, __LINE__);
while ($arr = mysqli_fetch_assoc($res)) $mysql_data[] = $arr;
$mc1->cache_value('is_staff_' . $CURUSER['class'], $mysql_data, $INSTALLER09['expires']['staff_check']);
}
if ($mysql_data) {
$htmlout .= "
<div class='container col-xs-12'>
<div class='row'>
<div class='col-md-3'>
<li class='list-group-item text-center'style='background:rgba(0, 0, 0, 0.1)'>User</a></li>
<ul class='list-group'>";
foreach ($mysql_data as $key => $value){
if ($value['av_class'] <= $CURUSER['class'] && $value['type'] == 'user') {
$htmlout .= '
<small><a href="'.htmlsafechars($value["file_name"]).'" class="list-group-item">'.htmlsafechars($value["page_name"]).'</a></small>';
}
}
$htmlout .= "</ul></div>";
$htmlout .= "
<div class='col-md-3'>
<li class='list-group-item text-center'style='background:rgba(0, 0, 0, 0.1)'>Settings</li>
<ul class='list-group'>";
foreach ($mysql_data as $key => $value){
if ($value['av_class'] <= $CURUSER['class'] && $value['type'] == 'settings') {
$htmlout .= '<small><a href="'.htmlsafechars($value["file_name"]).'" class="list-group-item">'.htmlsafechars($value["page_name"]).'</a></small>';
}
}
$htmlout .= "</ul></div>";
$htmlout .= "
<div class='col-md-3'>
<li class='list-group-item text-center'style='background:rgba(0, 0, 0, 0.1)'>Stats</li>
<ul class='list-group'>";
foreach ($mysql_data as $key => $value){
if ((int)$value['av_class'] <= $CURUSER['class'] && htmlsafechars($value['type']) == 'stats') {
$htmlout .= '<small><a href="'.htmlsafechars($value["file_name"]).'" class="list-group-item">'.htmlsafechars($value["page_name"]).'</a></small>';
}
}
$htmlout .= "</ul></div>";
$htmlout .= "
<div class='col-md-3'>
<li class='list-group-item text-center'style='background:rgba(0, 0, 0, 0.1)'>Others</li>
<ul class='list-group'>";
foreach ($mysql_data as $key => $value){
if ((int)$value['av_class'] <= $CURUSER['class'] && htmlsafechars($value['type']) == 'other') {
$htmlout .= '<small><a href="'.htmlsafechars($value["file_name"]).'" class="list-group-item">'.htmlsafechars($value["page_name"]).'</a></small>';
}
}
$htmlout .= '</ul></div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>';
}
}
$htmlout .='</div></div></div>';
/////****Start Quick Message in Modal By iseeyoucopy****/////
}

?>
