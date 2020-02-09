<?php
// -----------------------------------------------------------------------------------------------
// "Highslide 4 SMF (HS-4-SMF)" Mod for Simple Machines Forum (SMF) V2.0
// by spuds,
// version 0.7
// based on the mods: karlbenson:Integrate Lightbox v1.4 & diggers:Highslide Image Viewer V 1.6
// -----------------------------------------------------------------------------------------------
// Adds a highslide 'lighbox' effect to images and attachments.  With the mod enabled all images
// and optionally attachments will expand in place inside of a nice web2.0 frame.  Offers advanced
// admin configuration options and smart message based or topic based slide show options
// controls if multiple images or attachments are available.  Integrates with Aeva as well.
// -----------------------------------------------------------------------------------------------
// Instructions for Use:
// Just attach [img] images to your post as normal, the mod will do the rest for you during viewing.
// -----------------------------------------------------------------------------------------------

if (!defined('SMF')) 
	die('Hacking Attempt...');

/* This file contains the following functions

	hs4smf(&$message, $id_msg)
	- Main traffic cop for the rest of the function.  searches message for all img tags and
	  will wrap them in the correct anchor tag to allow the Highslide javascript function to
	  find them
	- $settings['hs4smf_img_count'] keeps track of how many images found for each area aeva, message, attachment

	hs4smf_fix_link($image)
	- Fixes the image link so they point to the full sized image instead of pointing to the image
	  hosting site, this allows them to expand in place and not redirect.

	hs4wsmf_anchor_link_prepare($str)
	- manipulates the anchor tag to update the class and onclick events needed for Highslide
	
	hs4smf_get_slidegroup($id_msg)
	- simply returns the slideshow group name for a given setup
	
	hs4smf_track_slidegroup($id_msg)
	- keeps track of the total number of found images in each slideshow group
	- expects $settings['hs4smf_img_count'] to be updated to reflect the count of images found
	  for each area, aeva, message body and attachments.  

	hs4smf_prepare_header()
	- poops out the header with the correct css and language css files for the themes.

	hs4smf_prepare_footer()
	- creates the all important javascipt options for Highslide.  Builds the option settings
	  based on the options set in the admin area.  Will do some smart corrections to help avoid
	  users choosing conflicting options.

	hs4smf_coralize_uri()
	- open CDN project,  we encode the Highslide js and css files to point to this farm so that
	  they are distributed from a closer server than ours.  MIGHT be faster if you have a lot
	  of users from across the globe, if its just a local site, could be slower.  Who knows, saw
	  this on the web blog (need to find the link to post) and thought it would be fun

	hs4smf_str_replace_once($needle, $replace, $haystack)
	- Looks for the first occurrence of $needle in $haystack and replaces it with $replace, this is a single replace

	str_ireplace($search, $replace, $subject)
	- Case insensitive str_replace function for those that don't have PHP5 installed

	stripos($haystack, $needle, $offset = 0)
	- Case insensitive stripos replacement for php4 from subs-compat
	
	aeva_initLightbox_hs4smf()
	- shortened version from aeva, used to format avea slidegroup images (signatures at this point)
	- adds styling for the avea media embed
	- adds in important javascipt functions so smg images clicks add as views
	- removes the loading of a second css and highslide.js as well as hs options so all images have 
	  the style as defined by hs4smf
*/

function hs4smf(&$message, $id_msg = -1)
{
	global $context, $modSettings, $settings;

	// Mod or BBC disabled?
	if (empty($modSettings['hs4smf_enabled']) || empty($modSettings['enableBBC'])) return;

	// init some thangs
	$settings['hs4smf_img_count'] = 0;
	$image_hosters = array('imageshack', 'photobucket', 'ipicture', 'radikal', 'keep4u', 'fotosik', 'xs', 'postimage', 'ggpht');
	$regex = '~(?P<a_tag><a href="(?P<a_link>[^"]*?)"(?:[^>]*?)>|)(?P<img_tag><img src=[""' . '\'](?P<img_src>.*?)[""' . '\'](?:[^>]*?)>)(?:</a>|)~si';

	// Do we have Aeva installed, if so lets move the [smg embedded pics to our slidegroups
	if ((!empty($modSettings['aeva_enable'])) && !isset($context['smg_disable'])) 
	{
		// How many smg images did Aeva render in this message
		$smg_count = substr_count($message, 'onclick="return hs.expand(this, slideOptions)');

		// If there are some, move them to the proper slide group, leave the aeva group for sig images
		if ($smg_count > 0) 
		{
			if (!empty($modSettings['hs4smf_slideshowgrouping']))
				$message = str_replace('onclick="return hs.expand(this, slideOptions)', 'onclick="return hs.expand(this, {slideshowGroup:\'' . $id_msg . '\',captionEval:\'{this.thumb.highslide-caption}\'})', $message);
			else
				$message = str_replace('onclick="return hs.expand(this, slideOptions)', 'onclick="return hs.expand(this, {slideshowGroup:\'fullgroup\'})', $message);

			// keep track of how many images we are sliding in this message
			$settings['hs4smf_img_count'] = $smg_count;
			hs4smf_track_slidegroup($id_msg);
		}
	}

	// Now Find all the images in this message, be they linked or not, return a numbered and named array for use
	$settings['hs4smf_img_count'] = 0;
	if (preg_match_all($regex, $message, $images, PREG_SET_ORDER)) 
	{
		// get the slide show groupings, message based or topic based
		$slidegroup = hs4smf_get_slidegroup($id_msg);

		// As long as we have images to work on
		foreach ($images as $image) 
		{
			// not on the smileys, avatars, alt="ns" or img tags from attachments, icons etc ... primarily to find smiley since they are IN the
			// message, next is the override, the rest should not happen but .....
			if (stripos($image['img_tag'], 'class="smiley"')) continue;
			elseif (stripos($image['img_tag'], 'alt="&quot;ns&quot;"')) continue;
			elseif (stripos($image['img_tag'], 'type=avatar')) continue;
			elseif (stripos($image['img_tag'], 'class="icon"')) continue;
			elseif (stripos($image['img_tag'], $modSettings['smileys_url'])) continue;
			elseif (stripos($image['img_tag'], 'index.php?action=dlattach;')) continue;
			elseif (stripos($image['img_tag'], $settings['images_url'])) continue;

			// Non-linked images need to be wrapped in an <a href /a> for Highslide to do its magic, so lets create an anchor tag
			if (empty($image['a_tag']) && empty($image['a_link'])) 
			{
				$image['a_tag'] = '<a href="' . $image['img_src'] . '">';
				$image['a_link'] = $image['img_src'];
				$image['replacement'] = $image['a_tag'] . $image['img_tag'] . '</a>';
			} 
			else 
				$image['replacement'] = $image[0];
			

			// we have special processing needs for image hosting sites in order to find the real image to expand, so if we have a valid domain
			$domain = array();
			if (preg_match('~^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$~i', $image['a_link'])) 
			{
				$domain = parse_url($image['a_link']);
				$image['domain_url'] = empty($domain['host']) ? '' : $domain['host'];

				// See if the image is hosted at a site we support
				foreach ($image_hosters as $host) 
				{
					if (stripos($image['domain_url'], $host) !== false)
					{
						// If image[1] was not set, set it now for externally hosted sites so [img] tags not in a [url] tag will slide
						if (empty($image[1])) 
							$image[1] = $image['a_tag'];
						$image = hs4smf_fix_link($image);
						break;
					}
				}
			}

			// build the anchor tag replacement, if we created the anchor tag ourselves (none was existing), use it to search and replace, otherwise
			// use the original tag ($image[1]) as we could have altered the a_tag (image hosting site) in a normal link and need to swap that in
			if (empty($image[1]))
				$image['replacement'] = str_ireplace($image['a_tag'], hs4wsmf_anchor_link_prepare($image['a_tag'], $slidegroup), $image['replacement']);
			else 
				$image['replacement'] = str_ireplace($image[1], hs4wsmf_anchor_link_prepare($image['a_tag'], $slidegroup), $image['replacement']);

			// check the image tag, if it contains the class bbc_img resized we need to change it so smf does not try to expand it
			if (stripos($image['img_tag'], 'bbc_img resized'))
			{
				$image['img_tag'] = str_ireplace('bbc_img resized', 'bbc_img', $image['img_tag']);
				$image['replacement'] = str_ireplace($image[3], $image['img_tag'], $image['replacement']);
			}

			// swap out the old with the new
			$message = hs4smf_str_replace_once($image[0], $image['replacement'], $message);
		}
		// create / update slide show tracking
		hs4smf_track_slidegroup($id_msg);
	}
	return;
}

