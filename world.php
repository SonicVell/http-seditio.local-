<?php
session_start();
error_reporting(0);
include($_SERVER["DOCUMENT_ROOT"]."/func/connect.php");
include($_SERVER["DOCUMENT_ROOT"]."/func/sql_func.php");
include($_SERVER["DOCUMENT_ROOT"]."/inc/bbcodes.inc.php");
##################### Фак SQL атакам ################
include($_SERVER["DOCUMENT_ROOT"]."/includes/sql.php");
$stop_injection = new InitVars();
$stop_injection->checkVars();
##################### Фак SQL атакам ################

switch($_GET['act']){
	default:
		$PLAY = db_quer('user','login = "'.mysqli_real_escape_string($GLOBALS['db_link'],strip_tags($_POST['player_nick'])).'" and pass = "'.md5($_POST['player_password']).'" LIMIT 1;');
	break;
}

function GetUserIDtoAutch($user){
	$user = mysqli_fetch_assoc(mysqli_query($GLOBALS['db_link'],"SELECT `id` FROM `user` WHERE `login` = '".mysqli_real_escape_string($GLOBALS['db_link'],$user)."'"));
	return $user['id'];
}

if(empty($_POST) or ($_POST['player_nick']=='Логин' and $_POST['player_password']=='Пароль')) {
	header("Location: /?msg=noinf");
	exit;
}
if(!isset($PLAY['id'])) {
	if(GetUserIDtoAutch($_POST['player_nick'])!=''){
		pvu_logs(GetUserIDtoAutch($_POST['player_nick']),"1","|1|".getIP());
	}
	log_write("err: пароль",'','','',1);
	header("Location: /index.php?msg=login");
	exit;
}
if(!empty($PLAY['block'])) {
	header("Location: /index.php?msg=block");
	exit;
}


$lch=mysqli_result(mysqli_query($GLOBALS['db_link'],"SELECT MAX(id) FROM chat LIMIT 1;"),0);
//--------заполняем сессии переменными----
$uin = md5(uniqid(rand(0,1000000000)));
setcookie("social","");
setcookie("Hash", $PLAY['pass'],time()+86400, "", ".seditio.local");
/*
if($PLAY['flash'] != '0'){
	include("includes/windows/FlashPassword.php");
	exit;
}
*/
setcookie('UID',$uin,time()+86400);
setcookie("Puid", $PLAY['id'],time()+86400, "", ".seditio.local");

$_SESSION['ignor'][] = '';
$_SESSION['user'] = array(
    "login"  => $PLAY["login"],
    "filt"   => $PLAY["filt"],
    "on_time"=> time()+200,
    "chcolor"=> $PLAY["chcolor"],
    "sh"=> "",
    "ft"=> "",
    "wait"=> 0,
    "pos"=>0,
    "lastch"=>$lch,
    "uin"=>$uin,
    "inv"=>'',
	"pchange"=> $PLAY['pass']
);
//------------------------------------------
//--------пишем куки-----------

if($PLAY['last'] == '0'){
    mysqli_query($GLOBALS['db_link'],"INSERT INTO `chat` (`time`,`login`,`msg`) VALUES ('".time()."','sys','".addslashes("parent.frames['chmain'].add_msg('<font class=massm>&nbsp;Moon Light&nbsp;</font> <font color=000000>В свет наших земель вышел будущий герой, <a href=\"#\"><img src=/image/chat/private.gif width=11 height=12 border=0 align=absmiddle onClick=\"parent.say_private(\'".$PLAY["login"]."\')\"></a><b>".$PLAY["login"]."</b>[0]<a style=\"COLOR: #336699;text-decoration : none;cursor: pointer;\" href=\"/ipers.php?".$PLAY["login"]."\" target=\"_blank\"><img src=/image/chat/info.gif onClick=\"window.open(\'http://seditio.local/ipers.php?".$PLAY["login"]."\');\" width=11 height=12 border=0 align=absmiddle></a>. Желаем увлекательного прибывания в нашем мире.</font><BR>'+'');")."');");
}

