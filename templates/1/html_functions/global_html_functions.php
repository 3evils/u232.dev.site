<?php
/**
 |--------------------------------------------------------------------------|
 |   https://github.com/Bigjoos/                                            |
 |--------------------------------------------------------------------------|
 |   Licence Info: WTFPL                                                    |
 |--------------------------------------------------------------------------|
 |   Copyright (C) 2010 U-232 V5                                            |
 |--------------------------------------------------------------------------|
 |   A bittorrent tracker source based on TBDev.net/tbsource/bytemonsoon.   |
 |--------------------------------------------------------------------------|
 |   Project Leaders: Mindless, Autotron, whocares, Swizzles.               |
 |--------------------------------------------------------------------------|
  _   _   _   _   _     _   _   _   _   _   _     _   _   _   _
 / \ / \ / \ / \ / \   / \ / \ / \ / \ / \ / \   / \ / \ / \ / \
( U | - | 2 | 3 | 2 )-( S | o | u | r | c | e )-( C | o | d | e )
 \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/
 */
 ///// global, table tr, td, and div classes for blocks
function begin_main_div()
{
    return "<div class='card'>";
}
function begin_head_div()
{
    return "<div class='card-divider'>";
}
function end_head_div()
{
    return "</div>";
}
function begin_head_label($x)
{
    return "<span class='label'>";
}
function end_head_label()
{
    return "</span>";
}
function begin_body_div()
{
    return "<div class='callout'>";
}
function end_body_div()
{
    return "</div>";
}
function end_div()
{
    return "</div>";
}
function begin_main_table()
{
    return "<table class='hover'>"; 
}
//New functions
function begin_callout_div($x, $y, $noesc = 0)
{
	    if ($noesc)
        $a = $y;
    else {
        $a = htmlsafechars($y);
        $a = str_replace("\n", "<br>\n", $a);
    }
    return "<div class='callout'>$x</div>";
}
function end_callout_div()
{
    return "</div>";
}
function row_div($x)
{
	return "<div class='row'>$x</div>";
}
function card_divider($y, $noesc = 0)
{
	    if ($noesc)
        $a = $y;
    else {
        $a = htmlsafechars($y);
        $a = str_replace("", $a);
    }
    return row_div("<div class='card-divider'>$a</div>",1);
}
function row_callout($x, $y, $noesc = 0)
{	
    if ($noesc)
        $a = $y;
    else {
        $a = htmlsafechars($y);
        $a = str_replace("", $a);
    }
    return row_div("<div class='callout'><h6 class='subheader'>$x</h6><p>$a</p></div>",1);
}
function row_callout_large12($x, $y, $noesc = 0)
{	
    if ($noesc)
        $a = $y;
    else {
        $a = htmlsafechars($y);
        $a = str_replace("", $a);
    }
    return "<div class='row callout large-12'><h6 class='subheader'>$x</h6><p>$a</p></div>";
}
function tabs_title($x)
{
	return "<li class='tabs-title'>$x</li>";
}
function tabs_title_active($x)
{
	return "<li class='tabs-title is-active'>$x";
}
/// end golbal global, table tr, td, and div classes for blocks
?>