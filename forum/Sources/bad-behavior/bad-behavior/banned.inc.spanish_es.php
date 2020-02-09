<?php if (!defined('BB2_CORE')) die('I said no cheating!');

// Functions called when a request has been denied
// This part can be gawd-awful slow, doesn't matter :)

require_once(BB2_CORE . "/responses.inc.spanish_es.php");

function bb2_housekeeping($settings, $package)
{
	// FIXME Yes, the interval's hard coded (again) for now.
	//  Pruning log is handled as a daily SMF scheduled task

	// Waste a bunch more of the spammer's time, sometimes.
	if (rand(1,1000) == 1) {
		// SMF optimizes tables via a scheduled task
	}
}

function bb2_display_denial($settings, $package, $key, $previous_key = false)
{
	define('DONOTCACHEPAGE', true);	// WP Super Cache
	if (!$previous_key) $previous_key = $key;
	if ($key == "e87553e1") {
		// FIXME: lookup the real key
	}
	// Create support key
	$ip = explode(".", $package['ip']);
	$ip_hex = "";
	foreach ($ip as $octet) {
		$ip_hex .= str_pad(dechex($octet), 2, 0, STR_PAD_LEFT);
	}
	$support_key = implode("-", str_split("$ip_hex$key", 4));

// Bad Behavior for SMF start
	global $modSettings;
	$honeyLink='';
	if ((isset($modSettings['badbehavior_httpbl_link']) && !empty($modSettings['badbehavior_httpbl_link'])) && (isset($modSettings['badbehavior_httpbl_word']) && !empty($modSettings['badbehavior_httpbl_word']))) {
		$honeyLink = '<!-- <a href="'. $modSettings['badbehavior_httpbl_link'].'">'. $modSettings['badbehavior_httpbl_word'].'</a> -->';
	} else {
		$honeyLink = '';
	}
// Bad Behavior for SMF end 

	// Get response data
	$response = bb2_get_response($previous_key);
	header("HTTP/1.1 " . $response['response'] . " Bad Behavior");
	header("Status: " . $response['response'] . " Bad Behavior");
	$request_uri = $_SERVER["REQUEST_URI"];
	if (!$request_uri) $request_uri = $_SERVER['SCRIPT_NAME'];	# IIS
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--< html xmlns="http://www.w3.org/1999/xhtml">-->
<head>
<title>Error HTTP <?php echo $response['response']; ?></title>
</head>
<body>
<h1>Error <?php echo $response['response']; ?></h1>
<p>Lo sentimos, pero no podemos satisfacer su solicitud de
<?php echo htmlspecialchars($request_uri) ?> en este servidor.</p>
<p><?php echo $response['explanation']; ?></p>
<p>Su clave de soporte t&eacute;cnico es: <strong><?php echo $support_key; ?></strong></p>
<p>Puede usar esta clave para <a href="http://www.ioerror.us/bb2-support-key?key=<?php echo $support_key; ?>">solucionar el problema usted mismo</a>.</p><?php echo $honeyLink; ?>
<p>Si no consigue solucionar el problema usted mismo, por favor contacte <a href="<?php echo bb2_email_scramble(); ?>"><?php echo bb2_email_to(); ?></a> y aseg&uacute;rese de proporcionar la clave de soporte t&eacute;cnico que aparece arriba.</p>
<?php
// Bad Behavior for SMF start
global $modSettings;

if ($modSettings['badbehavior_email_allow']) {
	global $webmaster_email, $context, $txt, $scripturl, $sourcedir;

	$emailsubject = $emailbody = $emailcontact = '';

	require_once($sourcedir . '/Subs-Post.php');

	$emailcontact = bb2_email();
	$emailsubject = "Mod Bad Behavior SMF ha eliminado spam!";
	$emailbody = " Mod Bad Behavior ha bloqueado spam.\n\r";
	$emailbody .= " Clave de soporte: ".$support_key."\n\r";
	$emailbody .= " Error ".$response['response']."\n\r";
	$emailbody .= " para ".htmlspecialchars($request_uri)."\n\r";
	$emailbody .= " Explicaci&oacute;n: ".$response['explanation']."\n\r";
	$emailbody .= " Registrado como: ".$response['log']."\n\r";

	sendmail($emailcontact, $emailsubject, $emailbody, null, null, false, 0);
}
// Bad Behavior for SMF end
}

function bb2_log_denial($settings, $package, $key, $previous_key=false)
{
	if (!$settings['logging']) return;
	bb2_db_query(bb2_insert($settings, $package, $key));
}