function hs4smf_fix_link($image) 
{
	global $context, $message;

	/* Fix thumbnails for image hosting, some code from diggers:Highslide Image Viewer V 1.6 & karlbenson:Integrate Lightbox v1.4
	
	 The href link is not to a image but back to the image host page, where the full sized is returned. since we know the thumbnail image
	 location its generally easy to build the correct link to the full-size for us to use.  Take the thumbnail image and based on the host
	 strip out the thumb indicator, for example imageshack will have
	 http://img266.imageshack.us/imgxyz/952/ltsce5.th.jpg as the thumb, the full size is therefore
	 http://img266.imageshack.us/imgxyz/952/ltsce5.jpg
	 and so on through the list 
	 http://i282.photobucket.com/albums/kk241/Eudemon_Randy/o0550040011071339949.jpg
	 http://i1025.photobucket.com/albums/y311/LOEternal/anime%20pictures/01-4.jpg
	 */
	if (stripos($image['domain_url'], 'imageshack') !== false && preg_match('~(.*?)\.(?:th\.|)(png|gif|jp(e)?g|bmp)$~is' . ($context['utf8'] ? 'u' : ''), $image[4], $out)) 
		$out = (substr($out[1], 0, -1) == '.') ? $out[1] . $out[2] : $out[1] . '.' . $out[2];
	elseif (stripos($image['domain_url'], 'photobucket') !== false && preg_match('~(.*?)/(?:th_|)([^/]*?)\.(png|gif|jp(e)?g|bmp)$~is' . ($context['utf8'] ? 'u' : ''), $image[4], $out)) 
		$out = $out[1] . '/' . $out[2] . '.' . $out[3];
	elseif (stripos($image['domain_url'], 'ipicture') !== false && preg_match('~(.*?)/(?:thumbs|)/([^/]*?)\.(png|gif|jp(e)?g|bmp)$~is' . ($context['utf8'] ? 'u' : ''), $image[4], $out)) 
		$out = $out[1] . '/' . $out[2] . '.' . $out[3];
	elseif (stripos($image['domain_url'], 'radikal') !== false && preg_match('~(.*?)/([^/]*?)t\.(png|gif|jp(e)?g|bmp)$~is' . ($context['utf8'] ? 'u' : ''), $image[4], $out)) 
		$out = $out[1] . '/' . $out[2] . '.' . $out[3];
	elseif (stripos($image['domain_url'], 'keep4u') !== false && stripos($image['domain_url'], 'keep4u') !== false && preg_match('~(.*?)/imgs/s/(.+)\.(png|gif|jp(e)?g|bmp)$~is' . ($context['utf8'] ? 'u' : ''), $image[4], $out)) 
		$out = $out[1] . '/imgs/b/' . $out[2] . '.' . $out[3];
	elseif (stripos($image['domain_url'], 'fotosik') !== false && preg_match('~(.*?)\.(?:m\.|)(png|gif|jp(e)?g|bmp)$~is' . ($context['utf8'] ? 'u' : ''), $image[4], $out)) 
	{
		if (substr($out[1], -1) == 'm') 
			$out[1] = substr($out[1], 0, strlen($out[1]) - 1);
		if (substr($out[1], -3) == 'med') 
			$out[1] = substr($out[1], 0, strlen($out[1]) - 3);
		$out = $out[1] . '.' . $out[2];
	} 
	elseif (stripos($image['domain_url'], 'xs') !== false && preg_match('~(.*?)\.(?:jpg.xs\.|)(png|gif|jp(e)?g|bmp)$~is' . ($context['utf8'] ? 'u' : ''), $image[4], $out)) 
		$out = $out[1] . '.' . $out[2];
	elseif (stripos($image['domain_url'], 'postimage') !== false) 
	{
		// postimage.org appears to set the full image name based on the user agent, different agents generate different image names ...
		$out = $image[2];
		ini_set('user_agent', $_SERVER['HTTP_USER_AGENT']);
		$page = file_get_contents($image[2]);
		if ($page !== false && preg_match('~<img src=\'(.*?\.(png|gif|jp(e)?g|bmp))\'~is', $page, $link)) $out = $link[1];
	} 
	elseif (stripos($image['domain_url'], 'ggpht') !== false && preg_match('~(.*?)/(?:s\d{3,4}|)/([^/]*?)\.(png|gif|jp(e)?g|bmp)$~is' . ($context['utf8'] ? 'u' : ''), $image[4], $out)) 
		$out = $out[1] . '/' . $out[2] . '.' . $out[3];
	else 
		return $image;

	// update the anchor link and tag with the fixed image
	$image['a_tag'] = str_replace($image['a_link'], $out, $image['a_tag']);
	$image['a_link'] = $out;
	return $image;
}

function hs4wsmf_anchor_link_prepare($str, $slidegroup) 
{
	global $settings;

	// prepare the link for highslide effect by adding in the class and onclick events
	if (preg_match('~href=[\'"][^"\']+\.(?:gif|jpe|jpg|jpeg|png)~i', $str)) 
	{
		if (stripos($str, "highslide") == false && stripos($str, "onclick") === false) 
		{
			// its an image that has not been previously marked for highslide
			$settings['hs4smf_img_count'] = $settings['hs4smf_img_count'] + 1;
			if (false !== strpos(strtolower($str), 'class=')) 
			{
				// add highslide into an existing class structure
				$temp = preg_replace('~(class=[\'"])~i', '$1 highslide ', $str);
				return str_replace('>', ' onclick="return hs.expand(this, ' . $slidegroup . ')">', $temp);
			}
			else 
			{
				// need to add both a class and onclick tags into this anchor link
				$temp = '<a ' . preg_replace('~<a([^>]*)>~si', '$1 class="highslide " onclick="return hs.expand(this, ' . $slidegroup . ')"', $str) . '>';
				return $temp;
			}
		}
	}
	// not an image so leave it alone
	return $str;
}

