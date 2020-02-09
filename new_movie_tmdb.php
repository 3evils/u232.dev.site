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
require_once __DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php';
require_once INCL_DIR . 'user_functions.php';
require_once (INCL_DIR . 'bbcode_functions.php');
require_once (INCL_DIR . 'pager_functions.php');
require_once INCL_DIR . 'html_functions.php';
require_once INCL_DIR . 'getpre.php';
//require_once TMDB_DIR . 'functions.php';

dbconn(false);
loggedinorreturn();
require_once(TEMPLATE_DIR.''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'html_functions' . DIRECTORY_SEPARATOR . 'global_html_functions.php'); 
require_once(TEMPLATE_DIR.''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'html_functions' . DIRECTORY_SEPARATOR . 'navigation_html_functions.php');
$design = array_merge(load_design());
$lang = load_language('global');
$HTMLOUT = '';

$key = '3c387120fb64bef7e859affa4e290d6d';
$ca = curl_init();
curl_setopt($ca, CURLOPT_URL, "http://api.themoviedb.org/3/configuration?api_key=".$key);
curl_setopt($ca, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ca, CURLOPT_HEADER, FALSE);
curl_setopt($ca, CURLOPT_HTTPHEADER, array("Accept: application/json"));
$response = curl_exec($ca);
curl_close($ca);
$config = json_decode($response, true);


$possible_actions = array(
    'latest',
    'now_playing',
	'popular',
    'popular_kids',
    'top_rated',
    'upcoming'
);
$action = isset($_GET["action"]) ? htmlsafechars(trim($_GET["action"])) : '';
if (!in_array($action, $possible_actions)) 
	stderr('<br /><div class="alert alert-error span11">Error</div>', 'something went wrong');
$HTMLOUT.= "<div class='row'>
       <div class='large-12 columns'>
</div>
</div>
<div class='row'>
		<ul class='tabs' data-responsive-accordion-tabs='tabs medium-accordion large-tabs' id='usercp-tabs'>
				  <li class='tabs-title is-active'><a href='new_movie_tmdb.php?action=latest'>Latest</a></li>
				  <li class='tabs-title'><a href='new_movie_tmdb.php?action=now_playing'>Now Playing</a></li>
				  <li class='tabs-title'><a href='new_movie_tmdb.php?action=popular'>Popular</a></li>
				  <li class='tabs-title'><a href='new_movie_tmdb.php?action=popular_kids'>Popular Kids</a></li>
				  <li class='tabs-title'><a href='new_movie_tmdb.php?action=top_rated'>Top Rated</a></li>
				  <li class='tabs-title'><a href='new_movie_tmdb.php?action=upcoming'>Upcoming Movies</a></li>
		</ul>
</div>";
if ($action == "latest") {
	require_once (TMDB_DIR . 'latest_movie.php');
}
elseif ($action == "now_playing") {
	require_once (TMDB_DIR . 'now_playing.php');
}
elseif ($action == "popular") {
	require_once (TMDB_DIR . 'popular.php');
}
elseif ($action == "popular_kids") {
	require_once (TMDB_DIR . 'popular_kids.php');
}
elseif ($action == "top_rated") {
	require_once (TMDB_DIR . 'top_rated.php');
}
elseif ($action == "upcoming") {
	require_once (TMDB_DIR . 'upcoming_movies.php');
}

echo stdhead("Upcoming Movies") . $HTMLOUT . stdfoot();