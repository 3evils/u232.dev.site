<?php
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
require_once (INCL_DIR . 'torrenttable_functions.php');
require_once (INCL_DIR . 'pager_functions.php');
dbconn(false);
loggedinorreturn();
require_once(TEMPLATE_DIR.''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'html_functions' . DIRECTORY_SEPARATOR . 'global_html_functions.php'); 
require_once(TEMPLATE_DIR.''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'html_functions' . DIRECTORY_SEPARATOR . 'navigation_html_functions.php');
$design = array_merge(load_design());
$lang = array_merge(load_language('global'));
if (!isset($_GET['id'])) stderr("Something gone weong", "Maybe someone is playing football :lol:");
if(isset($_GET['id']) && $_GET['id'] !== '');
	$show_id = $_GET['id'];
	if (($tvshow = $mc1->get_value('tv_show_'.$show_id)) === false) {
		$date = date(('Y-m-d'));
	$tvmaze = file_get_contents('https://api.tvmaze.com/shows/'.$show_id.'?embed[]=episodes&embed[]=cast');
    $tvshow = json_decode($tvmaze, true);
	}
	if (($tvshow_s = $mc1->get_value('tv_show_ep_'.$show_id)) === false) {
		$date = date(('Y-m-d'));
	$tvmaze_s= file_get_contents('https://api.tvmaze.com/shows/'.$show_id.'/seasons');
    $tvshow_s = json_decode($tvmaze_s, true);
	}
	if (($tvshow_ep = $mc1->get_value('tv_show_ep_'.$show_id)) === false) {
	$date = date(('Y-m-d'));
	$tvmaze_ep= file_get_contents('https://api.tvmaze.com/shows/'.$show_id.'/episodes');
    $tvshow_ep = json_decode($tvmaze_ep, true);
	}	
	if (($tvshow_cast = $mc1->get_value('tv_show_cast_'.$show_id)) === false) {
	$date = date(('Y-m-d'));
	$tvmaze_cast= file_get_contents('https://api.tvmaze.com/shows/'.$show_id.'/cast');
    $tvshow_cast = json_decode($tvmaze_cast, true);
	}
	$image = ($tvshow['image']['original']!='') ? "<img src='".$tvshow['image']['original']."' style='width:214; height:305px;'>":"<img src='" .$INSTALLER09['pic_base_url']."/noposter.png' style='width:214; height:305px;'>";  		
	$unwantedChars = array(',', '!', '?', "'"); // create array with unwanted chars
	$HTMLOUT = $name = $type = $language = $status = $runtime = $premiered = $rating = $official_site = $summary = $number = $schedule_time = $updated = "";
	$name = str_replace($unwantedChars,"",(htmlentities($tvshow['name'])));
	$type = $tvshow['type'];
	$language = $tvshow['language'];
	$status = $tvshow['status'];
	$runtime = $tvshow['runtime'];
	$premiered = $tvshow['premiered'];
	$network = $tvshow['network']['name'];
	$rating = $tvshow['rating']['average'];
	$official_site = $tvshow['officialSite'];
	$summary = str_replace($unwantedChars,"",(($tvshow['summary'])));
	$showGenre = "";
		foreach($tvshow['genres'] as $genre) {	
			$showGenre = $showGenre . "<a class='float-left' href='browse.php?search={$genre}&searchin=genre&incldead=0' target='_blank'>". $genre . "| </a>";
	    }
	$schedule_time = $tvshow['schedule']['time'];
	$schedule_days = "";
	foreach($tvshow['schedule']['days'] as $key => $schedule_day) {	
		$schedule_days = $schedule_days . "". $schedule_day . "";
	}
	$updated = date('m/d/Y', $tvshow['updated']);
	$HTMLOUT .= "<div class='row callout'>
		<div class='large-3 columns'>".$image."</div>
		<div class='large-9 columns'>
			<h4><a href='https://nullrefer.com/?{$tvshow['url']}' target='_blank'>{$name}</a></h4>
			<b>{$showGenre}</b>Airs on {$network}
			<p><i class='fas fa-star' style='color:#ee9c0c;'></i>{$rating}</p>
			<div class='callout'>{$summary}
				Air on {$schedule_days} at {$schedule_time}<br>
				Updated : {$updated}
			</div>
		</div>";
		$HTMLOUT .= "<div class='large-12 columns'><ul class='menu' data-responsive-accordion-tabs='tabs medium-accordion large-tabs' id='example-tabs'>";
		$count = 0;
		foreach ($tvshow_s as $season){
			$season_ep_order = $season['episodeOrder'];
			$season_number = $season['number'];
			if ($count == 0) 
				$HTMLOUT.= "<li class='tabs-title is-active' aria-selected='true'><a href='#panel{$season_number}'>{$season_number}</a></li>";
			else 
				$HTMLOUT .= "<li class='tabs-title'><a href='#panel{$season_number}'>{$season_number}</a></li>";
			$count++;
			}
		   $HTMLOUT .= "</ul>
		   <div class='{$design['tabs_content']}' {$design['data_tabs_content']}='example-tabs'>";
		$count = 0;
		foreach ($tvshow_s as $season) {
			$season_ep_order = $season['episodeOrder'];
			$season_number = $season['number'];	
			if ($count == 0) $HTMLOUT.= "<div class='{$design['tabs_panel']} {$design['is_active']}' id='panel{$season_number}'>";
		else
		$HTMLOUT.= "<div class='{$design['tabs_panel']}' id='panel{$season_number}'>";
		$HTMLOUT.= "<b>Season {$season_number}</b>
			<table class='responsive-card-table striped'>
				<thead>
					<tr>
						<th>Episode</th>
						<th><b>AirDate</b></th>
						<th><b>Name</b></th>
						<th><b>Search</b></th>
						<th><b>Status</b></th>
					</tr>
				</thead><tbody>";
				foreach ($tvshow_ep as $key => $episodes){			
					$episode_airdate = $episodes['airdate'];
					$number_ep = $episodes['number'];
					$airdate_ep = $episodes['airdate'];
					$name_ep = $episodes['name'];
					$runtime_ep = $episodes['runtime'];
					$season_ep = $episodes['season'];
					$id_ep = $episodes['id'];
					if ($season_number == $season_ep) {
					$HTMLOUT .= "<tr>
						<td data-label='Episode'>{$number_ep}</td>
						<td data-label='AirDate'><p>{$airdate_ep}</p></td>
						<td data-label='Name'><p>{$name_ep}</p></td>
						<td data-label='Search'><a href='browse.php?search={$name} S".($season_ep < 10 ? '0'.$season_ep : $season_ep)."E".($number_ep < 10 ? '0'.$number_ep : $number_ep)."&amp;searchin=title&amp;incldead=1' target='_blank'><i class='fas fa-search'></i></a></td>
						<td data-label='Status'><p>{$runtime_ep}<p></td>
					</tr>";
					$count++;
					}
				}
				$HTMLOUT .= "
				</tbody></table>";
		$HTMLOUT .= "</div>";
		$count++;
		}
	$HTMLOUT .= "</div></div></div>";	
echo stdhead("{$name}") . $HTMLOUT . stdfoot();
?>