function hs4smf_get_slidegroup($id_msg) 
{
	global $modSettings;

	// create the slideshow groupings, message based or topic based
	if (isset($modSettings['hs4smf_slideshowgrouping'])) $slidegroup = "{ slideshowGroup: '$id_msg' }";
	else $slidegroup = "{ slideshowGroup: 'fullgroup' }";
	return $slidegroup;
}

function hs4smf_track_slidegroup($id_msg) 
{
	// sets the array key if it has not been set or updates the key if already set and the new value is greater
	global $settings, $modSettings;

	// do the slideshow groupings
	if (isset($modSettings['hs4smf_slideshowgrouping'])) 
	{
		// create smart slide groups for this message/topic, add this group to our group string for use in building the correct javascript
		if (isset($settings['hs4smf_slideshow_group'][$id_msg])) 
			$settings['hs4smf_slideshow_group'][$id_msg]+= $settings['hs4smf_img_count'];
		elseif (!isset($settings['hs4smf_slideshow_group'][$id_msg])) 
			$settings['hs4smf_slideshow_group'][$id_msg] = $settings['hs4smf_img_count'];
	} 
	else 
	{
		// just a single group of all images in this topic, turn on the overlay controls if we have more than 1 image in the topic
		if (isset($settings['hs4smf_slideshow_group']['fullgroup'])) 
			$settings['hs4smf_slideshow_group']['fullgroup']+= $settings['hs4smf_img_count'];
		elseif (!isset($settings['hs4smf_slideshow_group']['fullgroup'])) 
			$settings['hs4smf_slideshow_group']['fullgroup'] = $settings['hs4smf_img_count'];
	}
}

function hs4smf_prepare_extra_headers() 
{
	global $modSettings, $settings;
	$header = "\n";

	// dark slideshow controls ?
	if (isset($modSettings['hs4smf_slideshowcontrols']) && ($modSettings['hs4smf_slideshowcontrols'] == 3)) $header .= '<style type="text/css">.large-dark .highslide-controls, .large-dark .highslide-controls ul, .large-dark .highslide-controls a {background-image: url(' . $settings['default_theme_url'] . '/hs4smf/graphics/controlbar-black-border.gif);}</style>';

	// if the large white controls are placed in the header / caption area, and that area has been exposed, then
	// turn off the round corner background if possible so it blends into that area without bleedout
	if ((isset($modSettings['hs4smf_slideshowcontrols']) && ($modSettings['hs4smf_slideshowcontrols'] == 2))) 
	{
		$oheader = (($modSettings['hs4smf_slideshownumbers'] == 1) || ((isset($modSettings['hs4smf_headingsource']) && $modSettings['hs4smf_headingsource'] != 0) && $modSettings['hs4smf_headingposition'] == 1));
		$ocaption = (($modSettings['hs4smf_slideshownumbers'] == 2) || ((isset($modSettings['hs4smf_captionsource']) && $modSettings['hs4smf_captionsource'] != 0) && $modSettings['hs4smf_captionposition'] == 1));
		if ($oheader && in_array(($modSettings['hs4smf_slideshowcontrollocation']), array("4", "5", "6"))) 
			$header .= '<style type="text/css">.highslide-controls {background: none;}.highslide-controls ul {background: none;}</style>' . "\n";
		elseif ($ocaption && in_array(($modSettings['hs4smf_slideshowcontrollocation']), array("7", "8", "9"))) 
			$header .= '<style type="text/css">.highslide-controls {background: none;}.highslide-controls ul {background: none;}</style>' . "\n";
	}

	// frame color of the expand hs4smf_slidebackgroundcolor
	if (substr($modSettings['hs4smf_slidebackgroundcolor'], 0, 1) != '#') 
		$modSettings['hs4smf_slidebackgroundcolor'] = '#' . $modSettings['hs4smf_slidebackgroundcolor'];
	if (preg_match('/^#[a-f0-9]{6}$/i', $modSettings['hs4smf_slidebackgroundcolor'])) 
		$header .= '<style type="text/css">	.highslide-wrapper, .highslide-outline {background: ' . $modSettings['hs4smf_slidebackgroundcolor'] . ';}</style>' . "\n";
	else 
		$header .= '<style type="text/css">	.highslide-wrapper, .highslide-outline {background: #FFFFFF;}</style>' . "\n";
	return $header;
}

function hs4smf_prepare_header($force = false) 
{
	global $modSettings, $settings, $context;

	// if we are in the gallery just do what Aeva would do
	if (!$force && $context['current_action'] == 'media') return;
 
	// build the correct header for css inclusion
	$hs_css = $settings['default_theme_url'] . '/hs4smf/highslide.css';
	$hs_css_ie = $settings['default_theme_url'] . '/hs4smf/highslide-ie6.css';

	// Use the CDN if requested
	if (!empty($modSettings['hs4smf_enablecoral'])) 
	{
		$hs_css = hs4smf_coralize_uri($hs_css);
		$hs_css_ie = hs4smf_coralize_uri($hs_css_ie);
	}

	// finalize the header string for output into the HTML header
	$header = "\n" . '<link rel="stylesheet" href="' . $hs_css . '" type="text/css" media="screen" />' . "\n";
	if ($context['browser']['is_ie6']) 
		$header .= '<link rel="stylesheet" href="' . $hs_css_ie . '" type="text/css" media="screen" />' . "\n";
	$header .= hs4smf_prepare_extra_headers();
	return $header;
}

