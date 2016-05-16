/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

var kide = {
	debug: false,
	sids: [],
	mostrar_colores_iniciado: false,
	sesiones_parado: true,
	recargar_parado: true,
	privados_parado: true,
	privados_encontrado: false,
	retardo_avisar: false,
	shift_pressed: false,
	shift_priv_pressed: false,
	popup: null,
	scrolling: false,
	scrolling_privados: false,

	$: function(id) {
		return document.getElementById(id);
	},
	defined: function(value) {
		return typeof(value) != "undefined";
	},
	css: function(id, param, value) {
		if (!this.checkID(id)) return;
		if (this.defined(value)) this.$(id).style[param] = value;
		else return this.$(id).style[param];
	},
	attr: function(id, param, value) {
		if (!this.checkID(id)) return;
		if (this.defined(value)) this.$(id)[param] = value;
		else return this.$(id)[param];
	},
	val: function(id, v) {
		if (!this.checkID(id)) return;
		if (this.defined(v)) this.$(id).value = v;
		else return this.$(id).value;
	},
	html: function(id, value) {
		if (!this.checkID(id)) return;
		if (this.defined(value)) this.$(id).innerHTML = value;
		else return this.$(id).innerHTML;
	},
	show: function(id,s) {
		if (!this.checkID(id)) return;
		if (this.defined(s)) s = s ? "" : "none";
		else s = this.css(id,"display") == "none" ? "" : "none";
		this.css(id,"display",s);
	},
	visible: function(id,s) {
		if (!this.checkID(id)) return;
		if (this.defined(s)) s = s ? "" : "hidden";
		else s = this.css(id,"visibility") == "hidden" ? "" : "hidden";
		this.css(id,"visibility",s);
	},
	foco: function(id) {
		if (!this.checkID(id)) return;
		this.$(id).focus();
	},
	checkID: function(id) {
		if (this.$(id)) return true;
		if (this.debug) {
			this.error("The element with id '"+id+"' doesn't exists.", 2);
		}
	},
	log: function(msg, f, l) {
		if (this.debug && console && console.log) {
			console.log("Kide error: "+msg+" at "+f+":"+l);
		}
	},
	error: function(msg, n, e) {
		try {
			var up = 0;
			if (!this.defined(e)) {
				up++;
				var e = new Error();
			}
			s = e.stack;
			if (s.indexOf("@") != -1) {
				var s = s.split("\n")[n+up].split("@")[1].split(":");
				var l = s[s.length-1];
				s[s.length-1] = '';
				s = s.join(':'); 
				var f = s.substr(0, s.length-1);
				this.log(msg, f, l);
			}
			else if("at " != -1) {
				var s = s.split("\n")[n+up].match(/\(([^\)]+)/)[1].split(":");
				var l = s[s.length-2];
				s[s.length-1] = '';
				s[s.length-2] = '';
				s = s.join(':'); 
				var f = s.substr(0, s.length-2);
				this.log(msg, f, l);
			}
		}
		catch(err) {
		
		}
	},
	nuevoAjax: function() {
		var xmlhttp=false;
		if (kide.defined('ActiveXObject')) {
			try {
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (E) {
					xmlhttp = false;
				}
			}
		}
		if (!xmlhttp && kide.defined('XMLHttpRequest'))
			xmlhttp = new XMLHttpRequest();
		return xmlhttp;
	},
	form: function(param, v) {
		if (typeof(v) == "undefined") return document.forms.kideForm[param].value;
		else document.forms.kideForm[param].value = v;
	},
	onLoad: function(func, func2) {
		if (kide.fast_init) {
			if (this.defined(func2)) (func2)();
			else (func)();
		}
		else if (window.addEventListener) window.addEventListener("load", func, false);
		else if (window.attachEvent) window.attachEvent("onload", func);
		else if (this.defined(func2)) (func2)();
		else (func)();
	},
	addHTMLInBody: function(html) {
		this.onLoad(function() {
			var div = document.createElement('div');
			div.setAttribute('class', 'KIDE_div');
			div.innerHTML = html;
			kide.insertAfter(div, document.body.lastChild);
		}, function(){
			document.write(html);
		}); 
	},
	iniciar: function() {
		kide.encendido = 2;
		kide.attr('encendido', 'src', kide.img_encendido[2]);
		if (kide.recargar_parado) {
			kide.recargar_parado = false;
			kide.recargar();
		}
		if (kide.sesiones_parado) {
			kide.sesiones_parado = false;
			kide.sesiones();
		}
		kide.events.lanzar('onIniciar');
	},
	open_popup: function() {
		if (this.popup)
			this.popup.close();
		this.popup=window.open(this.popup_url, 'kide', 'toolbar=0,location=0,menubar=0,directories=0,resizable=1,scrollbars=1,width=800,height=600');
	},
	text: function(row) {
		var n = navigator.userAgent.toString();
		if (n.indexOf("MSIE") != -1)
			return row.text;
		return row.textContent;
	},
	recargar: function() {
		if (this.encendido == 2) {
			this.ajax("reload");
			setTimeout("kide.recargar()", this.refresh_time);
		}
		else
			this.recargar_parado = true;
	},
	getUser: function(sid) {
		return this.defined(this.sids[sid]) ? this.sids[sid] : null;
	},
	getUserById: function(uid) {
		for(var i in this.sids) {
			if (this.sids[i].id == uid)
				return this.sids[i];
		}
	},
	sesiones: function() {
		if (this.encendido == 2) {
			this.ajax("sesiones");
			setTimeout("kide.sesiones()", this.refresh_time_sesion);
		}
		else
			this.sesiones_parado = true;
	},
	apagar_encender: function() {
		if (this.encendido == 0)
			this.encendido++;
		else if (this.encendido == 1) 
			this.iniciar();
		else 
			this.encendido = 0;
			
		this.save_config("encendido", this.encendido);
		this.attr('encendido', 'src', this.img_encendido[this.encendido]);
	},
	sonido: function() {
		if (this.sound != -1) {
			if (this.sound == 1) {
				this.sound = 0;
				this.attr('sound', 'src', this.sound_off);
			}
			else {
				this.sound = 1;
				this.attr('sound', 'src', this.sound_on);
				this.play_msg_sound();
			}
			this.save_config("sound", this.sound);
		}
	},
	save_config: function(param, value) {
		var ajax = this.nuevoAjax();
		var config = document.cookie.match(/kide_config=([^;]*)/);
		config = decodeURIComponent(config[1]);
		if (config.search(eval('/'+param+'=/')) > -1)
			config = config.replace(eval('/'+param+'=[^;]*/'), param+'='+value);
		else
			config += ';'+param+'='+value;
		document.cookie = 'kide_config='+encodeURIComponent(config)+'; path=/';
	},
	ahora: function() {
		var ya = new Date();
		var m = ya.getMonth() + 1;
		ya = ya.getDate()+"-"+(m < 10 ? "0" : "")+m+" "+ya.getHours()+":"+(ya.getMinutes() < 10 ? "0" : "")+ya.getMinutes()+":"+(ya.getSeconds() < 10 ? "0" : "")+ya.getSeconds();
		return ya;
	},
	in_array: function(e, a) {
		for (var i=0; i<a.length; i++)
			if (a[i] == e) return true;
		return false;
	},
	insertAfter: function(newElement,targetElement) {
		var parent = targetElement.parentNode;
		if (parent.lastchild == targetElement) 
			parent.appendChild(newElement);
		else 
			parent.insertBefore(newElement, targetElement.nextSibling);
	},
	trim: function(a,e) {
		//http://phpjs.org/functions/trim:566
		var c,d=0,b=0;a+="";if(!e)c=" \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";else{e+="";c=e.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g,"$1")}d=a.length;for(b=0;b<d;b++)if(c.indexOf(a.charAt(b))===-1){a=a.substring(b);break}d=a.length;for(b=d-1;b>=0;b--)if(c.indexOf(a.charAt(b))===-1){a=a.substring(0,b+1);break}return c.indexOf(a.charAt(0))===-1?a:"";
	},
	htmlspecialchars_decode: function(b,a) {
		//http://phpjs.org/functions/htmlspecialchars_decode:427
		var f=0,c=0,e=false;if(typeof a==="undefined")a=2;b=b.toString().replace(/&lt;/g,"<").replace(/&gt;/g,">");var d={ENT_NOQUOTES:0,ENT_HTML_QUOTE_SINGLE:1,ENT_HTML_QUOTE_DOUBLE:2,ENT_COMPAT:2,ENT_QUOTES:3,ENT_IGNORE:4};if(a===0)e=true;if(typeof a!=="number"){a=[].concat(a);for(c=0;c<a.length;c++)if(d[a[c]]===0)e=true;else if(d[a[c]])f=f|d[a[c]];a=f}if(a&d.ENT_HTML_QUOTE_SINGLE)b=b.replace(/&#0*39;/g,"'");if(!e)b=b.replace(/&quot;/g,'"');b=b.replace(/&amp;/g,"&");return b;
	},
	check_shift: function(e, up, priv) {
		var code = this.getCode(e);
		if (up) {
			if (code == 16) { //shift
				if (priv)
					this.shift_priv_pressed = false;
				else
					this.shift_pressed = false;
			}
		}
		else if (code != 13) { //enter
			if (priv)
				this.shift_priv_pressed = code == 16;
			else
				this.shift_pressed = code == 16;
		}
	},
	getCode: function(e) {
		return e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
	},
	isEnter: function(e) {
		return this.getCode(e) == 13;
	},
	pressedEnter: function(e, priv) {
		if (this.isEnter(e)) {
			if ((!priv && this.shift_pressed) || (priv && this.shift_priv_pressed))
				return true;
			else if (priv) 
				this.ajax("privados_insertar");
			else
				this.sm();
			return false;
		} 
		else
			return true;
	},
	tiempo: function(t) {
		t = Number(t);
		if (t <= 0) {
			kide.show('KIDE_tiempo_p', false);
			return;
		}
		kide.show('KIDE_tiempo_p', true);
		t -= this.retardo;
		var time = new Date();
		time = time.getTime();
		t = Math.floor((time/1000) - t);
		if (t <= 0) t = 1;
		
		var out = "";
		var i;
		var salir = false;
		var datos = new Array();
		datos[0] = new Array();
		datos[0][0] = Math.floor(t/2592000);
		datos[0][1] = Math.floor((t - datos[0][0]*2592000)/86400); 
		datos[0][2] = Math.floor((t - datos[0][0]*2592000 - datos[0][1]*86400)/3600);
		datos[0][3] = Math.floor((t - datos[0][0]*2592000 - datos[0][1]*86400 - datos[0][2]*3600)/60);
		datos[0][4] = Math.floor(t - datos[0][0]*62592000 - datos[0][1]*86400 - datos[0][2]*3600 - datos[0][3]*60);
		datos[1] = [1, 3, 7, 10];
		
		for (i=0;i<=4 && !salir;i++) {
			if (datos[0][i]) {
				salir = true;
				out += datos[0][i]+" "+this.msg.lang[datos[0][i]!=1 ? i*2+1 : i*2];
				if (i < 4 && datos[0][i] <= datos[1][i] && datos[0][i+1]) 
					out += " "+datos[0][i+1]+" "+this.msg.lang[datos[0][i+1]!=1 ? (i+1)*2+1 : (i+1)*2];
			}
		}
		if (!out) out = '1 '+this.msg.lang[8];
		kide.html('KIDE_tiempoK', out); 
	},
	insertSmile: function(text) {
		var textarea = document.forms.kideForm.txt;
		textarea.value += " "+text;
		textarea.focus(textarea.value.length - 1);
	},
	filter_smilies: function(s) {
		s = " "+s+" ";
		for (var i = 0; i < this.smilies.length; i++) {
			s = s.replace(" "+this.smilies[i][0], '<img alt="' + this.smilies[i][0] + '" title="' + this.smilies[i][0] + '" src="' + this.smilies[i][1] + '" class="KIDE_icono" />');
			s = s.replace(" "+this.smilies[i][0].toLowerCase(), '<img alt="' + this.smilies[i][0] + '" title="' + this.smilies[i][0] + '" src="' + this.smilies[i][1] + '" class=KIDE_icono" />')
		}
		return s;
	},
	tohtml: function(s) {
		s = s.replace(/&/g, "&amp;");
		s = s.replace(/</g, "&lt;");
		s = s.replace(/>/g, "&gt;");
		s = s.replace(/'/g, "&#39;");
		s = s.replace(/"/g, "&quot;");
		return s;
	},
	sm: function() {
		this.ajax("insertar");
		if (this.rango == 3) 
			this.anti_flood_spam();
		if (this.encendido == 1) 
			this.iniciar();
	},
	anti_flood_spam: function() {
		var total = this.ban_total+2;
		if (this.ban[0] != total-1) {
			this.ban[0]++;
			var time = new Date();
			time = time.getTime();
			this.ban[this.ban[1]] = time;
			this.ban[1]++;
		}
		else {
			var i;
			for (i=2;i<total;i++)
				this.ban[i] = this.ban[i+1];
			var time = new Date();
			time = time.getTime();
			this.ban[total] = time;
			var aux = this.ban[total] - this.ban[2];
			if (aux < this.ban_time*1000) {
				this.val('KIDE_txt', '');
				this.attr('KIDE_txt', 'disabled', true);
				this.ajax("baneado");
			}
		}
	},
	retardo_input: function() {
		this.retardo_avisar = true;
		this.ajax("retardo");
	},
	mostrar_iconos: function() {
		if (this.$('KIDE_iconos')) {
			this.save_config('icons_hidden', this.css('KIDE_iconos', 'display') == 'none' ? 0 : 1);
			this.show('KIDE_iconos');
		}
	},
	play_msg_sound: function() {
		if (navigator.userAgent.toString().indexOf("MSIE") != -1)
			this.html('KIDE_msg_sound', '<object name="msg_sound" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#3,0,0,0" width="0" height="0"><param name="src" value="'+this.sound_src+'"><param name="loop" value="false"></object>');
		else
			this.html('KIDE_msg_sound', '<embed name="msg_sound" src="'+this.sound_src+'" width="0" height="0" loop="false" />');
	},
	mostrar_opciones: function() {
		if (!this.mostrar_colores_iniciado) {
			this.mostrar_colores_iniciado = true;
			this.show_colors();
		}
		this.show('KIDE_opciones');
	},
	save_options: function() {
		this.show('KIDE_opciones', false);
		if (this.color)
			this.save_config("color", this.color);
		this.save_config("ocultar_sesion", this.attr('ocultar_sesion', 'checked')?1:0);
		if (this.form("KIDE_template") != this.template) {
			this.save_config("template", this.form("KIDE_template"));
			location.reload();
		}
	},
	change_name: function(t) {
		var v = t.value;
		if (v && v != this.name) {
			this.name = v;
			this.save_config("name", v);
		}
		else
			t.value = this.name;
	},
	set_color: function(c) {
		if (this.works) {
			this.color = c;
			this.css('KIDE_txt', 'color', "#"+c);
			this.events.lanzar('onSetColor', c);
		}
	},
	borrar: function(id) {
		if (id > 0) {
			this.show("KIDE_id_"+id, false);
			this.show("KIDE_mensaje", false)
			this.ajax("borrar", id);
		}
		else
			alert(this.msg.mensaje_borrar);
	},
	getDocumentWidth: function() {
		return window.innerWidth ? window.innerWidth : document.documentElement.clientWidth;
	},
	getDocumentHeight: function() {
		return window.innerHeight ? window.innerHeight : document.documentElement.clientHeight;
	},
	banear: function(sid, tipo) {
		var dias = this.form('kide_'+tipo+'_banear_dias');
		var horas = this.form('kide_'+tipo+'_banear_horas');
		var minutos = this.form('kide_'+tipo+'_banear_minutos');
		if (dias>0 || horas>0 || minutos>0)
			this.ajax("banear", [sid, tipo]);
	},
	ajax: function(tipo, tmp) {
		var ajax = this.nuevoAjax();
		if (tipo == "reload") { 
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
					var xml = ajax.responseXML.documentElement;
					if (xml.getElementsByTagName('mensaje').length > 0) {
						var row;
						kide.n = kide.text(xml.getElementsByTagName('last_id')[0]);
						kide.last_time = kide.text(xml.getElementsByTagName('last_time')[0]);
						for (var i=0; i<xml.getElementsByTagName('mensaje').length; i++) {
							row = xml.getElementsByTagName('mensaje')[i];
							kide.insertNewContent(row.getAttribute("uid"),row.getAttribute("name"),kide.htmlspecialchars_decode(kide.text(row)),row.getAttribute("url"),row.getAttribute("date"),row.getAttribute("color"),row.getAttribute("rango"),row.getAttribute("id"),row.getAttribute("sesion"),row.getAttribute("sesion")==kide.sesion,row.getAttribute("hora"),row.getAttribute("img"));
						}
					}
					kide.tiempo(kide.last_time);
					kide.ajustar_scroll();
					kide.events.lanzar('onAjaxReload', xml);
				}
			};
			if (this.direct) {
				ajax.open('POST', this.direct_url+'reload.php', true);
				ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
				ajax.send("privs="+(kide.privados_encontrado?0:1)+"&id="+this.n+"&token="+this.token+"&order="+this.order+"&gmt="+kide.gmt+"&formato_hora="+encodeURIComponent(kide.formato_hora)+"&formato_fecha="+encodeURIComponent(kide.formato_fecha));
			}
			else {
				ajax.open('POST',  this.ajax_url+"&task=reload", true);
				ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
				ajax.send("privs="+(kide.privados_encontrado?0:1)+"&id="+this.n+"&token="+this.token);
			}
		}
		else if (tipo == "insertar") {
			var txt = this.val('KIDE_txt');
			kide.val('KIDE_txt', '');
			if (!kide.trim(txt)) return;
			this.visible('KIDE_img_ajax', true);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
					var xml = ajax.responseXML.documentElement;
					if (xml.getAttribute('banned') == 1) {
						location.reload();
						return;
					}
					if (xml.getElementsByTagName('comment').length) {
						var texto = kide.text(xml.getElementsByTagName('comment')[0]);
						kide.insertNewContent(0,'System',texto,'',kide.ahora(),'',0,0,0,false,xml.getAttribute('hora'),''); 
					}
					if (xml.getElementsByTagName('txt').length) {
						var texto = kide.text(xml.getElementsByTagName('txt')[0]);
						kide.insertNewContent(kide.userid,kide.name,texto.length?texto:txt,kide.url,kide.ahora(),kide.color,kide.rango,xml.getAttribute('id'),kide.sesion,true,xml.getAttribute('hora'),xml.getAttribute('img'));
						kide.last_time = xml.getAttribute('tiempo');
						kide.tiempo(kide.last_time);
						kide.ajustar_scroll();
					}
					kide.visible('KIDE_img_ajax', false);
					if (xml.getElementsByTagName('js').length) {
						eval(kide.text(xml.getElementsByTagName('js')[0]));
					}
					kide.events.lanzar('onAjaxInsertar', xml);
				}
			};
			ajax.open('POST', this.ajax_url+"&task=add", true);
			ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			ajax.send("txt="+encodeURIComponent(txt)+"&token="+this.token);
		}
		else if (tipo == "baneado") {
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4)
					location.reload();
			};
			ajax.open('POST', this.ajax_url+"&task=add", true);
			ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			ajax.send("banear=1");
		}
		else if (tipo == "borrar") {
			ajax.open('POST', this.ajax_url+"&task=borrar", true);
			ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			ajax.send("id="+tmp);
		}
		else if (tipo == "sesiones") {
			if (kide.show_sessions) {
				ajax.onreadystatechange = function() {
					if (ajax.readyState == 4 && ajax.status == 200) {
						var xml = ajax.responseXML.documentElement;
						kide.sids = [];
						kide.html('KIDE_usuarios', '');
						var alias, name;
						for (var i=xml.getElementsByTagName('user').length-1; i>=0; i--) {
							row = xml.getElementsByTagName('user')[i];
							var sid = row.getAttribute("sesion");
							kide.sids[sid] = {
								rango: row.getAttribute("rango"),
								name: row.getAttribute("name"),
								_class: row.getAttribute("class"),
								sesion: row.getAttribute("sesion"),
								profile: row.getAttribute("profile"),
								id: row.getAttribute("userid"),
								img: row.getAttribute("img")
							};
							kide.events.lanzar('onAjaxSession', kide.getUser(sid));
							kide.insert_sesion(kide.getUser(sid));
						}
					}
				};
			}
			ajax.open('POST', this.ajax_url+"&task=sesiones&show_sessions="+kide.show_sessions, true);
			ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			ajax.send("token="+this.token);
		}
		else if (tipo == "retardo") {
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
					var out = ajax.responseText.split("|");
					out = out[0];
					if (out > 0) {
						var time = new Date();
						time = time.getTime();
						out = out - Math.floor((time/1000));
						kide.retardo = out;
						kide.save_config("retardo", kide.retardo);
						if (kide.retardo_avisar) {
							alert(kide.msg.retardo_frase.replace("%s", out));
						}
					}
				}
			};
			ajax.open('POST', this.ajax_url+"&task=retardo", true);
			ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			ajax.send(null);
		}
		else if (tipo == "banear") {
			var dias = this.form('kide_'+tmp[1]+'_banear_dias');
			var horas = this.form('kide_'+tmp[1]+'_banear_horas');
			var minutos = this.form('kide_'+tmp[1]+'_banear_minutos');
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
					var out = ajax.responseText;
					alert(out);
					kide.show('KIDE_'+tmp[1]+'_banear_span', false);
					kide.form('kide_'+tmp[1]+'_banear_dias', 0);
					kide.form('kide_'+tmp[1]+'_banear_horas', 0);
					kide.form('kide_'+tmp[1]+'_banear_minutos', 0);
				}
			};
			ajax.open('POST', this.ajax_url+"&task=banear", true);
			ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			ajax.send("sesion="+tmp[0]+"&dias="+dias+"&horas="+horas+"&minutos="+minutos);
		}
		else {
			this.events.lanzar('onAjax_'+tipo, [ajax, tmp]);
		}
	}
};

