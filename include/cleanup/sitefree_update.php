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
function docleanup($data)
{
    global $INSTALLER09, $queries, $mc1;
    require_once(CLASS_DIR . 'tracker.class.php');
    set_time_limit(1200);
    ignore_user_abort(1);
    $oq = sql_query("SELECT * FROM `events` WHERE `oupdated` = 0 AND `endtime` < " . TIME_NOW) or sqlerr(__FILE__, __LINE__);
    while ($orow = mysqli_fetch_assoc($oq)) {
        if ($orow['freeleechEnabled'] == 1) {
            replaceInFile('^sitefree\s*= true', "sitefree\t\t\t= false", OCELOT_CONF);
        } elseif ($orow['duploadEnabled'] == 1) {
            replaceInFile('^sitedouble\s*= true', "sitedouble\t\t\t= false", OCELOT_CONF);
        } elseif ($orow['hdownEnabled'] == 1) {
            replaceInFile('^sitehalf\s*= true', "sitehalf\t\t\t= false", OCELOT_CONF);
        }
        Tracker::reload_config();
        sql_query("UPDATE `events` SET `oupdated` = 1 WHERE `id` = " . sqlesc($orow['id'])) or sqlerr(__FILE__, __LINE__);
    }
    if ($queries > 0) write_log("Site Events Clean -------------------- Site Events Complete using $queries queries--------------------");
    if (false !== mysqli_affected_rows($GLOBALS["___mysqli_ston"])) {
        $data['clean_desc'] = mysqli_affected_rows($GLOBALS["___mysqli_ston"]) . " items deleted/updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>
