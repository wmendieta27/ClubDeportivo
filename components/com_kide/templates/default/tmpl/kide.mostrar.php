<?php 
/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die(); 

?>

<div id="KIDE_opciones" class="KIDE_mostrar" style="display: none">
	<div><?php echo JText::_("COM_KIDE_OCULTAR_SESION"); ?> <input type="checkbox" value="1" name="ocultar_sesion" id="ocultar_sesion" <?php if ($this->user->ocultar_sesion) echo 'checked="checked" '; ?> style="vertical-align:middle" /></div>
	<div><?php echo JText::_('COM_KIDE_TEMPLATE'); ?>: <?php echo $this->templates; ?></div>
	<div id="KIDE_opciones_colores"></div>
	<br />
	<button onclick="kide.save_options()"><?php echo JText::_("COM_KIDE_SAVE"); ?></button> <button onclick="kide.retardo_input()"><?php echo JText::_("COM_KIDE_RETARDO_INPUT"); ?></button>
</div>

<div id="KIDE_mensaje" class="KIDE_mostrar" style="display: none">
	<table width="100%">
		<tr>
			<td>
				· <span id="KIDE_mensaje_username"></span>
				<br />
				· <span id="KIDE_tiempo_msg"></span>
				<span id="KIDE_mensaje_perfil_span">
					<br />
					· <a target="_blank" id="KIDE_mensaje_perfil" href="javascript:void(0)"><?php echo JText::_("COM_KIDE_VERPERFIL"); ?></a>
				</span>
				<span id="KIDE_mensaje_borrar_span">
					<br />
					· <a id="KIDE_mensaje_borrar" href="javascript:void(0)"><?php echo JText::_("COM_KIDE_BORRARMENSAJE"); ?></a>
				</span>
				<span id="KIDE_mensaje_ocultar_span">
					<br />
					· <a id="KIDE_mensaje_ocultar" href="javascript:void(0)"><?php echo JText::_("COM_KIDE_HIDE_MESSAGE"); ?></a>
				</span>
				<?php if ($this->user->rango == 1) : ?>
				<span id="KIDE_mensaje_banear_span1">
					<br />
					· <a href="javascript:kide.show('KIDE_mensaje_banear_span')"><?php echo JText::_("COM_KIDE_MENSAJE_BANEAR"); ?></a>
					<span id="KIDE_mensaje_banear_span" style="display: none">
						<select name="kide_mensaje_banear_dias" style="padding:0">
							<option value="0"><?php echo ucfirst(JText::_("COM_KIDE_DAYS")); ?></option>
							<?php echo KideHelper::opciones(3); ?>
						</select>
						<select name="kide_mensaje_banear_horas" style="padding:0">
							<option value="0"><?php echo ucfirst(JText::_("COM_KIDE_HOURS")); ?></option>
							<?php echo KideHelper::opciones(24); ?>
						</select>
						<select name="kide_mensaje_banear_minutos" style="padding:0">
							<option value="0"><?php echo ucfirst(JText::_("COM_KIDE_MINUTES")); ?></option>
							<?php echo KideHelper::opciones(60); ?>
						</select>
						<button style="padding:0" id="KIDE_mensaje_banear"><?php echo JText::_("COM_KIDE_MENSAJE_BANEAR_MIN"); ?></button>
					</span>
				</span>
				<?php endif; ?>
				<br />
				<a href="" id="KIDE_mensaje_img_enlace"><img style="border:0" id="KIDE_mensaje_img" src="<?php echo $this->include_html('otras', 'blank.png'); ?>" alt="" class="KIDE_avatar" /></a>
			</td>
			<td style="text-align: right; vertical-align: top">
				<a href="javascript:kide.show('KIDE_mensaje',false)" class="KIDE_cerrar_x">X</a>
			</td>
		</tr>
	</table>
</div>

<div id="KIDE_usuario" class="KIDE_mostrar" style="display: none">
	<table width="100%">
		<tr>
			<td>
				· <span id="KIDE_usuario_name"></span>
				<span id="KIDE_usuario_perfil_mostrar">
					<br />
					· <a target="_blank" id="KIDE_usuario_perfil" href="javascript:void(0)"><?php echo JText::_("COM_KIDE_VERPERFIL"); ?></a>
				</span>
				<?php if ($this->user->rango == 1) : ?>
				<span id="KIDE_usuario_banear_span1">
					<br />
					· <a href="javascript:kide.show('KIDE_usuario_banear_span')"><?php echo JText::_("COM_KIDE_MENSAJE_BANEAR"); ?></a>
					<span id="KIDE_usuario_banear_span" style="display: none">
						<select name="kide_usuario_banear_dias" style="padding:0">
							<option value="0"><?php echo ucfirst(JText::_("COM_KIDE_DAYS")); ?></option>
							<?php echo KideHelper::opciones(3); ?>
						</select>
						<select name="kide_usuario_banear_horas" style="padding:0">
							<option value="0"><?php echo ucfirst(JText::_("COM_KIDE_HOURS")); ?></option>
							<?php echo KideHelper::opciones(24); ?>
						</select>
						<select name="kide_usuario_banear_minutos" style="padding:0">
							<option value="0"><?php echo ucfirst(JText::_("COM_KIDE_MINUTES")); ?></option>
							<?php echo KideHelper::opciones(60); ?>
						</select>
						<button style="padding:0" id="KIDE_usuario_banear"><?php echo JText::_("COM_KIDE_MENSAJE_BANEAR_MIN"); ?></button>
					</span>
				</span>
				<?php endif; ?>
				<br />
				<a href="" id="KIDE_usuario_img_enlace"><img style="border:0" id="KIDE_usuario_img" src="<?php echo $this->include_html('otras', 'blank.png'); ?>" alt="" class="KIDE_avatar" /></a>
			</td>
			<td style="text-align: right; vertical-align: top">
				<a href="javascript:kide.show('KIDE_usuario',false)" class="KIDE_cerrar_x">X</a>
			</td>
		</tr>
	</table>
</div>
	
<div id="KIDE_rangos" class="KIDE_mostrar" style="display: none">
	<?php echo JText::_("COM_KIDE_RANGOS"); ?>: <br />
	<img class="KIDE_r KIDE_bg_admin" src="<?php echo $this->include_html("otras", "blank.png"); ?>" alt="" /> &nbsp; <?php echo JText::_("COM_KIDE_ADMINISTRADOR"); ?><br />
	<img class="KIDE_r KIDE_bg_registered" src="<?php echo $this->include_html("otras", "blank.png"); ?>" alt="" /> &nbsp; <?PHP echo JText::_("COM_KIDE_REGISTRADO") ;?><br />
	<img class="KIDE_r KIDE_bg_guest" src="<?php echo $this->include_html("otras", "blank.png"); ?>" alt="" /> &nbsp; <?php echo JText::_("COM_KIDE_INVITADO"); ?><br />
	<img class="KIDE_r KIDE_bg_special" src="<?php echo $this->include_html("otras", "blank.png"); ?>" alt="" /> &nbsp; <?php echo JText::_("COM_KIDE_ESPECIAL"); ?><br />
</div>