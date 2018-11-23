<?php
require_once('classes/auth.php');
// $auth = new Auth();
// $auth->add("new_login","Password1");
// $auth->add("new_login2","Password1");
function getBase(){
	$names=file('base.txt');
	$_entries=array();
	foreach($names as $name)
	{
		$rez["login"]=explode("#",$name)[0];
		$rez["password"]=explode("#",$name)[1];
		$_entries[]=$rez;
	}
		// print_r($_entries);
	return($_entries);
}
?>
<!-- <a href="test/auth_test.php">Test</a> -->

<!DOCTYPE html>
<html>
<head>
	<title>TDD demonstration</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

	<style>
	.box{
		margin-top: 10%;
		/*margin-right:10px;*/
		box-shadow:0px -1px 27px 6px rgba(0,0,0,0.21);
		-webkit-box-shadow:0px -1px 27px 6px rgba(0,0,0,0.21);
		-moz-box-shadow:0px -1px 27px 6px rgba(0,0,0,0.21);
		padding:5%;
		
	}
	.error{
		color:red;
		font-weight: bold;
	}
	.success{
		color:green;
		font-weight: bold;
	}
</style>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script
src="http://code.jquery.com/jquery-3.3.1.js"
integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>
<body>
	<?
// print_r($_SERVER['SCRIPT_FILENAME']);
// print_r($_SERVER['DOCUMENT_ROOT']);
	?>
	<div class="container">
		<div class="row">
			<a href="test/auth_test.php" class="btn btn-light">Перейти на страницу тестов</a>
		</div>
		<div class="row">
			<div class="col-md-6 col-s-12 box">
				<form>
					<p id="comment"></p>
					<div class="form-group">
						<label for="login">Login</label>
						<input type="text" class="form-control" id="login" aria-describedby="loginHelp" placeholder="Enter login">
						<small id="loginHelp" class="form-text text-muted">Логин должен состоять из набора латинских букв и цифр, длинной от 4 до 10 символов.</small>
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="text" class="form-control" id="password" placeholder="Password" aria-describedby="loginHelp">
						<small id="loginHelp" class="form-text text-muted">Пороль должен содержать латинские буквы верхнего и нижнего регистра, длина от 6 до 10 символов.</small>
					</div>
					<button type="submit" class="btn btn-primary add_new">Зарегистрироваться</button>
					<button type="submit" class="btn btn-success check_this">Авторизоваться</button>
					<button type="submit" class="btn btn-danger del_this">Удалить</button>
				</form>
			</div>
			<div class="col-md-6 col-s-12 box">
				
				<h5>base.txt <button type="button" class="btn btn-light refresh_doc"><span class="fa fa-refresh" aria-hidden="true"></span></button></h5>

				<table class="table" id="myTable">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Логин</th>
							<th scope="col">Пароль</th>
						</tr>
					</thead>
					<tbody>
						<?
						$ar=getBase();
						$i=1;
						foreach ($ar as $pair) {
							?>
							<tr>
								<th scope="row"><?=$i?></th>
								<td><?=$pair["login"]?></td>
								<td><?=$pair["password"]?></td>
							</tr>
							<?
							$i++;}?>
						</tbody>
					</table>
				</div>
			</div>


		</div>

		<script type="text/javascript">
			$('body').on('click', '.refresh_doc' ,function(event){
				event.stopPropagation(); // остановка всех текущих JS событий
				event.preventDefault();
				var data = new FormData();
				data.append( 'code', "refresh_doc" );
				// console.log("refresh");
				$.ajax({
					url         : 'ajax.php',
					type        : 'POST', // важно!
					data        : data,
					cache       : false,
					// dataType    : 'json',
					// отключаем обработку передаваемых данных, пусть передаются как есть
					processData : false,
					// отключаем установку заголовка типа запроса. Так jQuery скажет серверу что это строковой запрос
					contentType : false, 
					// функция успешного ответа сервера
					success     : function( respond, status, jqXHR ){
						console.log('refresh_success');						
						// console.log(respond);
						$('#myTable > tbody').html(respond);

					},
					// функция ошибки ответа сервера
					error: function( jqXHR, status, errorThrown ){
						console.log( 'ОШИБКА AJAX запроса: ' + status, jqXHR );
					}
				});

			});


			$('body').on('click', '.add_new' ,function(event){
				event.stopPropagation(); // остановка всех текущих JS событий
				event.preventDefault();
				var login=document.getElementById('login').value;
				var password=document.getElementById('password').value;
				var data = new FormData();
				data.append( 'login', login );
				data.append( 'password', password );
				data.append( 'code', "add_new" );

				$.ajax({
					url         : 'ajax.php',
					type        : 'POST', // важно!
					data        : data,
					cache       : false,
					// dataType    : 'json',
					// отключаем обработку передаваемых данных, пусть передаются как есть
					processData : false,
					// отключаем установку заголовка типа запроса. Так jQuery скажет серверу что это строковой запрос
					contentType : false, 
					// функция успешного ответа сервера
					success     : function( respond, status, jqXHR ){
						console.log('add_new');
						
						if(respond!='OK'){
							$("#comment").text(respond);
							$("#comment").removeClass('success');
							$("#comment").addClass('error');
						}
						else{
							// $("#comment").text(respond);
							$("#comment").text("");
							$("#comment").removeClass('error');
							// $("#comment").addClass('success');
							$('.refresh_doc').trigger('click');
							// $('#myTable > tbody:last-child').append('<tr><td><b>'+($("tbody").children('tr').length+1)+'</b></td><td>'+login+'</td><td>'+password[0]+'***'+password[password.length-1]+'</td></tr>');
						}
						console.log(respond);

					},
					// функция ошибки ответа сервера
					error: function( jqXHR, status, errorThrown ){
						console.log( 'ОШИБКА AJAX запроса: ' + status, jqXHR );
					}
				});

			});

			$('body').on('click', '.check_this' ,function(event){
				event.stopPropagation(); // остановка всех текущих JS событий
				event.preventDefault();
				var login=document.getElementById('login').value;
				var password=document.getElementById('password').value;
				var data = new FormData();
				data.append( 'login', login );
				data.append( 'password', password );
				data.append( 'code', "check_this" );

				$.ajax({
					url         : 'ajax.php',
					type        : 'POST', // важно!
					data        : data,
					cache       : false,
					// dataType    : 'json',
					// отключаем обработку передаваемых данных, пусть передаются как есть
					processData : false,
					// отключаем установку заголовка типа запроса. Так jQuery скажет серверу что это строковой запрос
					contentType : false, 
					// функция успешного ответа сервера
					success     : function( respond, status, jqXHR ){
						console.log('check_this');

						if(respond!='OK'){
							$("#comment").text(respond);
							$("#comment").removeClass('success');
							$("#comment").addClass('error');
						}
						else{
							// $("#comment").text(respond);
							$("#comment").text("Поздравляю. Вы успешно авторизировались!");
							$("#comment").removeClass('error');
							$("#comment").addClass('success');
							
						}
						console.log(respond);

					},
					// функция ошибки ответа сервера
					error: function( jqXHR, status, errorThrown ){
						console.log( 'ОШИБКА AJAX запроса: ' + status, jqXHR );
					}
				});

			});
			$('body').on('click', '.del_this' ,function(event){
				event.stopPropagation(); // остановка всех текущих JS событий
				event.preventDefault();
				var login=document.getElementById('login').value;
				var password=document.getElementById('password').value;
				var data = new FormData();
				data.append( 'login', login );
				data.append( 'password', password );
				data.append( 'code', "del_this" );

				$.ajax({
					url         : 'ajax.php',
					type        : 'POST', // важно!
					data        : data,
					cache       : false,
					// dataType    : 'json',
					// отключаем обработку передаваемых данных, пусть передаются как есть
					processData : false,
					// отключаем установку заголовка типа запроса. Так jQuery скажет серверу что это строковой запрос
					contentType : false, 
					// функция успешного ответа сервера
					success     : function( respond, status, jqXHR ){
						console.log('del_this');

						if(respond!='OK'){
							$("#comment").text(respond);
							$("#comment").removeClass('success');
							$("#comment").addClass('error');
						}
						else{
							// $("#comment").text(respond);
							$("#comment").text("Запись успешно удалена!");
							$("#comment").removeClass('error');
							$("#comment").addClass('success');
							$('.refresh_doc').trigger('click');
							
						}
						console.log(respond);

					},
					// функция ошибки ответа сервера
					error: function( jqXHR, status, errorThrown ){
						console.log( 'ОШИБКА AJAX запроса: ' + status, jqXHR );
					}
				});
			});

		</script>
	</body>
	</html>