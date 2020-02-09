<?php
//==Poll
//$HTMLOUT .= "<a href=\"javascript: klappe_news('a3')\"><img border=\"0\" src=\"pic/plus.gif\" id=\"pica3\" alt=\"{$lang['index_hide_show']}\"></a><div id=\"ka3\" style=\"display: none;\">";
$HTMLOUT.= "<div class='header panel panel-default'>
	<div class='panel-heading'>
		<label for='checkbox_4' class='text-left'>Polls</label>
	</div>

		<div class='container-fluid panel-body'>";
$HTMLOUT.= parse_poll();
$HTMLOUT.= "</div></div>";
//$HTMLOUT .="</div>";
// End Class
// End File
