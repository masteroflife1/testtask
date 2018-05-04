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
	//var d;
function getTable(d){
	var row = d[0];
	var code = '<tr>';
	var n = d.length;
	for (var i in row){
		code += '<td>' + i + '</td>';
	}
	code += "</tr>";
	//for (var j in d){
	for(var j = 0; j<n; j++){
		code += '<tr>';
		for (var i in d[j]){
			code += '<td>' + d[j][i] + '</td>';
		}
		code += '</tr>';
	}
	$("#tbl").html(code);
}
function sendAjx(){
	//alert( "alert" );
	$.ajax({
		type: "POST",
		url: "api.php",
		data: "name=John&location=Boston",
		success: function(msg){
			//alert( "Прибыли данные: " + msg );
			$("#data").text("Данные: " + msg);
			var d = JSON.parse(msg);
			getTable(d);
		}
	});
}
</script>	
<button onclick="sendAjx();">Запрос</button>
<div id="data"></div>
<table id="tbl" border="1"></table>
</body>

</html>