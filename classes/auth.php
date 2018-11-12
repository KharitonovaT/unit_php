<?
class Auth
{
	// public function viewAll() {}
	private static $_entries = array(
		array ('login' => 'Kirk1','password' => 'password'),
		array ('login' => 'Tedy2','password' => 'password'));

	public function add( $login, $password ) {
		// $fd = fopen("../unit/base.txt", 'w') or die("не удалось создать файл");
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
						$str = preg_replace('/\s/', '', $login)."#".preg_replace('/\s/', '', $password)."\n";
					}
				}
			}
		}
		// fwrite($fd, $str);
		// file_put_contents("../unit/base.txt", $str, FILE_APPEND);
		
		file_put_contents("../base.txt", $str, FILE_APPEND);

	}

	public function viewAll() {
		// return self::$_entries; // Возвращаем взе записи из таблицы
		$names=file('../base.txt');
		$_entries=array();
		foreach($names as $name)
		{
			$rez["login"]=explode("#",$name)[0];
			$rez["password"]=explode("#",$name)[1];
			$_entries[]=$rez;
		}
		return($_entries);
	}


	public function check($login, $password) {}

	public function delete($login, $password) {}
}
