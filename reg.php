<?php
 session_start();
error_reporting(0);
require_once ($_SERVER["DOCUMENT_ROOT"]."/func/connect.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/func/sql_func.php");
db_open();

 if($reg==1){
//--------------------Record-----------------------
$sdate=date("d.m.Y");
$stime=date("H:i:s");
$nickname=trim($nickname);
$nickname=addslashes($nickname);
     if(!empty($nickname)){
	 if (testchr($nickname) == 1) {  $msg = "<span class=\"redtitle_st\"><strong>Ошибка! Логин содержит недопустимые смиволы!</strong></span><BR>";$err=1;}
	 $CHECK = db_quer('user','login="'.$nickname.'"');
	 if($CHECK){ $msg= "<span class=\"redtitle_st\"><strong>Ошибка! Логин \"$nickname\" уже занят!</strong></span><BR>";$err=1;}
	  if(empty($name)){$msg= "<span class=\"redtitle_st\"><strong>Ошибка! Введите имя.</strong></span><BR>";$err=1;}
	  if($reg_code!=$_SESSION["randomize"]){$msg= "<span class=\"redtitle_st\"><strong>Ошибка! Неверно введен защитный код.</strong></span><BR>";$err=1;}
	  if(empty($country)){$msg= "<span class=\"redtitle_st\"><strong>Ошибка! Введите страну.</strong></span><BR>";$err=1;}
	  if(empty($city)){$msg= "<span class=\"redtitle_st\"><strong>Ошибка! Введите город.</strong></span><BR>";$err=1;}
	  if($bday =="n" or $bmouth=="n" or $byear=="n"){$msg= "<span class=\"redtitle_st\"><strong>Ошибка! Введите дату рождения.</strong></span><BR>";$err=1;}
	  if($sex=="n"){$msg= "<span class=\"redtitle_st\"><strong>Ошибка! Укажите свой пол.</strong></span><BR>";$err=1;}
	  if($bmonth=="2"){if($bday =="31" or $bday =="30" ){$msg= "<span class=\"redtitle_st\"><strong>Ошибка! Такой даты не существует.</strong></span><BR>";$err=1;}}
	  if($agree!="ok"){$msg= "<span class=\"redtitle_st\"><strong>Ошибка! Примите пользовательское соглашение.</strong></span><BR>";$err=1;}
	  if (strlen($psw_f)<=7){$msg= "<span class=\"redtitle_st\"><strong>Ошибка! Пароль меньше 8 знаков.</strong></span><BR>";$err=1;}else{if($psw_f!=$psw_s){$msg= "<span class=\"redtitle_st\"><strong>Ошибка! Пароли не совпадают.</strong></span><BR>";$err=1;}}

}else{$msg = "<span class=\"redtitle_st\"><strong>Ошибка! Вы не указали логин!</strong></span><BR>";$err=1;}
if($err!=1){
    if(empty($ip)){if (getenv('HTTP_X_FORWARDED_FOR')){$ip=getenv('HTTP_X_FORWARDED_FOR');}else{$ip=getenv('REMOTE_ADDR');}}
	$nickname=htmlspecialchars($nickname);
    $pass=md5(htmlspecialchars($psw_f));
    $email=htmlspecialchars($email);
    $name=htmlspecialchars($name);
    $city=htmlspecialchars($city);
	$country=htmlspecialchars($country);
	if ($bday < 10){$bday = "0".$bday;}
	if ($bmonth < 10){$bmonth = "0".$bmonth;}
	$birst = htmlspecialchars($bday.".".$bmonth.".".$byear);
 	$thotem=rand(0,11);

	
	send_mail($email,'Регистрация нового пользователя','Здравствуйте, '.$nickname.'!<br />Вы успешно зарегистрировались в проекте seditio.local.<br />С наилучшими пожеланиями,<br />Администрация проекта seditio.local.<br />');
	mysqli_query($GLOBALS['db_link'],'INSERT INTO user(login,pass,email,name,country,city,bday,sex,thotem,bdaypers,ip,obraz) VALUES ('.AP.$nickname.AP.','.AP.$pass.AP.','.AP.$email.AP.','.AP.$name.AP.','.AP.$country.AP.','.AP.$city.AP.','.AP.$birst.AP.','.AP.$sex.AP.','.AP.$thotem.AP.','.AP.time().AP.','.AP.$ip.AP.','.AP.$sex.".gif".AP.')');
	$id = mysqli_fetch_assoc(mysqli_query($GLOBALS['db_link'],"SELECT user.id, user.login FROM user WHERE user.login='".$nickname."' LIMIT 1;"));
 if($_SESSION['referal_id'] and $_SESSION['referal']){
		mysqli_query($GLOBALS['db_link'],"INSERT INTO `ref_system` (`who_id`,`who_login`,`ref_id`,`ref_login`,`time`) VALUES ('".$_SESSION['referal_id']."','".$_SESSION['referal']."','".$id['id']."','".$id['login']."','".time()."');");
	}
	// Говорим что все ок, и завершаем регу
	exit(header("Location: /index.php"));
}

}

