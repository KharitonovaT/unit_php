<?
require_once(dirname(__FILE__) . '/simpletest/autorun.php');
require_once('../classes/auth.php');

class TestAuth extends UnitTestCase {
	function testAuthAdd()
	{
		//добавление пары логин:пароль в файл (записать в файл/прочитать из него)
		//проеверка логина на корректность 1)такой есть/2)латиница+цифры3)4-20знаков  \w{4,20}
		//проверка пароля на корректность 1)длина[6-10] 2)латиница + верхний_регистр + нижний_регистр + цифры 3)недопустимые символы
		$auth = new Auth();
		$pairs= array(
			array ('login' => 'Hello123456789','password' => 'Password1'), //log-длинный pas-ok
			array ('login' => 'Hel','password' => 'Password1'),//log-короткий pas-ok
			array ('login' => 'абвгд','password' => 'Password1'),//log-короткий pas-ok
			array ('login' => '   ','password' => 'Password1'),//log-no pas-ok
			array ('login' => '123456','password' => 'Password1'),//log-no pas-ok
			array ('login' => 'mylogin1','password' => 'Password11254'),//log-ok pas-no
			array ('login' => 'mylogin1','password' => 'Pas1'),//log-ok pas-no
			array ('login' => 'mylogin1','password' => 'pasword'),//log-ok pas-no
			array ('login' => 'mylogin1','password' => '123123'),//log-ok pas-no
			array ('login' => 'mylogin1','password' => 'RELKI123'),//log-ok pas-no
			array ('login' => 'mylogin1','password' => 'jhgff23'),//log-ok pas-no
			array ('login' => 'mylogin1','password' => '   '),//log-ok pas-no
			array ('login' => 'mylogin1','password' => 'абвгА123'),//log-ok pas-no
			array ('login' => 'new_login','password' => 'Password1'),//log-ok pas-ok
			array ('login' => 'newlogin','password' => 'Password1'),//log-ok pas-ok
		);
		foreach ($pairs as $pair) {
			$auth->add($pair["login"],$pair["password"]);
		}
		$entries = $auth->viewAll();		
		foreach ($entries as $entrie) {
				$this->assertFalse((strlen($entrie["login"])>10));//	Неудача, если $login = = true
				$this->assertPattern("/\w{4,10}/" , $entrie["login"]);	//Неудача, если регулярное выражение $p не соответствует $login
				$this->assertNoPattern("/\d{4,10}/" , $entrie["login"]);	//Неудача, если регулярное выражение $p соответствует $login//неудача если логин только числа
				$this->assertFalse((strlen($entrie["password"])>10));//	Неудача, если $password = = true
				$this->assertPattern("/[a-zA-Z0-9]{6,10}/" , $entrie["password"]);
			}
		}

		function testAuthCheck()
		{
			//проверка существования пары логин:пароль в файле (прочитать из файла и сравнить)
				//проеверка логина на корректность 1)такой есть 2)латиница+цифры 3)4-20знаков
				//проверка пароля на корректность 1)длина[6-10] 2)латиница + верхний_регистр + нижний_регистр + цифры 3)недопустимые символы
				$auth = new Auth();
				//заполням базу корректными значениями логина и пароля
				$pairs= array(
					array ('login' => 'MyLogin1','password' => 'Password1'), //log-ok pas-ok
					array ('login' => 'MyLogin2','password' => 'Password2'),//log-ok pas-ok
					array ('login' => 'MyLogin3','password' => 'Password3'),//log-ok pas-ok
					array ('login' => 'MyLogin4','password' => 'Password4'),//log-ok pas-ok
					array ('login' => 'MyLogin5','password' => 'Password5'),//log-ok pas-ok
				);
				foreach ($pairs as $pair) {
					$auth->add($pair["login"],$pair["password"]);
				}
				// $entries = $auth->viewAll();

				//получаем массив пар под которыми будем производить авторизцию
				$check_pairs= array(
					array ('login' => 'MyLogin3','password' => 'Password2'), //Auth false, неверный пароль (пароль от другого логина)
					array ('login' => 'MyLogin1','password' => 'Pas123'), //Auth false, неверный пароль (такого пароля в базе нет вообще)
					array ('login' => 'MyLogin22','password' => 'Password2'),//Auth false, логина такого нет
				);

				foreach ($check_pairs as $value) {
					$rez_check=true;
					$responce=$auth->check($value['login'],$value['password']);
					if($responce=='Неверный логин'){
						$rez_check=false;
					}
					if($responce=='Неверный пароль'){
						$rez_check=false;
					}
					if($responce=='Ок'){
						$rez_check=true;
					}
					$this->assertFalse($rez_check);//Неудача если $rez_check==true
				}

				//получаем массив пар под которыми будем производить авторизцию
				$check_pairs2= array(
					array ('login' => 'MyLogin3','password' => 'Password3'),//Auth true
					array ('login' => 'MyLogin4','password' => 'Password4'),//Auth true
					array ('login' => 'MyLogin5','password' => 'Password5'),//Auth true
				);

				foreach ($check_pairs2 as $value) {
					$rez_check=false;
					$responce=$auth->check($value['login'],$value['password']);
					if($responce=='Ок'){
						$rez_check=true;
					}
					$this->assertTrue($rez_check);//Неудача если $rez_check==false
				}
		}

		function testAuthDelete()
		{
			$auth = new Auth();
				//проверка работы метода
				$pairs= array(
					array ('login' => 'MyLogin3','password' => 'Password2'), //Delete false, неверный пароль (пароль от другого логина)
					array ('login' => 'MyLogin1','password' => 'Pas123'), //Delete false, неверный пароль (такого пароля в базе нет вообще)
					array ('login' => 'MyLogin22','password' => 'Password2'),//Delete false, логина такого нет
				);
				foreach ($pairs as $value) {
					$rez_del=$auth->delete($value['login'],$value['password']);
					$this->assertFalse($rez_del);//Неудача если $rez_del==true
				}
				$pairs2= array(
					array ('login' => 'MyLogin3','password' => 'Password3'),//Delete true
					array ('login' => 'MyLogin4','password' => 'Password4'),//Delete true
					array ('login' => 'MyLogin5','password' => 'Password5'),//Delete true
				);
				foreach ($pairs2 as $value) {
					$rez_del=$auth->delete($value['login'],$value['password']);
					$this->assertTrue($rez_del);//Неудача если $rez_del==false
				}
				//проверка реально ли удалены значения
				foreach ($pairs2 as $value) {
					$real_del=false;
					$responce=$auth->check($value['login'],$value['password']);
					if($responce!='Ок'){
						$real_del=true;
					}
					$this->assertTrue($real_del);//Неудача если $real_del==false
				}
		}
	}
