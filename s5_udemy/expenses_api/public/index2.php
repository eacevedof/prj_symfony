<?php
//var_dump($_REQUEST);
if (isset($_REQUEST['r'])){	
	if (isset($_REQUEST['r'])){	$r=$_REQUEST['r'];}
	if (isset($_REQUEST['g'])){	$g=$_REQUEST['g'];}
	if (isset($_REQUEST['b'])){	$b=$_REQUEST['b'];}
	$color=strtoupper(str_pad(dechex($r),2,0,STR_PAD_LEFT).str_pad(dechex($g),2,0,STR_PAD_LEFT).str_pad(dechex($b),2,0,STR_PAD_LEFT));
	echo $color;
	exit;
}
?>

<head>
<style>
	div{border:1px solid;margin:20px}
	#caja1{width:682px;height:40px;clear:left;margin:20px}
	#caja2{width:200px;height:40px;clear:left;margin:20px}
	.caja{width:200px;height:40px;float:left}
	input {width:200px;margin-top:20px;margin-left:20px;margin-right:20px}
</style>
<script type="text/javascript">
	function inicio(){
		console.log('inicio');
		document.getElementById('cajaR').onchange=teclado;
		document.getElementById('cajaG').onchange=teclado;
		document.getElementById('cajaB').onchange=teclado;
		document.getElementById('cajaR').value=255;
		document.getElementById('cajaG').value=255;	
		document.getElementById('cajaB').value=255;		
		pinta('FFFFFF');
	}
	function pinta(color){
		document.getElementById("caja1").style.background="#"+color;
		var cR=''+color.substr(0,2)+'0000';
		var cG='00'+color.substr(2,2)+'00';
		var cB='0000'+color.substr(4,2)+'';
		console.log(color,cR,cG,cB);
		document.getElementById("dR").style.background='#'+cR;
		document.getElementById("dG").style.background='#'+cG;
		document.getElementById("dB").style.background='#'+cB;	
		document.getElementById("caja2").innerHTML=color;
	}
	function teclado(){
		envio();
	}
	function envio(){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var color=this.responseText;
                pinta(color);
            }
		};
		var r=document.getElementById('cajaR').value;
		var g=document.getElementById('cajaG').value;
		var b=document.getElementById('cajaB').value;
		xhttp.open("GET","index2.php?"+"r="+r+"&g="+g+"&b="+b, true);
		xhttp.send();			
	}
	
	window.onload = inicio;

</script>
</head>
<body>
	<input id="cajaR" name='r' type='range' min="0" max="255" />
	<input id="cajaG" name='g' type='range' min="0" max="255" />
	<input id="cajaB" name='b' type='range' min="0" max="255" />
	<br/>
	<div id="dR" class='caja'></div>
	<div id="dG" class='caja'></div>
	<div id="dB" class='caja'></div>
	<div id="caja1" ></div>
	<div id="caja2" ></div>
</body>