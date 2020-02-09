// Hack's Park Color Picker: www.hackspark.com
// File Version: 6

var colorPicker = new Array();
colorPicker['bg'] = false;
colorPicker['bg_color'] = '';
colorPicker['color'] = '';

function ColorPicker_ShowHide(c)
{
	if (document.getElementById("colorpicker").style.display == 'none')
		document.getElementById("colorpicker").style.display = '';
	else
		document.getElementById("colorpicker").style.display = 'none';

	// SMF 2.0.x
	if (!document.getElementById("cmd_color"))
		return;

	if (typeof(c) != "undefined")
		colorPicker['bg'] = c;

	if (colorPicker['bg'])
	{
		document.getElementById("cmd_bgcolor").style.display = '';
		document.getElementById("cmd_color").style.display = 'none';
		document.getElementById("colorpicker").style.right = '29px';
	}
	else
	{
		document.getElementById("cmd_color").style.display = '';
		document.getElementById("cmd_bgcolor").style.display = 'none';
		document.getElementById("colorpicker").style.right = '82px';
	}
}

colorPicker['base_hexa'] = "0123456789ABCDEF";
function ColorPicker_dec2Hexa(n)
{
	return colorPicker['base_hexa'].charAt(Math.floor(n / 16)) + colorPicker['base_hexa'].charAt(n % 16);
}

function ColorPicker_ToHexa(TR,TG,TB)
{
	return '#' + ColorPicker_dec2Hexa(TR) + ColorPicker_dec2Hexa(TG) + ColorPicker_dec2Hexa(TB);
}

function ColorPicker_Sample(c)
{
	document.getElementById("colorpicker_sample").style.backgroundColor = c;
}

function ColorPicker_Select(c)
{
	document.getElementById("colorpicker_select").style.backgroundColor = c;
	document.getElementById("colorpicker_hexa").value = c;
}

