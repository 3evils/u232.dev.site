<?php if (!defined('BB2_CORE')) die("I said no cheating!");

/**********************************************************************************
* roundtripdns.inc.php - Originated in Forum Firewall Mod for SMF
* Version 1.4.2 by JMiller a/k/a butchs
* (http://www.eastcoastrollingthunder.com) 
*********************************************************************************
* This program is distributed in the hope that it is and will be useful, but
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE.
**********************************************************************************/

# Round trip DNS verification

# Returns TRUE if DNS matches; FALSE on mismatch
# Returns true if an error occurs
# TODO: Not IPv6 safe
# FIXME: Returns false on DNS server failure; PHP provides no distinction
# between no records and error condition

function bb2_roundtripdns($ip,$domain)
{
	global $modSettings;

	if ((!isset($modSettings['badbehavior_roundtripdns'])) || (!$modSettings['badbehavior_roundtripdns']) || (empty($modSettings['badbehavior_roundtripdns']))) return $ip;

	if (@is_ipv6($ip)) return $ip;
	if ($ip == '') return $ip;
  	if (empty($ip)) return $ip;
	if (empty($domain)) return $ip;

	if (!function_exists('is_callable')) return $ip;
	if ((!is_callable('gethostbyaddr')) || (!is_callable('gethostbynamel'))) return $ip;

	$host = gethostbyaddr($ip);
	if ($host === $ip) return $ip;  //  failure

	$host_result = strpos(strrev($host), strrev($domain));
	
	if ($host_result !== false) {
		$addrs = @gethostbynamel($host);
		if (($addrs !== false) && (!empty($addrs)) && is_array($addrs)) {  //  Valid result
			if (sizeof($addrs) !== 0) {  //  not empty
				$flipped_addrs = array();
				$flipped_addrs = array_flip($addrs);
				if ($flipped_addrs !== null) {
					if (isset($flipped_addrs[$ip])) {   // is ip in an array
						unset($addrs, $flipped_addrs);  //  clear arrays from memory
						return true;
				}	}
				unset($flipped_addrs);
			}
			unset($addrs);
		} else {  //  not array
		 	if ($addrs === $ip) return true;
			if (is_array($addrs)) return $ip;
	}	}

	return false;
}