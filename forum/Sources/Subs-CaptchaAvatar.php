<?php
if (!defined('SMF'))
	die('Hacking attempt...');

function makeVerificationAvatar()
{
	global $modSettings;

	$test = array();
	if (empty($_SESSION['AvatarsToShow']))
		$test = '';
	else
		$test = $_SESSION['AvatarsToShow'];
	
	$avatar = $_GET['avatar'];
	$type = $_GET['type'];
	
	// We got a confirmation image
	if ( $type == 2 ) 
	{
		if (empty($test))
			die();
		foreach ($_SESSION['AvatarsToShow'] as $img)
		{
			if ( $img[1] == $avatar )
			{
				$TheImg = $img[0];
				break;
			}
		}
	} 

	// We got the reference image
	elseif ( $type == 1 )
	{
 		$TheImg = $avatar;
 	}
	
	if ( !isset($TheImg) )
		die();
	
	$numberImage = false;
	for ($i = 0; $i < 10; $i++) {
		if (isset($TheImg) and $i == $TheImg and strlen($TheImg) == 1)
		{
			$numberImage = true;
		}
	}

	// If we don't have enough avatars... we make bright images with an integer within
	if ( $numberImage == true )
	{
		$finalIm = imagecreatetruecolor(65, 65);
		$mywhite = imagecolorallocate( $finalIm, rand(220,255),rand(220,255),rand(220,255));
		imagefilledrectangle( $finalIm,0, 0, 65, 65, $mywhite);
		$myBlue = imagecolorallocate( $finalIm, rand(100,150),rand(100,150),rand(100,150));
		imagechar($finalIm, rand(3,5), rand(0,53), rand(0,53), $TheImg, $myBlue);
		header("Content-type: image/jpeg");
		imagejpeg($finalIm);
		imagedestroy($finalIm);
			die();
	} else {

		if ( strtolower(substr($TheImg,-4)) == '.jpg' ) {
			$im = imagecreatefromjpeg($modSettings['avatar_directory'] . '/' . $TheImg);
		} elseif ( strtolower(substr($TheImg,-4)) == '.gif' ) {
			$im = imagecreatefromgif($modSettings['avatar_directory'] . '/' . $TheImg);
		} elseif ( strtolower(substr($TheImg,-4)) == '.png' ) {
			$im = imagecreatefrompng($modSettings['avatar_directory'] . '/' . $TheImg);
		}
    	$width = Imagesx($im);
    	$height = Imagesy($im);
		
		$orig_w = imagesx($im);
		$orig_h = imagesy($im);
		$orig_x = 0;
		$orig_y = 0;
			
		// No matter dimensions, we default the new height to 65px to have a nice layout
		$new_w = round($width * (65 / $height ));
		$new_h = round($height * (65 / $height ));

		$finalIm = imagecreatetruecolor($new_w, $new_h);
		$mywhite = imagecolorallocate( $finalIm, rand(220,255),rand(220,255),rand(220,255));
		imagefilledrectangle( $finalIm,0,0,$new_w,$new_h, $mywhite);
		
		// Randomize what we want to do with the image, but skip rotation with gifs
		if (strtolower(substr($TheImg,-4)) == '.gif' || !function_exists('imagerotate'))
			$actions = rand(2, 3);
		else
			$actions = rand(1, 3);

		// Rotate
		if ( $actions == 1 )
		{
			$imtrans = imagecolortransparent($im); // Find the transparent color of the existing image

			// Let's rotate at least 3 degrees
			$degrees = rand(1, 20) - 10;
			if ($degrees < 3 && $degrees > -3)
				$degrees = $degrees - 6;
				$im = imagerotate($im, $degrees, $imtrans);
		}

		// Crop
		if ( $actions == 2 ) {
			$offset = 8;	
			$crop = rand(1,4);
			$orig_w = $orig_w - $offset; $offset; $orig_h = $orig_h - $offset;
			if ( $crop == 1 ) // Crop from top left
				{ $orig_x = $offset; $orig_y = $offset; }
			if ( $crop == 2 ) // Crop from top right
				{ $orig_y = $offset; }
			if ( $crop == 3 ) // Crop from buttom left
				{ $orig_x = $offset; }
			if ( $crop == 4 ) // Crop from buttom right
				{ $donothing; }				
		}

		// Stretch
		if ( $actions == 3 )
		{
			$split1 = rand(40,60);
			if ($split1 > 50)
				$split2 = rand(38,45);
			else
				$split2 = rand(55,62);
				imagecopyresampled($finalIm,$im,0,0,$orig_x,$orig_y,round($new_w / 100 * $split1),$new_h,round($orig_w / 100 * $split2),$orig_h);
				imagecopyresampled($finalIm,$im,round($new_w / 100 * $split1),0,round($orig_w / 100 * $split2),0,$new_w - round($new_w / 100 * $split1),$new_h,$orig_w-round($orig_w / 100 * $split2),$orig_h);
				imagedestroy($im);
			} else {
				imagecopyresampled($finalIm,$im,0,0,$orig_x,$orig_y,$new_w,$new_h,$orig_w,$orig_h);
				imagedestroy($im);
			}
			
			header("Content-type: image/jpeg");
			if ($finalIm)
			{
				imagejpeg($finalIm);
				imagedestroy($finalIm);
			}
			die();
		}
}

?>