?>
<HTML><HEAD><title>Moon Light - Регистрация</title><LINK href=css/game.css rel=STYLESHEET type=text/css><SCRIPT SRC='/js/reg.js'></SCRIPT></HEAD><BODY bgColor=#FFFFFF link=#336699 alink=#336699 vlink=#336699>
<FORM action=reg.php method=POST><input type=hidden name=reg value=1>
<table width=100% border=0 align=center cellpadding=1 cellspacing=0>
<tr><td bgcolor=#CCCCCC><table width=100% border=0 align=center cellpadding=1 cellspacing=0><tr><td bgcolor=#ffffff><table width=100% border=0 align=center cellpadding=2 cellspacing=0>
<tr><td bgcolor=#FCFAF3 colspan=2><div align=center><font color=#3564A5><img src="/image/rega.png" width=500 height=180 border=0 /></div></td></tr>
<tr><td bgcolor=#FCFAF3 colspan=2><div align=center><b><font class=forumwe>[Регистрация нового игрока в мире Moon Light]<?=$_SESSION['referal']?'<br>Вас пригласил: '.$_SESSION['referal']:'';?></b></font></div><? echo $msg;?> <font class=weaponch><b>Важно!</b> Иметь более одного персонажа запрещено.</td></tr>
<tr><td bgcolor=#FCFAF3 valign=top><font class=weaponch><b>Никнейм</b> (Логин) <font color=#cc0000>*</font></b></td><td bgcolor=#FCFAF3 valign=top><input name=nickname value="<?=$nickname?>" type=text class=LogintextBox4 maxlength=20></td></tr>
<tr><td bgcolor=#FCFAF3 valign=top><font class=weaponch><b>Пароль</b> <font color=#cc0000>*</font></b><br>минимум 8 символов</td><td bgcolor=#FCFAF3 valign=top><input name=psw_f type=password class=LogintextBox4 maxlength=30></td></tr>
<tr><td bgcolor=#FCFAF3 valign=top><font class=weaponch><b>Пароль повторно</b> <font color=#cc0000>*</font></b></td><td bgcolor=#FCFAF3 valign=top><input name=psw_s type=password class=LogintextBox4 maxlength=30></td></tr>
<tr><td bgcolor=#FCFAF3 valign=top><font class=weaponch><b>Ваш e-mail</b> <font color=#cc0000>*</font></td><td bgcolor=#FCFAF3 valign=top><input name=email value="<?=$email?>" type=text class=LogintextBox4 maxlength=30></td></tr>
<tr><td bgcolor=#FCFAF3 colspan=2><font class=weaponch>Обязательно вводите реальный e-mail. Доставку писем на бесплатные ящики в зоне mail.ru,yandex.ru и т.д. не гарантируем.</td></tr>
<tr><td bgcolor=#FCFAF3 valign=top><font class=weaponch><b>Ваше имя</b> <font color=#cc0000>*</font></td><td bgcolor=#FCFAF3 valign=top><input name=name type=text value="<?=$name?>" class=LogintextBox4 maxlength=30></td></tr>
<tr><td bgcolor=#FCFAF3 valign=top><font class=weaponch><b>Страна</b> <font color=#cc0000>*</font></td><td bgcolor=#FCFAF3 valign=top><input name=country type=text value="<?=$country?>" class=LogintextBox4 maxlength=20></td></tr>
<tr><td bgcolor=#FCFAF3 valign=top><font class=weaponch><b>Город</b> <font color=#cc0000>*</font></td><td bgcolor=#FCFAF3 valign=top><input name=city type=text value="<?=$city?>" class=LogintextBox4 maxlength=20></td></tr>
<tr><td bgcolor=#FCFAF3 valign=top><font class=weaponch><b>Дата рождения (реальная)</b> <font color=#cc0000>*</font></td><td bgcolor=#FCFAF3 valign=top><select name=bday class=LogintextBox6><option value=n SELECTED></option><option value=1> 01 </option><option value=2> 02 </option><option value=3> 03 </option><option value=4> 04 </option><option value=5> 05 </option><option value=6> 06 </option><option value=7> 07 </option><option value=8> 08 </option><option value=9> 09 </option><option value=10> 10 </option><option value=11> 11 </option><option value=12> 12 </option><option value=13> 13 </option><option value=14> 14 </option><option value=15> 15 </option><option value=16> 16 </option><option value=17> 17 </option><option value=18> 18 </option><option value=19> 19 </option><option value=20> 20 </option><option value=21> 21 </option><option value=22> 22 </option><option value=23> 23 </option><option value=24> 24 </option><option value=25> 25 </option><option value=26> 26 </option><option value=27> 27 </option><option value=28> 28 </option><option value=29> 29 </option><option value=30> 30 </option><option value=31> 31 </option></select><select name=bmonth class=LogintextBox6><option value=n SELECTED></option><option value=1> 01 </option><option value=2> 02 </option><option value=3> 03 </option><option value=4> 04 </option><option value=5> 05 </option><option value=6> 06 </option><option value=7> 07 </option><option value=8> 08 </option><option value=9> 09 </option><option value=10> 10 </option><option value=11> 11 </option><option value=12> 12 </option></select><select name=byear class=LogintextBox6><option value=n SELECTED></option><option value=1950> 1950 </option><option value=1951> 1951 </option><option value=1952> 1952 </option><option value=1953> 1953 </option><option value=1954> 1954 </option><option value=1955> 1955 </option><option value=1956> 1956 </option><option value=1957> 1957 </option><option value=1958> 1958 </option><option value=1959> 1959 </option><option value=1960> 1960 </option><option value=1961> 1961 </option><option value=1962> 1962 </option><option value=1963> 1963 </option><option value=1964> 1964 </option><option value=1965> 1965 </option><option value=1966> 1966 </option><option value=1967> 1967 </option><option value=1968> 1968 </option><option value=1969> 1969 </option><option value=1970> 1970 </option><option value=1971> 1971 </option><option value=1972> 1972 </option><option value=1973> 1973 </option><option value=1974> 1974 </option><option value=1975> 1975 </option><option value=1976> 1976 </option><option value=1977> 1977 </option><option value=1978> 1978 </option><option value=1979> 1979 </option><option value=1980> 1980 </option><option value=1981> 1981 </option><option value=1982> 1982 </option><option value=1983> 1983 </option><option value=1984> 1984 </option><option value=1985> 1985 </option><option value=1986> 1986 </option><option value=1987> 1987 </option><option value=1988> 1988 </option><option value=1989> 1989 </option><option value=1990> 1990 </option><option value=1991> 1991 </option><option value=1992> 1992 </option><option value=1993> 1993 </option><option value=1994> 1994 </option><option value=1995> 1995 </option><option value=1996> 1996 </option><option value=1997> 1997 </option><option value=1998> 1998 </option><option value=1999> 1999 </option><option value=2000> 2000 </option><option value=2001> 2001 </option><option value=2002> 2002 </option><option value=2003> 2003 </option></select></td></tr>
<tr><td bgcolor=#FCFAF3 valign=top><font class=weaponch><b>Ваш пол</b> <font color=#cc0000>*</font></td><td bgcolor=#FCFAF3 valign=top><select name=sex class=LogintextBox4><option value=n>Выберите</option><option value=male>-- Мужской</option><option value=female>-- Женский</option></select></td></tr>
<tr><td bgcolor=#FCFAF3></td><td bgcolor=#FCFAF3><img src="/func/scode.php" alt="Защитный код"><br><input type=hidden value=5474576874948de7012d1b name=hash><input type=hidden name=regtime value=1229512304></td></tr>
<tr><td bgcolor=#FCFAF3 valign=top><font class=weaponch><b>Код</b> <font color=#cc0000>*</font><br>
Введите 5-значный код, который указан на картинке выше.</td><td bgcolor=#FCFAF3 valign=top><input name=reg_code type=text class=LogintextBox4 maxlength=10></td></tr>


<tr><td bgcolor=#FCFAF3 valign=top><font class=weaponch><b>Я принимаю условия соглашения</b> <font color=#cc0000>*</font></td><td bgcolor=#FCFAF3 valign=top><font class=weaponch><input type=checkbox name=agree value=ok CHECKED> <a href="javascript:helpwin('help_2_0.html')">Законы и Соглашение Moon Light</a></td></tr>
<tr><td bgcolor=#FCFAF3 colspan=2><div align=center><font class=forumwe><input type=image src=image/newreg.gif width=139 height=15 border=0></div></td></tr>
</table></td></tr></table></td></tr></form></table>
<tr><td bgcolor=#FCFAF3 colspan=2><div align=center><font class=forumwe>
<!--<input type=image src=image/newreg.gif width=139 height=15 border=0>-->
</div></td></tr>
<div align=center><font class=fsm><img src="/image/1x1.gif" width=1 height=5 border=0><br>&copy; Copyright 2019-2019, <b>seditio.local</b>. Все права защищены.</font><br><img src="/img.seditio.local/image/1x1.gif" width=1 height=5 border=0><br>

<SCRIPT>
//counterview('null');
</SCRIPT>
</div>
</BODY>
</HTML>

