<?php
/**********************************************************************************
* BadBehavior.english.php - PHP language file for Bad Behavior mod
* Version 1.4.7 by JMiller a/k/a butchs
* (http://www.eastcoastrollingthunder.com) 
*********************************************************************************
* This program is distributed in the hope that it is and will be useful, but
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE.
**********************************************************************************/

global $settings, $scripturl;

$txt['badbehavior_cversion_mod'] = '1.4.7';
$txt['badbehavior'] = 'Bad Behavior';
$txt['badbehavior_config'] = 'Bad Behavior Admin Panel';
$txt['badbehavior_admin'] = 'Bad Behavior Admin';
$txt['badbehavior_admin_desc'] = 'Configure and Manage Bad Behavior';
$txt['badbehavior_settings_title'] = 'Settings';
$txt['badbehavior_settings_desc'] = 'Configure automatic spam blocking for your site';
$txt['badbehavior_reports_desc'] = 'Examine the spam blocking logs for your web site';
$txt['badbehavior_about_title'] = 'About';
$txt['badbehavior_about_desc'] = 'Bad Behavior About';
$txt['badbehavior_version_c'] = 'Version/Credits';
$txt['badbehavior_cversion'] = 'SMF Port Modification Version';
$txt['badbehavior_coredesc'] = 'Bad Behavior is a core engine plus this mod to make it work on SMF or any other port and, the individuals are responsible for each individual component.';
$txt['badbehavior_oview'] = 'The Bad Behavior module examines HTTP requests of visits to your web site, and any suspicious requests are logged for later review.  The suspicious visit is shown in the report.';
$txt['badbehavior_minfo'] = 'For more information please visit the <a href="http://www.bad-behavior.ioerror.us/">Bad Behavior</a> homepage.';
$txt['badbehavior_msupport'] = 'To support the mod author and inspire future updates and improvemnts to this mod.<br /><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=UJTMMF8FKGLZ6&lc=US&item_name=butchs%2f%20continued%20updates&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted"><img src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif"></a>';
$txt['badbehavior_csupport'] = 'To support the Core Engine Author, please consider <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=error%40ioerror%2eus&item_name=Bad%20Behavior%20<?php echo BB2_VERSION; ?>%20%28From%20Admin%29&no_shipping=1&cn=Comments%20about%20Bad%20Behavior&tax=0&currency_code=USD&bn=PP%2dDonationsBF&charset=UTF%2d8">donating</a> to help further development of Bad Behavior.';
$txt['badbehavior_stats_title'] = 'Statistics';
$txt['badbehavior_logging_title'] = 'Logging';
$txt['badbehavior_security'] = 'Security';
$txt['badbehavior_reverse_load'] = 'Reverse Proxy/Load Balancer';
$txt['badbehavior_settings_sub'] = 'Bad Behavior Settings';
$txt['enable_badbehavior'] = 'Enable Bad Behavior';
$txt['badbehavior_ooptions'] = 'Miscellaneous';
$txt['badbehavior_email_allow'] = 'Notify administrator when spam is killed<br /><span class="smalltext">Not Recommended</span>';
$txt['badbehavior_display_stats'] = 'Display statistics';
$txt['badbehavior_verbose'] = 'Verbose HTTP<br /><span class="smalltext">Logging MUST be enabled</span>';
$txt['badbehavior_logging'] = 'Logging<br /><span class="smalltext">Recommended</span>';
$txt['badbehavior_strict'] = 'Strict<br /><span class="smalltext">Not Recommended</span>';
$txt['badbehavior_offsite_forms'] = 'Offsite Forms';
$txt['badbehavior_reverse_proxy'] = 'Enable Reverse Proxy';
$txt['badbehavior_reverse_proxy_header'] = 'IP call to Reverse Proxy';
$txt['badbehavior_reverse_proxy_addresses'] = 'Reverse Proxy Addresses';
$txt['badbehavior_roundtripdns'] = 'Search Engine DNS';
$txt['badbehavior_cache_duration'] = 'Cache Duration';
$txt['badbehavior_core'] = ' Core Engine Bad Behavior Version';
$txt['badbehavior_author'] = ' Core Author:  <a href="http://www.bad-behavior.ioerror.us/">Michael Hampton</a>';
$txt['badbehavior_mauthor'] = 'SMF Port Modification Author: <a href="http://www.eastcoastrollingthunder.com/">butchs</a>';
$txt['badbehavior_permitted'] = 'PERMITTED';
$txt['badbehavior_denied'] = 'DENIED';
$txt['badbehavior_empty'] = 'No records exist in that range';
$txt['badbehavior_log_title'] = 'Bad Behavior - Reports';
$txt['badbehavior_event_title'] = 'Bad Behavior - Event Details';
$txt['badbehavior_log_id'] = 'ID';
$txt['badbehavior_log_ip'] = 'IP';
$txt['badbehavior_log_date'] = 'DATE';
$txt['badbehavior_log_method'] = 'METHOD';
$txt['badbehavior_log_uri'] = 'URI';
$txt['badbehavior_log_protocol'] = 'PROTOCOL';
$txt['badbehavior_log_headers'] = 'HEADERS';
$txt['badbehavior_log_agent'] = 'AGENT';
$txt['badbehavior_log_enity'] = 'ENTITY';
$txt['badbehavior_log_key'] = 'KEY';
$txt['badbehavior_reason'] = ' REASON: ';
$txt['badbehavior_explain'] = 'EXPLANATION: ';
$txt['badbehavior_error'] = 'ERROR: ';
$txt['badbehavior_report_all_title'] = 'ALL entries log';
$txt['badbehavior_report_permit_title'] = 'PERMITTED entries log';
$txt['badbehavior_report_denied_title'] = 'DENIED entries log';
$txt['badbehavior_rec_disp'] = 'Displaying record(s)';
$txt['badbehavior_to'] = ' to ';
$txt['badbehavior_from'] = 'of ';
$txt['badbehavior_rec_tot'] = ' total ';
$txt['badbehavior_type_all'] = '(ALL records).';
$txt['badbehavior_type_perm'] = '(PERMITTED records only).';
$txt['badbehavior_type_den'] = '(DENIED records only).';
$txt['badbehavior_colin'] = ': ';
$txt['badbehavior_engines1'] = 'AltaVista';
$txt['badbehavior_engines2'] = 'Teoma/Ask Crawler';
$txt['badbehavior_engines3'] = 'Baidu';
$txt['badbehavior_engines4'] = 'Excite';
$txt['badbehavior_engines5'] = 'Google';
$txt['badbehavior_engines6'] = 'Looksmart';
$txt['badbehavior_engines7'] = 'Lycos';
$txt['badbehavior_engines8'] = 'MSN';
$txt['badbehavior_engines9'] = 'Yahoo';
$txt['badbehavior_engines10'] = 'Cull';
$txt['badbehavior_engines11'] = 'Infoseek';
$txt['badbehavior_engines12'] = 'Minor Search Engine';
$txt['badbehavior_search_engine'] = 'Search engine ';
$txt['badbehavior_suspicious'] = 'Suspicious';
$txt['badbehavior_harvester'] = 'Harvester';
$txt['badbehavior_comment_spammer'] = 'Comment Spammer';
$txt['badbehavior_threat_level'] = 'Threat level ';
$txt['badbehavior_age'] = 'Age ';
$txt['badbehavior_days'] = ' days';
$txt['badbehavior_theadmin'] = 'the WEBMA5TER';
$txt['badbehavior_nospam'] = '+nospam@nospam.';
$txt['badbehavior_dot'] = '+nospam.nospam.';
$txt['badbehavior_dash'] = '+nospam-nospam.';
$txt['badbehavior_mailto'] = 'mailto:';
$txt['badbehavior_httpbl'] ='Project Honey Pot HTTP Blacklist';
$txt['badbehavior_httpbl_key'] ='http:BL Access Key';
$txt['badbehavior_httpbl_threat'] ='Minimum Threat Level<br /><span class="smalltext">(25 is recommended)</span>';
$txt['badbehavior_httpbl_maxage'] ='Maximum Age of Data<br /><span class="smalltext">(30 is recommended)</span>';
$txt['badbehavior_httpbl_word'] ='Honeypot Link word';
$txt['badbehavior_httpbl_link'] ='Honeypot Link';
$txt['badbehavior_block_ua'] = 'Block empty User Agents'
?>