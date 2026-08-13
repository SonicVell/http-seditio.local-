<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"]."/includes/config.inc.php");
include($_SERVER["DOCUMENT_ROOT"]."/includes/functions.php");

if (!empty($_POST)) {
	if (!(eregi("^[0-9a-zA-Z]+$", $_POST["user"]) || eregi("^[0-9а-яА-Я]+$", $_POST["user"]))) {
        $att = "Введите корректный логин (нельзя использовать специальные символы, точку, одновременно русские и латинские буквы).";
        $err=1;
    }
	$email = $_POST ["email"];
    if ($email == "" || (!eregi("^([0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-wyz][a-z](fo|g|l|m|mes|o|op|pa|ro|seum|t|u|v|z)?)$", $email))) {
        $att = "Введите корректный E-mail адрес.";
        $err=1;
    }
	if ($err<>1) {
		$row = mysql_fetch_assoc(mysql_query("SELECT `login`,`email` FROM `user` WHERE `login`='".htmlspecialchars($_POST['user'])."' and `email`='".htmlspecialchars($_POST['email'])."'"));
        if (empty($row)) {
            $att = "Неверное сочитание Логина или E-mail.";
            $err=1;
        }
    }
	if ($err != 1){
		$code=md5($row['login'].time());
		mysql_query("UPDATE `user` SET  `lostpass` =  '".$code."' WHERE `login`='".$row['login']."' and `email`='".$row['email']."'");
		send_mail($row['email'],'Восстановление пароля','Здравствуйте, '.$row['login'].'!<br><br>Вы пытаетесь восстановить пароль к Вашему персонажу.<br>Для подтверждения Вам необходимо перейти по ссылке:<br><a href="http://'.$_SERVER['HTTP_HOST'].'/index.php?lostcode='.$code.'" target="_blank">http://'.$_SERVER['HTTP_HOST'].'index.php?lostcode='.$code.'</a><br><br><br>С наилучшими пожеланиями,<br>Администрация игры Moon Light.<br>www.seditio.local<br><br>');
		$att='На указанный E-mail выслана информация для сброса пароля.';
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" id="html">
<head>
<title>Восстановление пароля</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<link rel="stylesheet" type="text/css" href="/css/default.css" />
<link rel="stylesheet" type="text/css" href="/css/themes/dark/theme.css" />
<!--[if lte IE 8]>
<link href="/css/ie.css" rel="stylesheet" type="text/css"/>
<![endif]-->
</head>
<body>
<div id="doc">
  <div id="content">
    <h3>Восстановление пароля</h3>
    <div class="block notify" style="margin-bottom:10px;display:none"></div>
	<?php
    if(!empty($att)){
		echo'<script>alert("'.$att.'");</script>';
	}
	?>
    <form action="" method="post">
      <table class="pstable" align="center">
        <tr>
          <td class="fieldname">Логин: </td>
          <td class="fieldinput"><div>
            <input class="edit text" type="text" name="user" value="" />
          </div></td>
        </tr>
        <tr>
          <td class="fieldname">E-mail: </td>
          <td class="fieldinput"><div>
            <input class="edit text" type="text" name="email" value="" />
          </div></td>
        </tr>
        <tr>
          <td colspan="2" style="text-align:center;"><input type="submit" class="button" value="Продолжить" /></td>
        </tr>
      </table>
    </form>
    <div id="notes" style="display: none"></div>
    <div id="info"> 1. Заполните форму.<br />
      2. Нажмите кнопку &quot;Продолжить&quot; для проверки данных.<br />
    </div>
  </div>
  <div id="footer">&copy; 2018-2019, Moon Light</div>
</div>
</body>
</html>