$('.txtNumeroDecimal').change(function(){
	$(this).val(parseFloat($(this).val()).toFixed(2));
});
$('.esDecimal').change(function(){
	var campo = $(this);
	var valor =campo.val();
	$(this).val(parseFloat(valor).toFixed(2));
});
$('.noEsDecimal').change(function(){
	var campo = $(this);
	var valor =campo.val();
	$(this).val(parseFloat(valor).toFixed(0));
});
$('.esMoneda').change(function(){
	var campo = $(this);
	var valor =campo.val();
	if(valor<0){
		$(this).val('0.00')
	}else{
		$(this).val(parseFloat(valor).toFixed(2));
	}
});
$('#agregarBarra').click(function(){
	//console.log('Se hizo clic en el boton agregar barra');
	if($('#txtBarras').val()!=''){
	$('#listBarras').show('normal');
	$('#listBarras').append('<li class="collection-item">'+$('#txtBarras').val()+'<a href="#!" class="secondary-content"><i class="material-icons red-text">close</i></a></li>')
	$('#txtBarras').val('');}
});
$(document).ready(function(){
	$('.modal-iniciarSesion').on('shown.bs.modal', function () {
	$('#txtVolverUsuario').focus();
});
$('#btnVolverIniciarSesion').click(function () {
	if( ! $('#btnVolverIniciarSesion i').hasClass('fa-spin')){
		$('.modal-iniciarSesion .divError').addClass('hidden');
		$('#btnVolverIniciarSesion i').addClass('fa-spin');
		if( $('#txtVolverUsuario').val()=='' ){
			$('.modal-iniciarSesion .spanError').text('Falta rellenar tu usuario');
			$('.modal-iniciarSesion .divError').removeClass('hidden');
			$('#btnVolverIniciarSesion i').removeClass('fa-spin');
		}else if($('#txtVolverPasw').val()==''){
			$('.modal-iniciarSesion .spanError').text('Falta rellenar tu usuario');
			$('.modal-iniciarSesion .divError').removeClass('hidden');
			$('#btnVolverIniciarSesion i').removeClass('fa-spin');
		}else{
			$.ajax({url:'php/validarSesion.php', type: 'POST', data: { user: $('#txtVolverUsuario').val(), pws: $('#txtVolverPasw').val() } }).done(function (resp) {
				console.log(resp);
				if(parseInt(resp)>0 && esNumero(resp)){ location.reload();}
				else{
					$('.modal-iniciarSesion .spanError').text('Las credenciales no coinciden');
					$('.modal-iniciarSesion .divError').removeClass('hidden');
					$('#btnVolverIniciarSesion i').removeClass('fa-spin');
				}
			});
		}
	}
});
});
$.fn.modal.prototype.constructor.Constructor.DEFAULTS.backdrop = 'static'; //Para que no cierre el modal, cuando hacen clic en cualquier parte

function esNumero(cadena) //true para si es número sólo
{
	if (cadena.match(/^[0-9]+$/))
	{
		return true;}
	else
	{
		return false;	}
}

$(".ocultar-mostrar-menu").click(function() {
	ocultar();
});
function ocultar(){//console.log('oc')
	$("#wrapper").toggleClass("toggled");
	//$('.navbar-fixed-top').css('left','0');
	$('.navbar-fixed-top').toggleClass('encoger');
	$('#btnColapsador').addClass('collapsed');
	$('#btnColapsador').attr('aria-expanded','false');
	$('#navbar').removeClass('in');
}
$('.has-clear').mouseenter(function(){$(this).find('input').focus();});

$('.has-clear input[type="text"]').on('input propertychange', function() {
	var $this = $(this);
	var visible = Boolean($this.val());
	$this.siblings('.form-control-clear').toggleClass('hidden', !visible);
}).trigger('propertychange');

$('.form-control-clear').click(function() {
	$(this).siblings('input[type="text"]').val('')
		.trigger('propertychange').focus();
});

$("input").focus(function(){
  this.select();
});

$('body').on('keypress','#txtVolverPasw', function(e){console.log('hola');
	if (e.keyCode === 10 || e.keyCode === 13) 
		{e.preventDefault(); 
		$('#btnVolverIniciarSesion').click();
	 }
});
$('.soloLetras').keypress(function (e) {//|| 
	if(!(e.which >= 97 /* a */ && e.which <= 122 /* z */) && !(e.which >= 48 /* 0 */ && e.which <= 90 /* 9 */)  ) {
        e.preventDefault();
    }
});
$('#txtBuscarNivelGod').keypress(function (e) {
	if (e.keyCode === 10 || e.keyCode === 13) 
		{e.preventDefault();
		//analizar que esta entrando
		// numbero >6 dni o telefonos
		// Numero <=6 buscar por id producto
		// Letra buscar nombre cliente o nombre producto

		var campo = $(this).val().toUpperCase();
		if( campo.indexOf('CR-')!=-1 ){ //$.isNumeric(campo) && campo.length<6
			$.post('php/58encode.php', {texto: campo.replace('CR-', '') }, function(resp) {
				window.location.href = 'creditos.php?credito='+resp;
			});
		}else{ // es letras
			if($('#txtBuscarNivelGod').val()!=''){
				window.location.href = 'clientes.php?buscar='+campo;
				// $.ajax({url: 'php/buscarClientesDniNombre.php', type: 'POST', data: {texto: campo }}).done(function (resp) {
				// console.log(resp);
				// //dato = JSON.parse(resp); 
				// $('#rowProductoEncontrado').html(resp);
				// $('.modal-mostrarResultadosProducto').modal('show');
				// });
			}
		}
	}
});
$('#liDatosPersonales').mouseenter(function() {
	$(this).addClass('open');
});
$('#liDatosPersonales').mouseleave(function() {
	$(this).removeClass('open');
});
function pantallaOver(tipo) {
	if(tipo){$('#overlay').css('display', 'initial');}
	else{ $('#overlay').css('display', 'none'); }
}