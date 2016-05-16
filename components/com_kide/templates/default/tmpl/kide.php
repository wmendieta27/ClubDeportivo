<?php 
/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die(); 

?>

<div class="KIDE_div" id="KIDE_div"<?php if (JRequest::getCmd('tmpl') == "component") echo ' style="padding:10px"'; ?>>
	<form id="kideForm" name="kideForm" method="post" onsubmit="return false" action="">
		<?php 	
		$this->display("botones");
		$this->display("msgs");
		$this->display("mostrar");
		$this->display("form");  
		?>
	</form>
</div>
<span id="KIDE_msg_sound"></span>

<?php $this->display("extra"); ?>

<script type="text/javascript">
<!--
kide.onLoad(function() {
	kide.$('KIDE_msgs').onmousedown = function() { kide.scrolling = true };
	kide.$('KIDE_msgs').onmouseup = function() { kide.scrolling = false };
});
kide.onLoad(function() {
	kide.$("encendido").src = kide.img_encendido[<?php echo $this->user->encendido; ?>];
});
<?php if ($this->autoiniciar || $this->user->encendido == 2) : ?>
kide.onLoad(kide.iniciar);
<?php elseif (!$this->autoiniciar && $this->user->encendido == 1) : ?>
kide.onLoad(function(){
	kide.$("KIDE_div").onmouseover = function() {
		kide.iniciar();
		kide.$("KIDE_div").onmouseover = '';
	};
});
<?php endif; ?>
kide.onLoad(kide.ajustar_scroll);
//-->
</script>