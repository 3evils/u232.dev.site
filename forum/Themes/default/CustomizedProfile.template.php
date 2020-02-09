<?php
/********************************************************
* Customized profile
* Version: 1.1 
* Official support: SmfPersonal
* Founder: ^HeRaCLeS^
* Date: 2010
/**********************************************************/

function template_Customizedprofile_settings()
{
	global $scripturl, $context, $settings, $txt, $modSettings;
	
 $Seleccion = $modSettings['Customizedprofile'];

echo '
<form method="post" action="'. $scripturl .'?action=admin;area=CustomizedProfile;sa=save">
 <div class="cat_bar">
  <h3 class="catbg">
   '.$txt['CP_title_nav'].'
  </h3>
 </div>
 <div class="windowbg">
  <span class="topslice"><span></span></span>
	 <div class="content">
	  <table style="width: 100%;">
     <tr>
      <td>'.$txt['CP_enable'].'</td>
      <td><input type="checkbox" name="CP_enable"',!empty($modSettings['CP_enable']) ? ' checked="checked"' : '' ,' /></td>
     </tr>
     <tr>
      <td>'.$txt['CP-mp_enable'].'</td>
      <td><input type="checkbox" name="CP-mp_enable"',!empty($modSettings['CP-mp_enable']) ? ' checked="checked"' : '' ,' /></td>
     </tr>
     <tr>
      <td>'.$txt['CP_color1'].'</td>
      <td><input type = "radio" name = "color" value = "Amarillo" ', $Seleccion == 'Amarillo' ? 'checked="checked"' : '', '/></td>
     </tr>
     <tr>
      <td>'.$txt['CP_color2'].'</td>
      <td><input type = "radio" name = "color" value = "Azul" ', $Seleccion == 'Azul' ? 'checked="checked"' : '', '/></td>
     </tr>
     <tr>
      <td>'.$txt['CP_color3'].'</td>
      <td><input type = "radio" name = "color" value = "Gris" ', $Seleccion == 'Gris' ? 'checked="checked"' : '', '/></td>
     </tr>
     <tr>
      <td>'.$txt['CP_color4'].'</td>
      <td><input type = "radio" name = "color" value = "Marron" ', $Seleccion == 'Marron' ? 'checked="checked"' : '', '/></td>
     </tr>
     <tr>
      <td>'.$txt['CP_color5'].'</td>
      <td><input type = "radio" name = "color" value = "Naranja" ', $Seleccion == 'Naranja' ? 'checked="checked"' : '', '/></td>
     </tr>
     <tr>
      <td>'.$txt['CP_color6'].'</td>
      <td><input type = "radio" name = "color" value = "Rojo" ', $Seleccion == 'Rojo' ? 'checked="checked"' : '', '/></td>
     </tr>
     <tr>
      <td>'.$txt['CP_color7'].'</td>
      <td><input type = "radio" name = "color" value = "Teal" ', $Seleccion == 'Teal' ? 'checked="checked"' : '', '/></td>
     </tr>
     <tr>
      <td>'.$txt['CP_color8'].'</td>
      <td><input type = "radio" name = "color" value = "Violeta" ', $Seleccion == 'Violeta' ? 'checked="checked"' : '', '/></td>
     </tr>
     <tr>
      <td></td>
      <td><input type="submit" name="enviar" value="'.$txt['CP_save'].'" class="button_submit" /><input type="hidden" name="sc" value="',$context['session_id'],'" /></td>
      <td></td>
     </tr>
    </table>
	 </div>
  <span class="botslice"><span></span></span>
 </div>
</form>
';
}
?>