if($PLAY['access'] == 'admins'){
	mysqli_query($GLOBALS['db_link'],"INSERT INTO `chat` (`time`,`login`,`msg`) VALUES ('".time()."','sys','".addslashes("top.frames['chmain'].add_msg('<font class=massm>&nbsp;Moon Light&nbsp;</font> <font color=000000>Я на месте <a href=\"#\"><img src=/image/chat/private.gif width=11 height=12 border=0 align=absmiddle onClick=\"top.say_private(\'".$PLAY["login"]."\')\"></a><b>".$PLAY["login"]."</b><a style=\"COLOR: #336699;text-decoration : none;cursor: pointer;\" href=\"/ipers.php?".$PLAY["login"]."\" target=\"_blank\"><img src=http://seditio.local/image/chat/info.gif onClick=\"window.open(\'http://seditio.local/ipers.php?".$PLAY["login"]."\');\" width=11 height=12 border=0 align=absmiddle></a>. Все вопросы в приват :575:</font><BR>'+'');")."');");
}

if($PLAY['clan_id'] == '777'){
	mysqli_query($GLOBALS['db_link'],"INSERT INTO `chat` (`time`,`login`,`msg`) VALUES ('".time()."','sys','".addslashes("top.frames['chmain'].add_msg('<font class=massm>&nbsp;Moon Light&nbsp;</font> <font color=000000>В игру вошел Представитель Власти, <a href=\"#\"><img src=/image/chat/private.gif width=11 height=12 border=0 align=absmiddle onClick=\"top.say_private(\'".$PLAY["login"]."\')\"></a><b>".$PLAY["login"]."</b><a style=\"COLOR: #336699;text-decoration : none;cursor: pointer;\" href=\"/ipers.php?".$PLAY["login"]."\" target=\"_blank\"><img src=http://seditio.local/image/chat/info.gif onClick=\"window.open(\'http://seditio.local/ipers.php?".$PLAY["login"]."\');\" width=11 height=12 border=0 align=absmiddle></a>. Поможет по любому воросу игры.</font><BR>'+'');")."');");
}
if($PLAY['autoobnul'] == 0){
    obnul_pl($PLAY);
    mysqli_query($GLOBALS['db_link'],"UPDATE `user` SET `obnul`=`obnul`+'1',`autoobnul`='1' WHERE `id`='".$PLAY['id']."' LIMIT 1;");
}

pvu_logs($PLAY['id'],"1","|0|".getIP());

mysqli_query($GLOBALS['db_link'],"UPDATE `user` SET `lastip` = ip,`ip`='".getIP()."',`sig`='',`pcid`='".$uin."',`last`='".time()."' WHERE `id` = '".$PLAY['id']."'");

log_write("вход в игру",'','','',1);


echo'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" id="html">
<head>
<TITLE>Moon Lighting - ('.$PLAY['login'].') «Лунный Свет». Браузерная MMORPG №1</TITLE>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta http-equiv="Cache-Control" content="No-Cache" />
<meta http-equiv="Pragma" content="No-Cache" />
<meta http-equiv="Expires" content="0" />
<link rel="stylesheet" type="text/css" href="/css/themes/jquery.jgrowl.css" />
<link rel="stylesheet" type="text/css" href="/css/themes/default/game.css" />
<link rel="stylesheet" type="text/css" href="/css/themes/smodal.css" />
<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/js/jquery.jgrowl.js"></script>
<script type="text/javascript" src="/js/jquery.smodal.js"></script>
<script type="text/javascript" src="/js/jquery.game.js"></script>
<script type="text/javascript" src="/js/AutoBot.js?v1"></script>
<script type="text/javascript" src="/js/png.js"></script>
<script type="text/javascript" src="/js/functions.js?v3"></script>
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="/css/themes/smodal_ie.css" />
<![endif]-->
</head>
<body style="height: 100%;">
<!-- preloading content -->

    <script type="text/javascript">
        $(document).ready(function(){
            view_loading();
            window.onresize = change_chatsize;
        });
        jAlert = function(text){
            $("#alert").jGrowl(text);
        }
    </script>
</div>

<!-- game content -->
<div id="ViewFrames">&nbsp;</div>

<!-- jGrowl content -->
<div id="alert" class="jGrowl bottom-right"></div>

<!-- modal content -->
<div id="basic-modal-content">
</div>

<!-- preload the images -->
<div style="display:none">
    <img src="http://seditio.local/image/x.png" alt="" />
</div>
</body>
</html>';