function ColorPicker_ColorBox()
{
	// colors to white
	var hashColor = 255;
	var hashDiv = 0;
	var color;

	for(var th = 1; th < 16; th++)
	{
		hashDiv = (255 - hashColor) / 5;
		for(var i = 1, c = 0; i < 6; i++)
		{
			color = ColorPicker_ToHexa(255, hashColor + c, hashColor);
			document.write('<img border="0" src="' + smf_images_url + '/blank.gif" onclick="ColorPicker_Select(\'' + color + '\')" onmouseover="ColorPicker_Sample(\'' + color + '\')" style="background-color:' + color + ';cursor:crosshair" width="5" height="5" alt="" />');
			c += hashDiv;
		}
		for(var i = 1, c = 0; i < 6; i++)
		{
			color = ColorPicker_ToHexa(255 - c, 255, hashColor);
			document.write('<img border="0" src="' + smf_images_url + '/blank.gif" onclick="ColorPicker_Select(\'' + color + '\')" onmouseover="ColorPicker_Sample(\'' + color + '\')" style="background-color:' + color + ';cursor:crosshair" width="5" height="5" alt="" />');
			c += hashDiv;
		}
		for(var i = 1, c = 0; i < 6; i++)
		{
			color = ColorPicker_ToHexa(hashColor, 255, hashColor + c);
			document.write('<img border="0" src="' + smf_images_url + '/blank.gif" onclick="ColorPicker_Select(\'' + color + '\')" onmouseover="ColorPicker_Sample(\'' + color + '\')" style="background-color:' + color + ';cursor:crosshair" width="5" height="5" alt="" />');
			c += hashDiv;
		}
		for(var i = 1, c = 0; i < 6; i++)
		{
			color = ColorPicker_ToHexa(hashColor, 255 - c, 255);
			document.write('<img border="0" src="' + smf_images_url + '/blank.gif" onclick="ColorPicker_Select(\'' + color + '\')" onmouseover="ColorPicker_Sample(\'' + color + '\')" style="background-color:' + color + ';cursor:crosshair" width="5" height="5" alt="" />');
			c += hashDiv;
		}
		for(var i = 1, c = 0; i < 6; i++)
		{
			color = ColorPicker_ToHexa(hashColor + c, hashColor, 255);
			document.write('<img border="0" src="' + smf_images_url + '/blank.gif" onclick="ColorPicker_Select(\'' + color + '\')" onmouseover="ColorPicker_Sample(\'' + color + '\')" style="background-color:' + color + ';cursor:crosshair" width="5" height="5" alt="" />');
			c += hashDiv;
		}
		for(var i = 1, c = 0; i < 6; i++)
		{
			color = ColorPicker_ToHexa(255, hashColor, 255 - c);
			document.write('<img border="0" src="' + smf_images_url + '/blank.gif" onclick="ColorPicker_Select(\'' + color + '\')" onmouseover="ColorPicker_Sample(\'' + color + '\')" style="background-color:' + color + ';cursor:crosshair" width="5" height="5" alt="" />');
			c += hashDiv;
		}
		hashColor -= 17;
		document.write('<br />');
	}

	// colors to black
	hashColor = 255;
	hashDiv = 0;

	for(var th = 1; th < 16; th++)
	{
		hashDiv = hashColor / 5;
		for(var i = 1, c = 0; i < 6; i++)
		{
			var color = ColorPicker_ToHexa(hashColor, 0 + c, 0);
			document.write('<img border="0" src="' + smf_images_url + '/blank.gif" onclick="ColorPicker_Select(\'' + color + '\')" onmouseover="ColorPicker_Sample(\'' + color + '\')" style="background-color:' + color + ';cursor:crosshair" width="5" height="5" alt="" />');
			c += hashDiv;
		}
		for(var i = 1, c = 0; i < 6; i++)
		{
			var color = ColorPicker_ToHexa(hashColor - c,hashColor,0);
			document.write('<img border="0" src="' + smf_images_url + '/blank.gif" onclick="ColorPicker_Select(\'' + color + '\')" onmouseover="ColorPicker_Sample(\'' + color + '\')" style="background-color:' + color + ';cursor:crosshair" width="5" height="5" alt="" />');
			c += hashDiv;
		}
		for(var i = 1, c = 0; i < 6; i++)
		{
			var color = ColorPicker_ToHexa(0, hashColor, 0 + c);
			document.write('<img border="0" src="' + smf_images_url + '/blank.gif" onclick="ColorPicker_Select(\'' + color + '\')" onmouseover="ColorPicker_Sample(\'' + color + '\')" style="background-color:' + color + ';cursor:crosshair" width="5" height="5" alt="" />');
			c += hashDiv;
		}
		for(var i = 1, c = 0; i < 6; i++)
		{
			var color = ColorPicker_ToHexa(0, hashColor - c, hashColor);
			document.write('<img border="0" src="' + smf_images_url + '/blank.gif" onclick="ColorPicker_Select(\'' + color + '\')" onmouseover="ColorPicker_Sample(\'' + color + '\')" style="background-color:' + color + ';cursor:crosshair" width="5" height="5" alt="" />');
			c += hashDiv;
		}
		for(var i = 1, c = 0; i < 6; i++)
		{
			var color = ColorPicker_ToHexa(0 + c, 0, hashColor);
			document.write('<img border="0" src="' + smf_images_url + '/blank.gif" onclick="ColorPicker_Select(\'' + color + '\')" onmouseover="ColorPicker_Sample(\'' + color + '\')" style="background-color:' + color + ';cursor:crosshair" width="5" height="5" alt="" />');
			c += hashDiv;
		}
		for(var i = 1, c = 0; i < 6; i++)
		{
			var color = ColorPicker_ToHexa(hashColor, 0, hashColor - c);
			document.write('<img border="0" src="' + smf_images_url + '/blank.gif" onclick="ColorPicker_Select(\'' + color + '\')" onmouseover="ColorPicker_Sample(\'' + color + '\')" style="background-color:' + color + ';cursor:crosshair" width="5" height="5" alt="" />');
			c += hashDiv;
		}
		hashColor -= 17;
		document.write('<br />');
	}
}

function ColorPicker_BoxGrayScale()
{
	var hashColor = 255;
	var hashDiv = hashColor / 17;
	var color;

	for(var i = 1, c = 0; i < 19; i++)
	{
		color = ColorPicker_ToHexa(hashColor - c, hashColor - c, hashColor - c);
		document.write('<img border="0" src="' + smf_images_url + '/blank.gif" onclick="ColorPicker_Select(\'' + color + '\')" onmouseover="ColorPicker_Sample(\'' + color + '\')" style="background-color:' + color + ';cursor:crosshair" width="5" height="8" alt="" />');
		c += hashDiv;
	}
}