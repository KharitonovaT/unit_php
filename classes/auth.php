<?
class Auth
{
	private static $_entries = array(
		array ('login' => 'Kirk1','password' => 'password'),
		array ('login' => 'Tedy2','password' => 'password'));

	public function add( $login, $password ) {
		$ent=self::viewAll();
		$exist=false;
		foreach ($ent as $key => $value) {
			if($value["login"]==$login){
				$exist=true;
			}
		}
		if($exist==false){
			if (strlen($login)<10 && strlen($login)>4 && strlen($password)<10 && strlen($password)>6 ) {
				if(preg_match("/\w{4,10}/",$login) && !preg_match("/\d{4,10}/",$login)){
					if(preg_match("/(?=[-_a-zA-Z0-9]*?[A-Z])(?=[-_a-zA-Z0-9]*?[a-z])(?=[-_a-zA-Z0-9]*?[0-9])[-_a-zA-Z0-9]{6,10}/", $password)){
						self::$_entries[] = array('login' => preg_replace('/\s/', '', $login), 'password' => preg_replace('/\s/', '', $password) );
					}
				}
			}
		}
	}

	public function viewAll() {
		return self::$_entries; // Возвращаем взе записи из таблицы
	
	}
	public function check($login, $password) {
	}

	public function delete($login, $password) {
	}
}
