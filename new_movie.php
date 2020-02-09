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
    require_once INCL_DIR . 'bbcode_functions.php';
    require_once INCL_DIR . 'html_functions.php';
    dbconn(false);
    loggedinorreturn();
    $lang = load_language('global');
    $htmlout = '';
    define('IMDB_IMG_DIR', BITBUCKET_DIR . DIRECTORY_SEPARATOR . 'imdb');
    if (!is_dir(IMDB_IMG_DIR)) {
        mkdir(IMDB_IMG_DIR);
    }
    $INSTALLER09['expires']['imdb_upcoming'] = 1440; // 1440 = 1 day
    if (($imdb_upcoming = $mc1->get_value('imdb_upcoming_')) === false) {
        $cr2 = curl_init("https://www.imdb.com/movies-coming-soon/");
        curl_setopt($cr2, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
        curl_setopt($cr2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cr2, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($cr2, CURLOPT_POST, 0);
        curl_setopt($cr2, CURLOPT_HTTPHEADER, array("Accept-language: en\r\n"));
        curl_setopt($cr2, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cr2, CURLOPT_SSL_VERIFYHOST, false);
        $imdbhtml = curl_exec($cr2);
        curl_close($cr2);
        preg_match_all('/<h4.*<a name=.*>(.*)&nbsp;/i', $imdbhtml, $datestemp);
        $dates = $datestemp[1];
        $regex = '';
        foreach ($dates as $date) {
            $regex .= '<a name(.*)';
        }
        $regex .= 'see-more';
        preg_match("/$regex/isU", $imdbhtml, $datemovies);
        $temp = array();
        foreach ($datemovies as $key => $value) {
            preg_match_all('/<table(.*)<\/table/isU', $value, $out);
            if ($key != 0) {
                $temp[$dates[$key - 1]] = $out[1];
            }
     
        }
        foreach ($dates as $date) {
            $i = 0;
            foreach ($temp[$date] as $code) {
                preg_match('/src="(.*)".*"\/title\/(tt\d+)\/.*".*title="(.*)".*itemprop="genre">(.*)<\/p>.*description">(.*)<\/div>.*itemprop=\'url\'>(.*)<\/a>.*Stars:(.*)<\/div>/isU', $code, $out);
                foreach ($out as $key => $value) {
                    if ($key != 0) {
                        $out[$key] = strip_tags($value);
                    }
                }
                $imdbout[$date][$i]['title'] = $out[3];
                $imdbout[$date][$i]['num'] = $out[2];
                get_imdbimg($out[1], $out[2]);
                $imdbout[$date][$i]['genres'] = preg_replace('/\s+/', ' ', $out[4]);
                $imdbout[$date][$i]['plot'] = preg_replace('/^\s+/', ' ', $out[5]);
                $imdbout[$date][$i]['director'] = preg_replace('/^\s+/', ' ', $out[6]);
                $imdbout[$date][$i]['stars'] = preg_replace('/\s+/', ' ', $out[7]);
                $i++;
            }
        }
        $imdb_upcoming = serialize($imdbout);
        $mc1->cache_value('imdb_upcoming_', $imdb_upcoming, $INSTALLER09['expires']['imdb_upcoming']);
    }
     
    $dates = unserialize($imdb_upcoming);
    $htmlout = '';
    $htmlout .= "<h3 class='text-center'>Upcoming Movies</h3>";
    foreach ($dates as $date => $items) {
            $htmlout .= "<h4 class='text-center'>{$date}</h4>";
            $htmlout .= "<div class='row small-up-1 medium-up-3 large-up-5'>";
        foreach ($items as $row) {
            $htmlout .= "<div class='column column-block'>

            <div class='card-divider'><a href='https://www.imdb.com/title/{$row['num']}'>{$row['title']}</a></div>
<a href=\"img.php/imdb/" . htmlsafechars($row["num"]) . ".jpg\"><img src=\"img.php/imdb/" . htmlsafechars($row["num"]) . ".jpg\" border=\"0\" width=\"214\" height=\"305\" alt=\"{$row['title']}\" title=\"{$row['title']}\" /></a>
<div class='callout'><a data-open='showsModal{$row['num']}' class='tiny button'>Read More</a></div>    
 ";
           $htmlout .= "<div class='large reveal row' id='showsModal{$row['num']}' data-reveal>
            <p><b><font color='rgb(67,158,76)'>Genre(s):</font></b>&nbsp;" . $row['genres'] . "<br>
               <b><font color='rgb(67,158,76)'>Director:</font></b>&nbsp;" . $row['director'] . "<br >
               <b><font color='rgb(67,158,76)'>Starring:</font></b>&nbsp;" . $row['stars'] . "<br> 
               <b><font color='rgb(67,158,76)'>Plot:</font></b>&nbsp;" . CutName($row['plot'], 200) . "<br>
             </p></div></div>";
        }
        $htmlout .= "</div>";
    }
     
    echo stdhead("Upcoming Movies") . $htmlout . stdfoot();
    function get_imdbimg($img, $num) {
        if (!file_exists(IMDB_IMG_DIR . DIRECTORY_SEPARATOR . $num . ".jpg")) {
            $poster = str_replace('http://', 'https://', $img);
            $cr = curl_init($poster);
            curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
            $imgfile = curl_exec($cr);
            $curlResponse = curl_getinfo($cr);
            curl_close($cr);
            file_put_contents(IMDB_IMG_DIR . DIRECTORY_SEPARATOR . $num . ".jpg", $imgfile);
            if (filesize(IMDB_IMG_DIR . DIRECTORY_SEPARATOR . $num . ".jpg") == 0) {
                unlink(IMDB_IMG_DIR . DIRECTORY_SEPARATOR . $num . ".jpg");
            }
        }
    }
     