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
$txt['badbehavior_config'] = 'Panel de Administración Bad Behavior';
$txt['badbehavior_admin'] = 'Administrar Bad Behavior';
$txt['badbehavior_admin_desc'] = 'Configurar y Administrar Bad Behavior';
$txt['badbehavior_settings_title'] = 'Configuración';
$txt['badbehavior_settings_desc'] = 'Configurar el bloqueo automático de spam en su web';
$txt['badbehavior_reports_desc'] = 'Examinar registros de spam bloqueado en su web';
$txt['badbehavior_about_title'] = 'Acerca de';
$txt['badbehavior_about_desc'] = 'Acerca de Bad Behavior';
$txt['badbehavior_version_c'] = 'Versión/Créditos';
$txt['badbehavior_cversion'] = 'Versión SMF Port Modification';
$txt['badbehavior_coredesc'] = 'Bad Behavior consta de un núcleo principal (Core Engine) y este Mod, que le permite trabajar en SMF. Sus respectivos autores son responsables de cada componente en particular.';
$txt['badbehavior_oview'] = 'El módulo Bad Behavior examina las solicitudes HTTP de los visitantes de su web y registra las solicitudes sospechosas para su revisión posterior. Las visitas sospechosas se muestran en el informe.';
$txt['badbehavior_minfo'] = 'Para más información, por favor visita <a href="http://www.bad-behavior.ioerror.us/">Bad Behavior</a>.';
$txt['badbehavior_msupport'] = 'Para apoyar al autor del Mod e inspirar futuras actualizaciones y mejoras.<br /><br /><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=UJTMMF8FKGLZ6&lc=US&item_name=butchs%2f%20continued%20updates&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted"><img src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif"></a>';
$txt['badbehavior_csupport'] = 'Para apoyar al autor del Core Engine, por favor considera hacer una <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=error%40ioerror%2eus&item_name=Bad%20Behavior%20<?php echo BB2_VERSION; ?>%20%28From%20Admin%29&no_shipping=1&cn=Comments%20about%20Bad%20Behavior&tax=0&currency_code=USD&bn=PP%2dDonationsBF&charset=UTF%2d8">donación</a> a fin de ayudar al desarrollo de Bad Behavior.';
$txt['badbehavior_stats_title'] = 'Estadísticas';
$txt['badbehavior_logging_title'] = 'Registro';
$txt['badbehavior_security'] = 'Seguridad';
$txt['badbehavior_reverse_load'] = 'Proxy Inverso/Carga Equilibrada';
$txt['badbehavior_settings_sub'] = 'Configuración Bad Behavior';
$txt['enable_badbehavior'] = 'Activar Bad Behavior';
$txt['badbehavior_ooptions'] = 'Varios';
$txt['badbehavior_email_allow'] = 'Notificar al administrador cuando se elimina spam<br /><span class="smalltext">No Recomendado</span>';
$txt['badbehavior_display_stats'] = 'Mostrar estadísticas';
$txt['badbehavior_verbose'] = 'HTTP Detallado<br /><span class="smalltext">Registro DEBE estar habilitado</span>';
$txt['badbehavior_logging'] = 'Registro<br /><span class="smalltext">Recomendado</span>';
$txt['badbehavior_strict'] = 'Control Estricto<br /><span class="smalltext">No Recomendado</span>';
$txt['badbehavior_offsite_forms'] = 'Formularios Externos';
$txt['badbehavior_reverse_proxy'] = 'Activar Proxy Inverso';
$txt['badbehavior_reverse_proxy_header'] = 'Llamada IP a Proxy Inverso';
$txt['badbehavior_reverse_proxy_addresses'] = 'Dirección IP Proxy Inverso';
$txt['badbehavior_roundtripdns'] = 'Motor de Búsqueda DNS';
$txt['badbehavior_cache_duration'] = 'Duración Caché';
$txt['badbehavior_core'] = ' Versión Core Engine Bad Behavior';
$txt['badbehavior_author'] = ' Autor Core:  <a href="http://www.bad-behavior.ioerror.us/">Michael Hampton</a>';
$txt['badbehavior_mauthor'] = 'Autor SMF Port Modification: <a href="http://www.eastcoastrollingthunder.com/">butchs</a>';
$txt['badbehavior_permitted'] = 'PERMITIDO';
$txt['badbehavior_denied'] = 'DENEGADO';
$txt['badbehavior_empty'] = 'No existen registros en ese rango';
$txt['badbehavior_log_title'] = 'Bad Behavior - Informes';
$txt['badbehavior_event_title'] = 'Bad Behavior - Detalles de eventos';
$txt['badbehavior_log_id'] = 'ID';
$txt['badbehavior_log_ip'] = 'IP';
$txt['badbehavior_log_date'] = 'FECHA';
$txt['badbehavior_log_method'] = 'MÉTODO';
$txt['badbehavior_log_uri'] = 'URI';
$txt['badbehavior_log_protocol'] = 'PROTOCOLO';
$txt['badbehavior_log_headers'] = 'CABECERAS';
$txt['badbehavior_log_agent'] = 'AGENTE';
$txt['badbehavior_log_enity'] = 'ENTIDAD';
$txt['badbehavior_log_key'] = 'CLAVE';
$txt['badbehavior_reason'] = ' RAZóN: ';
$txt['badbehavior_explain'] = 'EXPLICACIÓN: ';
$txt['badbehavior_error'] = 'ERROR: ';
$txt['badbehavior_report_all_title'] = 'TODAS las entradas';
$txt['badbehavior_report_permit_title'] = 'entradas PERMITIDAS';
$txt['badbehavior_report_denied_title'] = 'entradas DENEGADAS';
$txt['badbehavior_rec_disp'] = 'Mostrando registro(s)';
$txt['badbehavior_to'] = ' a ';
$txt['badbehavior_from'] = 'de ';
$txt['badbehavior_rec_tot'] = ' total ';
$txt['badbehavior_type_all'] = '(TODOS los registros).';
$txt['badbehavior_type_perm'] = '(solamente PERMITIDOS).';
$txt['badbehavior_type_den'] = '(solamente DENEGADOS).';
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
$txt['badbehavior_search_engine'] = 'Motor de búsqueda ';
$txt['badbehavior_suspicious'] = 'Sospechoso';
$txt['badbehavior_harvester'] = 'Recolector';
$txt['badbehavior_comment_spammer'] = 'Spammer Comentador';
$txt['badbehavior_threat_level'] = 'Nivel de amenaza ';
$txt['badbehavior_age'] = 'Edad ';
$txt['badbehavior_days'] = ' días';
$txt['badbehavior_theadmin'] = 'el WEBMA5TER';
$txt['badbehavior_nospam'] = '+nospam@nospam.';
$txt['badbehavior_dot'] = '+nospam.nospam.';
$txt['badbehavior_dash'] = '+nospam-nospam.';
$txt['badbehavior_mailto'] = 'mailto:';
$txt['badbehavior_httpbl'] ='Project Honey Pot HTTP Blacklist';
$txt['badbehavior_httpbl_key'] ='Clave Acceso http:BL';
$txt['badbehavior_httpbl_threat'] ='Nivel de amenaza mínimo<br /><span class="smalltext">(25 es lo recomendado)</span>';
$txt['badbehavior_httpbl_maxage'] ='Número de días máximo<br /><span class="smalltext">(30 es lo recomendado)</span>';
$txt['badbehavior_httpbl_word'] ='Palabra de enlace Honeypot';
$txt['badbehavior_httpbl_link'] ='Ruta enlace a Honeypot';
$txt['badbehavior_block_ua'] = 'Bloquear User Agents en blanco'
?>