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
function stdmsg($heading, $text)
{
$htmlout = "<div class='panel panel-default'>";
if ($heading) $htmlout.= "<div class='panel-heading'>$heading</div>";
$htmlout.= "<div class='panel panel-default'><div class='panel-body'>{$text}</div></div>";
return $htmlout;
}
?>
