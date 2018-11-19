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
						$str = preg_replace('/\s/', '', $login)."#".preg_replace('/\s/', '', $password)."\n";
						// print_r($str."<br>");
					}
				}
			}
		}	
		file_put_contents("../base.txt", $str, FILE_APPEND);

	}

	public function viewAll() {
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


	public function check($login, $password) {
		$ent=self::viewAll();
		$err="";
		foreach ($ent as $value) {
			if($value["login"]==$login){
				if(trim($value["password"])==$password){
					$err="Ок";
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

	public function delete($login, $password) {
		$ent=self::viewAll();
		$del=false;
		$id=0;
		foreach ($ent as $value) {
			$id++;
			if($value["login"]==$login){
				if(trim($value["password"])==$password){
					self::del($id);
					$del=true;
				}
			}
		}
		return($del);
	}
	public function del($id){
		if ($id != "") {
			$id--;
			$file=file("../base.txt");

			for($i=0;$i<sizeof($file);$i++)
				if($i==$id) unset($file[$i]);

			$fp=fopen("../base.txt","w");
			fputs($fp,implode("",$file));
			fclose($fp);
		}
	}
}