function hs4smf_prepare_footer() 
{
	global $modSettings, $settings, $context, $amSettings;

	// In the gallery, just let Aeva do its thang.
	if ($context['current_action'] == 'media') return;

	// build the footer for script inclusion
	$hs_script = $settings['default_theme_url'] . '/hs4smf/highslide.js';
	$hs_language = $settings['default_theme_url'] . '/hs4smf/language/highslide.' . $context['user']['language'] . '.js';
	$hs_graphics = $settings['default_theme_url'] . '/hs4smf/graphics/';

	// Use the CDN if requested
	if (!empty($modSettings['hs4smf_enablecoral'])) 
	{
		$hs_script = hs4smf_coralize_uri($hs_script);
		$hs_language = hs4smf_coralize_uri($hs_language);
		$hs_graphics = hs4smf_coralize_uri($hs_graphics);
	}

	// begin to build the footer, lots of busy work to due based on admin options
	$footer = "\n" . '<!-- HS-4-SMF -->' . "\n";
	$footer .= '<script type="text/javascript" src="' . $hs_script . '"></script>' . "\n";
	$footer .= '<script type="text/javascript" src="' . $hs_language . '"></script>' . "\n";
	$footer .= '<script type="text/javascript"><!-- // --><![CDATA[' . "\n";
	$footer .= 'hs.graphicsDir = \'' . $hs_graphics . '\';' . "\n";
	$footer .= (isset($modSettings['hs4smf_enablecredits']) && !empty($modSettings['hs4smf_enablecredits'])) ? 'hs.showCredits = true;' . "\n" : 'hs.showCredits = false;' . "\n";
	$footer .= (isset($modSettings['hs4smf_enablegalleryfade']) && !empty($modSettings['hs4smf_enablegalleryfade'])) ? 'hs.fadeInOut = true;' . "\n" . 'hs.transitions = [\'expand\', \'crossfade\'];' . "\n" : 'hs.fadeInOut = false;' . "\n";
	$footer .= (isset($modSettings['hs4smf_enablecenter']) && !empty($modSettings['hs4smf_enablecenter'])) ? 'hs.align = \'center\';' . "\n" : '';
	$footer .= 'hs.padToMinWidth = true;' . "\n";

	// Caption text Mode
	$footer .= hs4smf_caption_text();

	// Heading text Mode
	$footer .= hs4smf_heading_text();

	// Caption position
	$footer .= hs4smf_caption_position();

	// Heading position
	$footer .= hs4smf_heading_position();

	// Set the caption and heading width
	$footer .= hs4smf_set_width();

	// what to do on mouse actions
	$footer .= hs4smf_mouse_action();

	// heading and caption opacity
	$footer .= hs4smf_hc_opacity();

	// Overall frame style
	$footer .= hs4smf_frame_style();

	// Overall dimming opacity
	$footer .= hs4smf_dimming_opacity();

	// Set the control text style
	$footer .= hs4smf_control_wrapper();

	// Add the slideshow controls if requested
	$footer .= hs4smf_slidshow_controls();

	// Finish this script
	$footer .= "// ]]></script>\n";

	// Any remaining Aeva bits
	if (!empty($amSettings['use_lightbox'])) 
		$footer .= aeva_initLightbox_hs4smf();

	// clean up for the next pass
	unset($settings['hs4smf_slideshow']);
	unset($settings['hs4smf_slideshow_group']);

	// return this beast
	return $footer;
}

function hs4smf_caption_text() 
{
	global $modSettings, $context;
	$footer = '';

	// Caption text Mode
	if (!empty($modSettings['hs4smf_captionsource']) && !empty($modSettings['hs4smf_captionposition'])) 
	{
		switch ($modSettings['hs4smf_captionsource']) 
		{
			case 1:
				$footer = 'hs.captionEval =  \'if (this.thumb.title == "" && this.slideshowGroup == "aeva") {this.highslide-caption} else {this.thumb.title} \';' . "\n";
			break;
			case 2:
				$footer = 'hs.captionEval = \' if (this.thumb.alt == "" && this.slideshowGroup == "aeva") {this.highslide-caption} else {this.thumb.alt} \';' . "\n";
			break;
			case 3:
				$footer = 'hs.captionEval = \'if (this.a.title == "" && this.slideshowGroup == "aeva") {this.highslide-caption} else {this.a.title} \';' . "\n";
			break;
			case 4:
				$temp = (isset($context['subject'])) ? htmlspecialchars($context['subject'], ENT_QUOTES) : '';
				$footer = 'hs.captionEval = \'if (this.slideshowGroup == "aeva") {this.highslide-caption} else {"' . $temp . '"} \';' . "\n";
			default:
			break;
		}
	}
	return $footer;
}

function hs4smf_heading_text() 
{
	global $modSettings, $context;
	$footer = '';

	// Heading text Mode
	if (!empty($modSettings['hs4smf_headingsource']) && !empty($modSettings['hs4smf_headingposition'])) 
	{
		switch ($modSettings['hs4smf_headingsource']) 
		{
			case 1:
				$footer = 'hs.headingEval = \'if (this.thumb.title == "") {"&nbsp;"} else {this.thumb.title} \';' . "\n";
			break;
			case 2:
				$footer = 'hs.headingEval = \' if (this.thumb.alt == "") {"&nbsp;"} else {this.thumb.alt} \';' . "\n";
			break;
			case 3:
				$footer = 'hs.headingEval = \'if (this.a.title == "") {"&nbsp;"} else {this.a.title} \';' . "\n";
			break;
			case 4:
				$temp = (isset($context['subject'])) ? htmlspecialchars($context['subject'], ENT_QUOTES) : '';
				$footer = 'hs.headingText = \'' . $temp . "'\n";
			break;
			default:
			break;
		}
	}
	return $footer;
}

function hs4smf_caption_position() 
{
	global $modSettings;
	$footer = '';

	// Caption position
	if (!empty($modSettings['hs4smf_captionposition']) && !empty($modSettings['hs4smf_captionsource'])) 
	{
		switch ($modSettings['hs4smf_captionposition']) 
		{
			case 1:
				$footer = 'hs.captionOverlay.position = \'below\';' . "\n";
			break;
			case 2:
				$footer = 'hs.captionOverlay.position = \'bottom\';' . "\n";
			break;
			default:
			break;
		}
	}
	return $footer;
}

function hs4smf_heading_position() 
{
	global $modSettings;
	$footer = '';

	// Heading position
	if (!empty($modSettings['hs4smf_headingposition']) && !empty($modSettings['hs4smf_headingsource'])) 
	{
		switch ($modSettings['hs4smf_headingposition']) 
		{
			case 1:
				$footer = 'hs.headingOverlay.position = \'above\';' . "\n";
			break;
			case 2:
				$footer = 'hs.headingOverlay.position = \'top\';' . "\n";
			break;
			default:
			break;
		}
	}
	return $footer;
}

function hs4smf_set_width() 
{
	// Set the width of the caption overlays
	$footer = '';
	$footer = 'hs.captionOverlay.width = \'100%\';' . "\n";
	$footer .= 'hs.headingOverlay.width = \'100%\';' . "\n";
	return $footer;
}

function hs4smf_mouse_action() 
{
	global $modSettings;
	$footer = '';

	// what to do on mouse actions
	$footer = (isset($modSettings['hs4smf_sourcemouse']) && !empty($modSettings['hs4smf_sourcemouse'])) ? 'hs.captionOverlay.hideOnMouseOut = true;' . "\n" . 'hs.headingOverlay.hideOnMouseOut = true;' . "\n" : 'hs.captionOverlay.hideOnMouseOut = false;' . "\n" . 'hs.headingOverlay.hideOnMouseOut = false;' . "\n";
	return $footer;
}

