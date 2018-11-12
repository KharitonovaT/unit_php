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
		//print_r($entries);
		foreach ($entries as $entrie) {
				$this->assertFalse((strlen($entrie["login"])>10));//	Неудача, если $login = = true
				$this->assertPattern("/\w{4,10}/" , $entrie["login"]);	//Неудача, если регулярное выражение $p не соответствует $login
				$this->assertNoPattern("/\d{4,10}/" , $entrie["login"]);	//Неудача, если регулярное выражение $p соответствует $login//неудача если логин только числа

				$this->assertFalse((strlen($entrie["password"])>10));//	Неудача, если $password = = true
				$this->assertPattern("/[a-zA-Z0-9]{6,10}/" , $entrie["password"]);
			}
		}

		function testAuthCheck()
		{}

		function testAuthDelete()
		{}
	}