// eventos

kide.events = {
	list: [],
	add: function(name, func) {
		if (typeof(func) != 'function') return;
		if (!this.list[name])
			this.list[name] = [];
		this.list[name].push(func);
	},
	lanzar: function(name, params) {
		var stop = false;
		if (this.list[name]) {
			if (!params) params = [];
			for (var i=0; i<this.list[name].length;i++)
				stop = (this.list[name][i])(params) || stop;
		}
		return stop;
	}
};

// catpcha

kide.captcha = {
	check: function() {
		kide.ajax('captcha_check');
	},
	onAjax_check: function(data) {
		var ajax = data[0];
		ajax.onreadystatechange = function() {
			if (ajax.readyState == 4 && ajax.status == 200) {
				var out = ajax.responseText;
				if (out == 'ok') {
					kide.show('KIDE_catpcha', false);
					kide.show('KIDE_form', true);
				}
				else {
					Recaptcha.reload();
					alert(out);
				}
			}
		};
		ajax.open('POST', kide.ajax_url+"&task=catpcha_check", true);
		ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		ajax.send('recaptcha_challenge_field='+encodeURIComponent(kide.form('recaptcha_challenge_field'))+'&recaptcha_response_field='+encodeURIComponent(kide.form('recaptcha_response_field')));
	}
};
kide.events.add('onAjax_captcha_check', kide.captcha.onAjax_check);