function hs4smf_hc_opacity() 
{
	global $modSettings;
	$footer = '';

	// heading and caption opacity
	if (!empty($modSettings['hs4smf_sourceopacity'])) 
	{
		switch ($modSettings['hs4smf_sourceopacity']) 
		{
			case 1:
			break;
			case 2:
				$footer = 'hs.captionOverlay.opacity = 0.1;' . "\n";
				$footer = 'hs.headingOverlay.opacity = 0.1;' . "\n";
			break;
			case 3:
				$footer = 'hs.captionOverlay.opacity = 0.2;' . "\n";
				$footer = 'hs.headingOverlay.opacity = 0.2;' . "\n";
			break;
			case 4:
				$footer = 'hs.captionOverlay.opacity = 0.3;' . "\n";
				$footer = 'hs.headingOverlay.opacity = 0.3;' . "\n";
			break;
			case 5:
				$footer = 'hs.captionOverlay.opacity = 0.4;' . "\n";
				$footer = 'hs.headingOverlay.opacity = 0.4;' . "\n";
			break;
			case 6:
				$footer = 'hs.captionOverlay.opacity = 0.5;' . "\n";
				$footer = 'hs.headingOverlay.opacity = 0.5;' . "\n";
			break;
			case 7:
				$footer = 'hs.captionOverlay.opacity = 0.6;' . "\n";
				$footer = 'hs.headingOverlay.opacity = 0.6;' . "\n";
			break;
			case 8:
				$footer = 'hs.captionOverlay.opacity = 0.7;' . "\n";
				$footer = 'hs.headingOverlay.opacity = 0.7;' . "\n";
			break;
			case 9:
				$footer = 'hs.captionOverlay.opacity = 0.8;' . "\n";
				$footer = 'hs.headingOverlay.opacity = 0.8;' . "\n";
			break;
			case 10:
				$footer = 'hs.captionOverlay.opacity = 0.9;' . "\n";
				$footer = 'hs.headingOverlay.opacity = 0.9;' . "\n";
			break;
			case 11:
				$footer = 'hs.captionOverlay.opacity = 1;' . "\n";
				$footer = 'hs.headingOverlay.opacity = 1;' . "\n";
			break;
			default:
			break;
		}
	}
	return $footer;
}

function hs4smf_frame_style() 
{
	global $modSettings;
	$footer = '';

	// Style Definitions
	$footer = '';
	if (!empty($modSettings['hs4smf_appearance'])) 
	{
		switch ($modSettings['hs4smf_appearance']) 
		{
			case 1:
				$footer = 'hs.outlineType = \'rounded-white\';' . "\n";
			break;
			case 2:
				$footer = 'hs.wrapperClassName = \'wide-border\';' . "\n";
			break;
			case 3:
				$footer = 'hs.wrapperClassName = \'borderless\';' . "\n";
			break;
			case 4:
				$footer = 'hs.outlineType = \'outer-glow\';' . "\n";
			break;
			case 5:
				$footer = 'hs.outlineType = null;' . "\n";
			break;
			case 6:
				$footer = 'hs.outlineType = \'glossy-dark\';' . "\n";
			break;
			case 7:
				$footer = 'hs.wrapperClassName = \'dark borderless floating-caption\';' . "\n";
			break;
			case 99:
			break;
			default:
				$footer = 'hs.outlineType = \'rounded-white\';' . "\n";
			break;
		}
	}
	return $footer;
}

function hs4smf_dimming_opacity() 
{
	global $modSettings;
	$footer = '';

	// dimming opacity
	if (!empty($modSettings['hs4smf_dimmingopacity'])) 
	{
		switch ($modSettings['hs4smf_dimmingopacity']) 
		{
			case 1:
			break;
			case 2:
				$footer = 'hs.dimmingOpacity = 0.1;' . "\n";
			break;
			case 3:
				$footer = 'hs.dimmingOpacity = 0.2;' . "\n";
			break;
			case 4:
				$footer = 'hs.dimmingOpacity = 0.3;' . "\n";
			break;
			case 5:
				$footer = 'hs.dimmingOpacity = 0.4;' . "\n";
			break;
			case 6:
				$footer = 'hs.dimmingOpacity = 0.5;' . "\n";
			break;
			case 7:
				$footer = 'hs.dimmingOpacity = 0.6;' . "\n";
			break;
			case 8:
				$footer = 'hs.dimmingOpacity = 0.7;' . "\n";
			break;
			case 9:
				$footer = 'hs.dimmingOpacity = 0.8;' . "\n";
			break;
			case 10:
				$footer = 'hs.dimmingOpacity = 0.9;' . "\n";
			break;
			case 11:
				$footer = 'hs.dimmingOpacity = 1;' . "\n";
			break;
			default:
			break;
		}
	}
	return $footer;
}

function hs4smf_control_wrapper() 
{
	global $modSettings;
	$footer = '';

	// set the slidshow control wrapper to use the correct controls
	if (isset($modSettings['hs4smf_slideshowcontrols'])) 
	{
		switch ($modSettings['hs4smf_slideshowcontrols']) 
		{
			case 0:
				$footer = "hs.wrapperClassName = 'text-controls';" . "\n";
			break;
			case 1:
				$footer = "hs.wrapperClassName = 'controls-in-heading';" . "\n";
			break;
			case 3:
				$footer = "hs.wrapperClassName = 'large-dark';" . "\n";
			break;
			default:
			break;
		}
	}
	return $footer;
}

