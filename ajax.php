<?require_once('classes/auth.php');
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


if($_POST["code"]=="add_new"){
	$auth = new Auth();
	$str=$auth->add($_POST["login"],$_POST["password"]);
	print_r($str);
}
if($_POST["code"]=="check_this"){
	$auth = new Auth();
	$str=$auth->check($_POST["login"],$_POST["password"]);
	print_r($str);
}
if($_POST["code"]=="del_this"){
	$auth = new Auth();
	$rez=$auth->delete($_POST["login"],$_POST["password"]);
	if($rez==true){
		// print_r("Запись успешно удалена");
		$str="OK";
	}
	else{
		$str="Неверная пара логин:пароль";
	}
	print_r($str);
}
if($_POST["code"]=="refresh_doc"){
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
		$i++;}
	}

	?>
