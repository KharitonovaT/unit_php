<?
class Auth
{
	private static $_entries = array(/*
		array ('login' => 'Kirk1','password' => 'password'),
	array ('login' => 'Tedy2','password' => 'password')*/);
	public static $_path="../unit_php/base.txt";
	// public static $_path="../base.txt";
	// функция добавляет пару логин пароль соответствующую условиям в файл
	public function add( $login, $password ) {
		$er='';		
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
						//без хэширования пароля
						// self::$_entries[] = array('login' => preg_replace('/\s/', '', $login), 'password' => preg_replace('/\s/', '', $password  ) );
						// $str = preg_replace('/\s/', '', $login)."#".preg_replace('/\s/', '', $password)."\n";

						//с хэшированием пароля
						self::$_entries[] = array('login' => preg_replace('/\s/', '', $login), 'password' => preg_replace('/\s/', '', md5($password)  ) );
						$str = preg_replace('/\s/', '', $login)."#".preg_replace('/\s/', '', md5($password))."\n";
						// print_r($str."<br>");
					}
					else{
						$er="Некорректный пароль";
					}
				}
				else{
					$er="Некорректный логин";
				}
				
			}
			else{
				$er="Длина логина или пароля некорректна";
			}

		}
		else{
			$er="такой существует";
		}
		if($er==''){
			$er="OK";
		}	
		file_put_contents(self::$_path, $str, FILE_APPEND);
		return($er);

	}

	// функция возвращает массив пар из файла
	public function viewAll() {
		$names=file(self::$_path);
		$_entries=array();
		foreach($names as $name)
		{
			$rez["login"]=explode("#",$name)[0];
			$rez["password"]=explode("#",$name)[1];
			$_entries[]=$rez;
		}
		return($_entries);
	}

	//функция проверяет соответствие пары логин-пароль
	public function check($login, $password) {
		$ent=self::viewAll();
		$err="";
		foreach ($ent as $value) {
			if($value["login"]==$login){
				// if(trim($value["password"])==$password){//без хэширования пароля
				if(trim($value["password"])==md5($password)){//с хэшированием пароля
					$err="OK";
				}
				else{
					$err="Неверный пароль";
				}
			}
		}
		if($err==""){
			$err="Неверный логин";
		}
		return($err);
	}

	//функция удаляет пару логин пароль
	public function delete($login, $password) {
		$ent=self::viewAll();
		$del=false;
		$id=0;
		foreach ($ent as $value) {
			$id++;
			if($value["login"]==$login){
				// if(trim($value["password"])==$password){//без хэширования пароля
				if(trim($value["password"])==md5($password)){//с хэшированием пароля
					self::del($id);//если проверка пройдена удаляем из файла
					$del=true;
				}
			}
		}
		return($del);
	}
	//функция удаляет из файла по номеру строки
	public function del($id){
		if ($id != "") {
			$id--;
			$file=file(self::$_path);

			for($i=0;$i<sizeof($file);$i++)
				if($i==$id) unset($file[$i]);

			$fp=fopen(self::$_path,"w");
			fputs($fp,implode("",$file));
			fclose($fp);
		}
	}
}