function hs4smf_slidshow_controls() 
{
	global $modSettings, $settings, $txt;
	$footer = '';
	if (!empty($modSettings['hs4smf_endableslideshow'])) 
	{
		// do we have an open header bar or footer bar?
		$oheader = (($modSettings['hs4smf_slideshownumbers'] == 1) || (!empty($modSettings['hs4smf_headingsource']) && $modSettings['hs4smf_headingposition'] == 1));
		$ocaption = (($modSettings['hs4smf_slideshownumbers'] == 2) || (!empty($modSettings['hs4smf_captionsource']) && $modSettings['hs4smf_captionposition'] == 1));

		// show image x of y for all the slideshows?
		if (isset($modSettings['hs4smf_slideshownumbers']) && !empty($modSettings['hs4smf_slideshownumbers'])) 
		{
			$footer .= "hs.lang.number = '" . $txt['hs4smf_image'] . " %1 - %2';\n";
			
			switch ($modSettings['hs4smf_slideshownumbers']) 
			{
				case 1:
					$footer .= "hs.numberPosition = 'heading';\n";
				break;
				case 2:
					$footer .= "hs.numberPosition = 'caption';\n";
				break;
				default:
				break;
			}
		}

		// create slidshow groups for each message (that has images) in the post ....
		if (isset($settings['hs4smf_slideshow_group'])) 
		{
			foreach ($settings['hs4smf_slideshow_group'] as $slide_group => $slide_count) 
			{
				// start of this slide group
				$footer .= "if (hs.addSlideshow) hs.addSlideshow({";
				$footer .= "slideshowGroup: '$slide_group',";

				// how long to show each image
				$interval = (!empty($modSettings['hs4smf_slideshowdelay'])) ? intval($modSettings['hs4smf_slideshowdelay'] * 1000) : 5000;
				if ($interval < 1000) $interval = 5000;
				$footer .= "interval: " . $interval . ",";
				$footer .= (!empty($modSettings['hs4smf_slideshowrepeat'])) ? "repeat: true," : "repeat: false,";

				// if this group only has 1 image then don't show the controls unless we have been told to
				$footer .= ($slide_count > 1 || (isset($modSettings['hs4smf_slidecontrolsalways']) && !empty($modSettings['hs4smf_slidecontrolsalways']))) ? "useControls: true," : "useControls: false,";
				$footer .= "fixedControls: 'fit',";

				// how should the overlay controls appear
				$footer .= "overlayOptions: {";
				$footer .= "opacity: .80,";
				$X = 0;
				$Y = 0;

				// place the controls, try to avoid collisions with the header and caption locations
				
				switch ($modSettings['hs4smf_slideshowcontrollocation']) 
				{
					case 1:
						$footer .= "position: 'above',";
					break;
					case 4:
						$footer .= "position: 'top left',";

						// if we are using the graphical control and the header is exposed then place it in there
						// otherwise place it based on control size and type
						if ($oheader) 
						{
							if (isset($modSettings['hs4smf_slideshowcontrols']) && $modSettings['hs4smf_slideshowcontrols'] == 1) {
								$X = - 5;
								$Y = 25;
							} elseif (isset($modSettings['hs4smf_slideshowcontrols']) && $modSettings['hs4smf_slideshowcontrols'] == 2) {
								$X = - 12;
								$Y = - 22;
							} elseif (isset($modSettings['hs4smf_slideshowcontrols']) && $modSettings['hs4smf_slideshowcontrols'] == 3) {
								$X = 5;
								$Y = - 12;
							}
						}
						elseif (isset($modSettings['hs4smf_slideshowcontrols'])) 
						{
							switch ($modSettings['hs4smf_slideshowcontrols']) 
							{
								case 1:
									$Y = 25;
								break;
								case 2:
									$Y = - 15;
									$X = 5;
								break;
								case 3:
									$Y = - 15;
									$X = 5;
								break;
								case 0:
									$Y = 5;
								break;
								default:
								break;
							}
						}
					break;
					case 5:
						$footer .= "position: 'top center',";

						// if we are using the graphical control and the header is exposed then place it in there
						// otherwise place it based on control size and type
						if ($oheader) 
						{
							if (isset($modSettings['hs4smf_slideshowcontrols']) && $modSettings['hs4smf_slideshowcontrols'] == 2) 
								$Y = - 55;
							elseif (isset($modSettings['hs4smf_slideshowcontrols']) && $modSettings['hs4smf_slideshowcontrols'] == 0) 
								$Y = - 25;
						} 
						elseif (isset($modSettings['hs4smf_slideshowcontrols'])) 
						{
							switch ($modSettings['hs4smf_slideshowcontrols']) 
							{
								case 1:
									$Y = 25;
								break;
								case 2:
									$Y = - 15;
								break;
								case 3:
									$Y = - 15;
								break;
								case 0:
									$Y = 5;
								break;
								default:
								break;
							}
						}
					break;
					case 6:
						$footer .= "position: 'top right',";

						// if we are using the graphical control and the header is exposed then place it in there
						// otherwise place it based on control size and type
						if ($oheader) 
						{
							if (isset($modSettings['hs4smf_slideshowcontrols']) && $modSettings['hs4smf_slideshowcontrols'] == 2) {
								$Y = - 55;
								$X = 15;
							} 
							elseif (isset($modSettings['hs4smf_slideshowcontrols']) && $modSettings['hs4smf_slideshowcontrols'] == 0) 
								$Y = - 25;
						}
						elseif (isset($modSettings['hs4smf_slideshowcontrols'])) 
						{	
							switch ($modSettings['hs4smf_slideshowcontrols']) 
							{
								case 1:
									$Y = 25;
									$X = - 5;
								break;
								case 2:
									$Y = - 5;
									$X = - 5;
								break;
								case 3:
									$Y = - 5;
									$X = - 5;
								break;
								case 0:
									$Y = 5;
								break;
								default:
								break;
							}
						}
					break;
					case 7:
						$footer .= "position: 'bottom left',";

						// if we are using the graphical control then shift it if is not compatible with the header/caption position
						if ($ocaption) 
						{
							if (isset($modSettings['hs4smf_slideshowcontrols']) && $modSettings['hs4smf_slideshowcontrols'] == 2) 
							{
								$X = - 10;
								$Y = 12;
							} 
							elseif (isset($modSettings['hs4smf_slideshowcontrols']) && $modSettings['hs4smf_slideshowcontrols'] == 1) 
							{
								$X = - 5;
								$Y = 20;
							}
						} 
						elseif (isset($modSettings['hs4smf_slideshowcontrols'])) 
						{
							switch ($modSettings['hs4smf_slideshowcontrols']) 
							{
								case 3:
									$X = 5;
									$Y = 5;
								break;
								case 2:
									$X = 5;
									$Y = 5;
								break;
								case 1:
									$Y = 15;
								break;
								case 0:
									$X = - 2;
									$Y = - 5;
								break;
								default:
								break;
							}
						}
					break;
					case 8:
						$footer .= "position: 'bottom center',";

						// if we are using the graphical control then shift it if is not compatible with the header/caption position
						if ($ocaption) 
						{
							if (isset($modSettings['hs4smf_slideshowcontrols']) && $modSettings['hs4smf_slideshowcontrols'] == 2) $Y = 45;
							elseif (isset($modSettings['hs4smf_slideshowcontrols']) && $modSettings['hs4smf_slideshowcontrols'] == 3) $Y = 47;
							elseif (isset($modSettings['hs4smf_slideshowcontrols']) && $modSettings['hs4smf_slideshowcontrols'] == 1) $Y = 50;
							else $Y = 27;
						} 
						elseif (isset($modSettings['hs4smf_slideshowcontrols'])) 
						{
							switch ($modSettings['hs4smf_slideshowcontrols']) 
							{
								case 3:
									$Y = 5;
								break;
								case 2:
									$Y = 5;
								break;
								case 1:
									$Y = 20;
								break;
								case 0:
									$Y = - 5;
								break;
								default:
								break;
							}
						}
					break;
					case 9:
						$footer .= "position: 'bottom right',";

						// if we are using the graphical control then shift it if is not compatible with the header/caption position
						if ($ocaption) 
						{
							if (isset($modSettings['hs4smf_slideshowcontrols']) && $modSettings['hs4smf_slideshowcontrols'] == 3) 
							{
								$Y = 47;
								$X = 10;
							} 
							elseif (isset($modSettings['hs4smf_slideshowcontrols']) && $modSettings['hs4smf_slideshowcontrols'] == 2) 
							{
								$Y = 47;
								$X = 10;
							} elseif (isset($modSettings['hs4smf_slideshowcontrols']) && $modSettings['hs4smf_slideshowcontrols'] == 1) 
							{
								$Y = 50;
								$X = - 5;
							} elseif (isset($modSettings['hs4smf_slideshowcontrols']) && $modSettings['hs4smf_slideshowcontrols'] == 0) 
							{
								$Y = 27;
								$X = - 5;
							}
						} 
						elseif (isset($modSettings['hs4smf_slideshowcontrols'])) 
						{
							switch ($modSettings['hs4smf_slideshowcontrols']) 
							{
								case 3:
									$Y = 5;
									$X = - 5;
								break;
								case 2:
									$Y = 5;
									$X = - 5;
								break;
								case 1:
									$Y = 20;
									$X = - 5;
								break;
								case 0:
									$Y = - 5;
								break;
								default:
								break;
							}
						}
					break;
					case 10:
						$footer .= "position: 'below',";
					break;
					default:
					break;
				}
				if (isset($modSettings['hs4smf_nudgex'])) 
					$X+= $modSettings['hs4smf_nudgex'];
				if (isset($modSettings['hs4smf_nudgey'])) 
					$Y+= $modSettings['hs4smf_nudgey'];
				$footer .= 'offsetX: ' . $X . ',offsetY: ' . $Y . ',';

				// last line, no trailing , or ie7- will toss an error for no good reason
				$footer .= (isset($modSettings['hs4smf_slideshowmouse']) && !empty($modSettings['hs4smf_slideshowmouse'])) ? 'hideOnMouseOut: true' : 'hideOnMouseOut: false';
				$footer .= "}";
				$footer .= "});\n";
			}
		}
	}
	return $footer;
}
	
