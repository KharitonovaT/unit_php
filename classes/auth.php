<?
class Auth
{
	private static $_entries = array(
		array ('login' => 'Kirk1','password' => 'password'),
		array ('login' => 'Tedy2','password' => 'password'));

	public function add( $login, $password ) {
	}

	public function viewAll() {
		return self::$_entries; // Возвращаем взе записи из таблицы
	
	}
	public function check($login, $password) {
	}

	public function delete($login, $password) {
	}
}
