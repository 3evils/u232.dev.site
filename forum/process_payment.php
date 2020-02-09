<?php

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$email = isset($_POST['email']) ? $_POST['email'] : false;
	$amount = isset($_POST['amount']) ? (int)$_POST['amount'] : false;
	$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id']: false;
	if(!$email || !$amount)
		dheader('location: https://forum.u-232.com' ) ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
        <meta http-equiv='Content-Language' content='en-us' />	
		<title>Processing payment</title>
		<style type="text/css">
			#paypal_form {
				display:none;
			}
			#loading {
			   height: 100px;
			   width: 100px;
			   position: fixed;
			   z-index: 1000;
			   left: 50%;
			   top: 50%;
			   margin: -25px 0 0 -25px;
			}
		</style>
	</head>
<body>
<div id="loading">
	Please wait...
</div>
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" id="paypal_form">
	<input type='hidden' name='business' value='u232_1359650550_biz@gmail.com' />
	<input type='hidden' name='cmd' value='_xclick' />
	<input type='hidden' name='amount' value='<?php echo $amount; ?>' />
	<input type='hidden' name='item_name' value='Paid package - 5 USD for U-232' />
	<input type='hidden' name='currency_code' value='USD' />
	<input type='hidden' name='no_shipping' value='1' />
	<input type='hidden' name='notify_url' value='https://u-232.com/donatecheck.php' />
	<input type='hidden' name='email' value='<?php echo $email ?>' />
	<input type='hidden' name='rm' value='2' />
	<input type='hidden' name='custom' value='<?php echo $user_id; ?>' />
	<input type='hidden' name='return' value='https://forum.u-232.com' />
	<!--<input type='submit' value='Donate 5 USD' />-->
</form>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#paypal_form').submit();
	});
</script>
</body>
</html>
<?php
exit;
} else {
	header('location: https://forum.u-232.com' ) ;
}

?>