function hs4smf_coralize_uri($uri) 
{
	// simply adds on the .nyud.net to a link so it points to the open CDN, usally does not work :P
	if (stristr($uri, "http://") === false) 
		return $uri;
	$tmp = explode("/", $uri, 4);
	$cor = $tmp[0] . '/' . $tmp[1] . '/' . $tmp[2] . '.nyud.net/' . $tmp[3];
	return $cor;
}

function hs4smf_str_replace_once($needle, $replace, $haystack) 
{
	// Looks for the first occurrence of $needle in $haystack and replaces it with $replace, this is a single replace
	$pos = strpos($haystack, $needle);
	if ($pos === false) 
	{
		// Nothing found
		return $haystack;
	}
	return substr_replace($haystack, $replace, $pos, strlen($needle));
}

function aeva_initLightbox_hs4smf() 
{
	global $modSettings;
	// Pretty much just a cut down aeva_initLightbox to prevent double loading highslide and HS config options
	// So that we just use hs4smf options in the forum.  Not used when in the gallery

	$r = '
<script type="text/javascript"><!-- // --><![CDATA[
function hss(aId, aSelf)
{
	var aUrl = aSelf.href;
	var ah = document.getElementById(\'hsm\' + aId);
	hs.close(ah);
	hs.expanders[hs.getWrapperKey(ah)] = null;
	ah.href = aUrl;
	hs.expand(ah);
	return false;
}

hs.Expander.prototype.onInit = function()
{
	for (var i = 0, j = this.a.attributes, k = j.length; i < k; i++)
	{
		if (j[i].value.indexOf(\'htmlExpand\') != -1)
		{
			getXMLDocument(\'index.php?action=media;sa=addview;in=\' + this.a.id.substr(3), function() {});
			return;
		}
	}
}';

// Uncomment this block if you want Aeva to count highslide actions as gallery views
//$r .= '
//hs.Expander.prototype.onAfterExpand = function () 
//{
//	// This will tickle the avea viewed count when and smg item is expanded in a post
//	var view_pattern = new RegExp(";in=([0-9]{1,10}).?","g");
//	var match = view_pattern.exec(this.a.href);
//	if (match != null) {
//		getXMLDocument(\'index.php?action=media;sa=addview;in=\' + match[1], function() {});
//	}
//
//	return;
//}';

	$r .= '
var slideOptions = { slideshowGroup: \'aeva\'' . 
((!empty($modSettings['hs4smf_enablecenter'])) ? ', align: \'center\',' : ',') . ' useControls: false' . 
((!empty($modSettings['hs4smf_enablegalleryfade'])) ? ', fadeInOut: true, transitions: [\'expand\', \'crossfade\']' : ', fadeInOut: false') . 
' }; 
var mediaOptions = { slideshowGroup: \'aeva\'' . 
((!empty($modSettings['hs4smf_enablecenter'])) ? ', align: \'center\',' : ',') . 
((!empty($modSettings['hs4smf_enablegalleryfade'])) ? ' fadeInOut: true, transitions: [\'expand\', \'crossfade\']' : ' fadeInOut: false') . 
', width: 1 };	
// ]]>
</script>';

	return $r;
}

function aeva_initGallery_hs4smf(&$lightbox)
{
	global $settings, $modSettings, $sourcedir, $context;

	include_once($sourcedir . '/Aeva-Subs.php');
	$settings['hs4smf_slideshow_group']['aeva'] = 2;
	
	// slideshow grouping in messages, remove aeva's and add in hs4smf's
	if ($context['current_action'] == 'media' && !empty($modSettings['hs4smf_enabled']))
		$lightbox = preg_replace('~if \(hs\.addSlideshow\) hs\.addSlideshow\(\{.*\}\);~sU', '', $lightbox);

	// Add the things that aeva does not normally use, 
	$lightbox .= '
	<script type="text/javascript"><!-- // --><![CDATA[
	';
	$lightbox .= (!empty($modSettings['hs4smf_enablecredits'])) ? 'hs.showCredits = true;' . "\n" : 'hs.showCredits = false;' . "\n";
	$lightbox .= (!empty($modSettings['hs4smf_enablegalleryfade'])) ? '	hs.fadeInOut = true;' . "\n" . '	hs.transitions = [\'expand\', \'crossfade\'];' . "\n" : 'hs.fadeInOut = false;' . "\n";
	$lightbox .= (!empty($modSettings['hs4smf_enablecenter'])) ? '	hs.align = \'center\';' . "\n" : '';
	$lightbox .= '	' . hs4smf_caption_text();
	$lightbox .= '	' . hs4smf_heading_text();
	$lightbox .= hs4smf_caption_position();
	$lightbox .= hs4smf_heading_position();
	$lightbox .= '	' . hs4smf_set_width();
	$lightbox .= '	' . hs4smf_mouse_action();
	$lightbox .= '	' . hs4smf_hc_opacity();
	$lightbox .= '	' . hs4smf_dimming_opacity();
	$lightbox .= '	' . hs4smf_control_wrapper();
	$lightbox .= '	' . hs4smf_slidshow_controls();
	$lightbox .= '	// ]]></script>';

	// change the ones that it does
	$lightbox = str_replace("hs.outlineType = 'rounded-white';", hs4smf_frame_style(), $lightbox);
	$lightbox = str_replace('<link rel="stylesheet" type="text/css" href="' . aeva_theme_url('hs.css') . '" media="screen" />', hs4smf_prepare_header(true), $lightbox);
	$lightbox = str_replace('hs.graphicsDir = \'' . aeva_theme_url('hs/') . '\';', 'hs.graphicsDir = \'' . $settings['default_theme_url'] . '/hs4smf/graphics/\';', $lightbox);
	
	return;
}

if (!function_exists('stripos')) 
{
	// case insensitive stripos replacement for php4 from subs-compat
	function stripos($haystack, $needle, $offset = 0) 
	{
		return strpos(strtolower($haystack), strtolower($needle), $offset);
	}
}

if (!function_exists('str_ireplace')) 
{
	// case insensitive str_ireplace function for those that don't have PHP5 installed, from php.net
	function str_ireplace($search, $replace, $subject) 
	{
		global $context;
		$endu = '~i' . ($context['utf8'] ? 'u' : '');
		if (is_array($search)) 
			foreach (array_keys($search) as $word) 
				$search[$word] = '~' . preg_quote($search[$word], '~') . $endu;
		else 
			$search = '~' . preg_quote($search, '~') . $endu;
		return preg_replace($search, $replace, $subject);
	}
}

// These 3 functions are our integration hooks, they simply add the info to the admin panel for use
function iaa_hs4smf(&$admin_areas)
{
	global $txt;
	$admin_areas['config']['areas']['modsettings']['subsections']['hs4smf'] = array($txt['mods_cat_modifications_hs4smf']);
}

function imm_hs4smf(&$sub_actions)
{
	$sub_actions['hs4smf'] = 'Modifyhs4smfsettings';
}

function Modifyhs4smfsettings($return_config = false)
{
	global $txt, $scripturl, $context, $settings, $sc, $modSettings;
	
	$context[$context['admin_menu_name']]['tab_data']['tabs']['hs4smf']['description'] = $txt['hs4smf_desc'];

	$config_vars = array(
		array('check', 'hs4smf_enabled'),
		'',
		array('check', 'hs4smf_enablecoral'),
		array('check', 'hs4smf_enablecredits'),
		array('check', 'hs4smf_enableonattachments'),
		array('check', 'hs4smf_aeva_format'),
		array('check', 'hs4smf_enablecenter'),
		array('check', 'hs4smf_enablegalleryfade'),
		array('check', 'hs4smf_sourcemouse'),
		'',
		array('check', 'hs4smf_endableslideshow'),
		array('check', 'hs4smf_slideshowmouse'),
		array('int',   'hs4smf_slideshowdelay'),
		array('check', 'hs4smf_slideshowrepeat'),
		array('check', 'hs4smf_slidecontrolsalways'),
		array('select', 'hs4smf_slideshowcontrols', array(
			'0' => $txt['hs4smf_text'],
			'1' => $txt['hs4smf_smallw'],
			'2' => $txt['hs4smf_largew'],
			'3' => $txt['hs4smf_largeb'],
		)),
		array('check', 'hs4smf_slideshowgrouping'),
		array('select','hs4smf_slideshownumbers', array(
			'0' => $txt['hs4smf_none'],
			'1' => $txt['hs4smf_inheading'],
			'2' => $txt['hs4smf_incaption'],
		)),
		array('select','hs4smf_slideshowcontrollocation', array(
			'1' => $txt['hs4smf_above'],
			'4' => $txt['hs4smf_top'] . ' ' . $txt['hs4smf_left'],
			'5' => $txt['hs4smf_top'] . ' ' . $txt['hs4smf_center'],
			'6' => $txt['hs4smf_top']  . ' ' . $txt['hs4smf_right'],
			'7' => $txt['hs4smf_bottom'] . ' ' . $txt['hs4smf_left'],
			'8' => $txt['hs4smf_bottom'] . ' ' . $txt['hs4smf_center'],
			'9' => $txt['hs4smf_bottom'] . ' ' . $txt['hs4smf_right'],
			'10' => $txt['hs4smf_below'],
		)),
		array ('int', 'hs4smf_nudgex'),
		array ('int', 'hs4smf_nudgey'),
		'',
		array('select','hs4smf_appearance', array(
			'1' => $txt['hs4smf_hrw'],
			'2' => $txt['hs4smf_pos'],
			'3' => $txt['hs4smf_borderless'],
			'4' => $txt['hs4smf_dog'],
			'5' => $txt['hs4smf_pas'],
			'6' => $txt['hs4smf_dag'],
			'7' => $txt['hs4smf_dbf'])),
		array('text',   'hs4smf_slidebackgroundcolor'),
		array('select', 'hs4smf_dimmingopacity', array(
			'1' => $txt['hs4smf_00'] . ', ' . $txt['hs4smf_nodimming'],
			'2' => $txt['hs4smf_10'],
			'3' => $txt['hs4smf_20'],
			'4' => $txt['hs4smf_30'],
			'5' => $txt['hs4smf_40'],
			'6' => $txt['hs4smf_50'],
			'7' => $txt['hs4smf_60'],
			'8' => $txt['hs4smf_70'],
			'9' => $txt['hs4smf_80'],
			'10' => $txt['hs4smf_90'],
			'11' => $txt['hs4smf_100']. ', ' . $txt['hs4smf_fullblack'],
		)),
		'',
		array('select', 'hs4smf_headingsource', array(
			'0' => $txt['hs4smf_none'],
			'1' => $txt['hs4smf_imagetitle'],
			'2' => $txt['hs4smf_imagealt'],
			'3' => $txt['hs4smf_linktitle'],
			'4' => $txt['hs4smf_postsubject']
		)),
		array('select', 'hs4smf_headingposition', array(
			'0' => $txt['hs4smf_none'],
			'1' => $txt['hs4smf_above'] . ' ' . $txt['hs4smf_image'],
			'2' => $txt['hs4smf_overlayedon'] . ' ' . $txt['hs4smf_top'],
		)),
		'',
		array('select', 'hs4smf_captionsource', array(
			'0' => $txt['hs4smf_none'],
			'1' => $txt['hs4smf_imagetitle'],
			'2' => $txt['hs4smf_imagealt'],
			'3' => $txt['hs4smf_linktitle'],
			'4' => $txt['hs4smf_postsubject']
		)),
		array('select', 'hs4smf_captionposition', array(
			'0' => $txt['hs4smf_none'],
			'1' => $txt['hs4smf_below'] . ' ' . $txt['hs4smf_image'],
			'2' => $txt['hs4smf_overlayedon'] . ' ' . $txt['hs4smf_bottom'],
		)),
		'',	
		array('select', 'hs4smf_sourceopacity', array(
			'1' => $txt['hs4smf_00'] . ', ' . $txt['hs4smf_nodimming'],
			'2' => $txt['hs4smf_10'],
			'3' => $txt['hs4smf_20'],
			'4' => $txt['hs4smf_30'],
			'5' => $txt['hs4smf_40'],
			'6' => $txt['hs4smf_50'],
			'7' => $txt['hs4smf_60'],
			'8' => $txt['hs4smf_70'],
			'9' => $txt['hs4smf_80'],
			'10' => $txt['hs4smf_90'],
			'11' => $txt['hs4smf_100']. ', ' . $txt['hs4smf_fullblack'],
		)),
	);

	if ($return_config)
		return $config_vars;

	$context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=hs4smf';
	$context['settings_title'] = $txt['mods_cat_modifications_hs4smf'];

	if (isset($_GET['save']))
	{
		checkSession();
		saveDBSettings($config_vars);
		redirectexit('action=admin;area=modsettings;sa=hs4smf');
	}
	prepareDBSettingContext($config_vars);
}
?>