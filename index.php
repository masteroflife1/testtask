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
	var row;// = d[1];
	var code;// = '<tr>';
	var prkeys = '';
	var keys;
	var n = d.length;
	/*for (var i in row){
		code += '<th>' + i + '</th>';
	}
	code += "</tr>";*/
	//for (var j in d){
	for(var j = 1; j<n; j++){
		row = d[j];
		keys = '';
		for (var i in row){
			keys += '<th>' + i + '</th>';
		}
		if (keys != prkeys){
			code += '<tr>' + keys + '</tr>'
		}
		prkeys = keys;
		code += '<tr>';
		for (var i in row){
			code += '<td>' + row[i] + '</td>';
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
	<option value="getAuthorNews">Все новости автора по id</option>
	<option value="getNewsByRub">Список новостей по id рубрики</option>
	<option value="getAuthList">Список авторов</option>
	<option value="getNewsInfo">Информация о новостях по id</option>
	
</select>
<input type="text" id="reqParam">
<button onclick="sendAjx();">Запрос</button>
<div id="data"></div>
<table id="tbl" border="1"></table>
</body>

</html>