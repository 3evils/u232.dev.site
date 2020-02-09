<?php
// File Version: 2
// Hack's Park Shoutbox: www.hackspark.com

function template_manageshoutbox_settings()
{
	global $scripturl, $context, $txt;

	echo '
	<form action="', $scripturl, $context['shoutbox_form'], '" method="post" accept-charset="', $context['character_set'], '">
		<div style="width:100%;padding:0;margin:0 auto" class="tborder">
			<div class="titlebg" style="padding:4px 6px;text-align:center">' . $txt['sba_3'] . '</div>
			<div class="windowbg2" style="padding:8px">
				<table cellpadding="2" cellspacing="0" border="0" width="100%">';

	echo '
					<tr>
						<td colspan="2" style="text-align:left">
							Do you find this modifications useful? <b>Do not let it be premium</b>, <a href="http://www.smfpacks.com/donate.php"><b>donate</b></a> and colaborate with it!
						</td>
					</tr>
					<tr>
						<td colspan="2"><hr /></td>
					</tr>';

	foreach ($context['shoutbox_echo'] as $s => $t)
			if ($t == '')
				echo '
					<tr>
						<td colspan="2"><hr /></td>
					</tr>';
			elseif ($t == 'checkbox')
				echo '
					<tr>
						<td colspan="2" style="text-align:left"><label><input type="checkbox" name="' . $s . '" value="1"' . (!empty($context['shoutbox'][$s]) ? ' checked="checked"' : '') . ' /> ' . $txt['sbas_' . $s] . '</label></td>
					</tr>';
			elseif ($t == 'textarea')
				echo '
					<tr>
						<td width="100%" style="text-align:left">' . $txt['sbas_' . $s] . '</td>
						<td nowrap="nowrap"><textarea style="width:280px;height:80px" name="' . $s . '">' . (!empty($context['shoutbox'][$s]) ? str_replace(',', "\n", $context['shoutbox'][$s]) : '') . '</textarea></td>
					</tr>';
			else
				echo '
					<tr>
						<td width="100%" style="text-align:left">' . $txt['sbas_' . $s] . '</td>
						<td nowrap="nowrap"><input type="text" name="' . $s . '" value="' . (!empty($context['shoutbox'][$s]) ? $context['shoutbox'][$s] : '') . '" style="width:250px" /></td>
					</tr>';

	echo '
				</table>
			</div>
			<div class="windowbg2" style="text-align:right;padding:12px">
				<input type="submit" class="button_submit" value="' . $txt['sba_5'] . '" />
			</div>
		</div>
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
	</form>';
}

?>