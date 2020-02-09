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
//==Connectable and port shit
if ($user['paranoia'] < 1 || $CURUSER['id'] == $id || $CURUSER['class'] >= UC_STAFF) {
        $What_Cache = (OCELOT_TRACKER == true ? 'port_data_xbt_' : 'port_data_' );
    if (($port_data = $mc1->get_value($What_Cache . $id)) === false) {
        if(OCELOT_TRACKER == true) {
        $q1 = sql_query('SELECT `connectable`, `peer_id` FROM `xbt_files_users` WHERE uid = ' . sqlesc($id) . ' LIMIT 1') or sqlerr(__FILE__, __LINE__);
        } else {
        $q1 = sql_query('SELECT connectable, port, agent FROM peers WHERE userid = ' . sqlesc($id) . ' LIMIT 1') or sqlerr(__FILE__, __LINE__);
        }
        $port_data = mysqli_fetch_row($q1);
        $mc1->cache_value('port_data_' . $id, $port_data, $INSTALLER09['expires']['port_data']);
    }
    if ($port_data > 0) {
        $connect = $port_data[0];
        $port = (OCELOT_TRACKER == true ? '' : $port_data[1]);
        $Ident_Client = (OCELOT_TRACKER == true ? $port_data['1'] : $port_data[2]);
        $OCELOT_or_PHP = (OCELOT_TRACKER == true ? '1' : 'yes');
        if ($connect == $XBT_or_PHP) {
            $connectable = "<font color='green'><i class='fa fa-check' aria-hidden='true'></i></font>";
        } else {
            $connectable = "<font color='red'><i class='fa fa-times' aria-hidden='true'></i></font>";
        }
    } else {
        $connectable = "<font color='orange'><i class='fa fa-question' aria-hidden='true'></i></b></font>";
    }
    $HTMLOUT.= "<tr><td><strong>{$lang['userdetails_connectable']}</strong></td><td align='left'>" . $connectable . "</td></tr>";
    if (!empty($port)) $HTMLOUT.= "<tr><td>{$lang['userdetails_port']}</td><td class='float-left'>" . htmlsafechars($port) . "</td></tr>
    <tr><td>{$lang['userdetails_client']}</td><td class='float-left'>" . htmlsafechars($Ident_Client) . "</td></tr>";
}
//==End
// End Class
// End File
