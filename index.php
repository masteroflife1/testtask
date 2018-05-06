<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>API</title>
<!--<link rel="stylesheet" href="style.css">-->
<script src="jquery-3.3.1.js"></script>
</head>

<body>
<script type="text/javascript">
var d;
function getTable(d){
	var row = d[1];
	var code = '<tr>';
	var n = d.length;
	for (var i in row){
		code += '<td>' + i + '</td>';
	}
	code += "</tr>";
	//for (var j in d){
	for(var j = 1; j<n; j++){
		code += '<tr>';
		for (var i in d[j]){
			code += '<td>' + d[j][i] + '</td>';
		}
		code += '</tr>';
	}
	$("#tbl").html(code);
}
function sendAjx(){
	//console.log($("#reqMethod").val());
	var dt = 'method=' + $("#reqMethod").val() + '&param=' + $("#reqParam").val();
	$.ajax({
		type: "POST",
		url: "api.php",
		data: dt,
		success: function(msg){
			//alert( "Прибыли данные: " + msg );
			//$("#data").text("Данные: " + msg);
			d = JSON.parse(msg);
			if (d.length > 1) {
				$("#data").text('');
				getTable(d);
			} else {
				$("#tbl").html('');
				$("#data").text("Данные: " + msg);
			}
		}
	});
}
</script>
<select id="reqMethod">
	<option value="getRub">Таблица рубрик</option>
	<option value="getAuth">Таблица авторов</option>
	<option value="getNews">Таблица новостей (без текста)</option>
	<option value="getText">Текст новости по id</option>
	<option value="getRubNews">Таблица рубрик для новости</option>
</select>
<input type="text" id="reqParam">
<button onclick="sendAjx();">Запрос</button>
<div id="data"></div>
<table id="tbl" border="1"></table>
</body>

</html>