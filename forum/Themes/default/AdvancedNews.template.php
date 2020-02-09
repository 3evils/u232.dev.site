<?php
// Version: 1.2: AdvancedNews

function template_main()
{
	global $context, $txt;

	echo '
		<div class="cat_bar">
			<h3 class="catbg">', $txt['news'], '</h3>
		</div>
				<span class="upperframe"><span></span></span>
					<div class="roundframe" id="newspage">';

	foreach ($context['news_lines'] as $news)
		echo '
						<div>', $news, '</div>';

	echo '
					</div>
				<span class="lowerframe"><span></span></span>';

}

?>