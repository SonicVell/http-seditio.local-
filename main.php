<?php
session_start ();
error_reporting(0);
$v=time()+microtime();
require_once ($_SERVER["DOCUMENT_ROOT"]."/func/connect.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/func/sql_func.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/inc/bbcodes.inc.php");
db_open();
$player=player();

## DLR logs by alexs
if($_SESSION['ddpl']==''){$_SESSION['ddpl'] = $player['dd'];}
if($_SESSION['bakspl']==''){$_SESSION['bakspl'] = $player['baks'];}
if($_SESSION['lrpl']==''){$_SESSION['lrpl'] = $player['nv'];}
## проверяем значения
	$typetolog = '0'; $abouttolog = '0';  # переменные для логов: первая всегда 0
	if($_SESSION['ddpl']!=$player['dd']){
		$typetolog .= '@3';  
		$abouttolog .= '@старое:  <b><font color=#004BBB>'.$_SESSION['ddpl'].'</font></b> | новое: <b><font color=#CC0000>'.$player['dd'].'</font></b>';
		$_SESSION['ddpl']=$player['dd'];
	}
	if($_SESSION['bakspl']!=$player['baks']){
		$typetolog .= '@4';  
		$abouttolog .= '@старое: <b><font color=#004BBB>'.$_SESSION['bakspl'].'</font></b> | новое: <b><font color=#CC0000>'.$player['baks'].'</font></b>';
		$_SESSION['bakspl']=$player['baks'];
	}
	/*if($_SESSION['lrpl']!=$player['nv']){
		$typetolog .= '@4';  
		$abouttolog .= '@старое: <b><font color=#004BBB>'.$_SESSION['lrpl'].'</font></b> | новое: <b><font color=#CC0000>'.$player['nv'].'</font></b>';
		$_SESSION['lrpl']=$player['nv'];
	}	*/	
	# пишем в лог все что произошло	
		if($typetolog!='0' and $abouttolog!='0'){
			player_actions($player['id'],$typetolog,$abouttolog);
		}
	# 
##
if($_SESSION['user']['pchange']==''){$_SESSION['user']['pchange']=$player['pass'];}
if($_SESSION['user']['pchange']!=$player['pass']){
	log_write("WARNING","md5_old: [".$_SESSION['user']['pchange']."] | md5_new: [".$player['pass']."]",0);
}
if($player['mov']==1){
	$_SESSION['user']['pos']=3;
	unset($_SESSION['secur']);
	mysqli_query($GLOBALS['db_link'],"UPDATE `user` SET `mov`=DEFAULT WHERE `id`='".$player['id']."' LIMIT 1;");
}
$check = mysqli_fetch_array(mysqli_query($GLOBALS['db_link'],"SELECT * FROM `blocklist` WHERE `ip`='".$player['ip']."' LIMIT 1;"));
$check2 = mysqli_fetch_array(mysqli_query($GLOBALS['db_link'],"SELECT * FROM `blocklist` WHERE `ip`='".$player['lastip']."' LIMIT 1;"));
if(!preg_match("/{$HTTP_HOST}/" ,getenv ('HTTP_REFERER' )) or $_SESSION['user']['login']=='' or $_COOKIE['UID']!=$player['pcid'] or $player['block']!='' or $check['id'] or $check2['id']){
if($player['block']!=''){echo "<script>parent.location = 'index.php?act=logout';</script>";}else{echo "<script>parent.location = 'error.php';</script>";}}
if($player['bank_bonus']==0){$rndlr=rand(1,5)/100;mysqli_query($GLOBALS['db_link'],"UPDATE `user` SET `bank_bonus`='1',`dd`=`dd`+".$rndlr." WHERE id=".$player['id']." LIMIT 1;");}
if(isset($_POST['newabout']) and $_POST['post_id']==49){
	$tmptext = $_POST['newabout'];
}
if(isset($_POST['opass']) and isset($_POST['npass']) and isset($_POST['vpass']) and $_POST['post_id']==49){
$tmpopass=$_POST['opass'];
$tmpvpass=$_POST['vpass'];
$tmpnpass=$_POST['npass'];
}
foreach($_POST as $keypost=>$valp){
	$valp = varcheck($valp);
	$_POST[$keypost] = $valp;
	$$keypost = $valp;
}
foreach($_GET as $keyget=>$valg){
	$valg = varcheck($valg);
	$_GET[$keyget] = $valg;
	$$keyget = $valg;
}
foreach($_SESSION as $keyses=>$vals){
	$$keyses = $vals;
}
if(isset($_POST['newabout']) and $_POST['post_id']==49){
	$_POST['newabout'] = $tmptext;
	$newabout = $tmptext;
}
if(isset($_POST['opass']) and isset($_POST['npass']) and isset($_POST['vpass']) and $_POST['post_id']==49){
$_POST['opass'] = $tmpopass;
$_POST['vpass'] = $tmpvpass;
$_POST['npass'] = $tmpnpass;
$opass = $tmpopass;
$vpass = $tmpvpass;
$npass = $tmpnpass;
}

#GET CHECKS
if(isset($_GET['get']) and in_array($_GET['vcode'],$_SESSION['secur'])){
	$_SESSION['user']['pos']=$_GET['get'];
	mysqli_query($GLOBALS['db_link'],"UPDATE `user` SET `useaction`='".$_GET['get']."' WHERE `id`='".$player['id']."'");
}

if(isset($_GET['get_id'])and in_array($_GET['vcode'],$_SESSION['secur'])){
	include($_SERVER["DOCUMENT_ROOT"]."/inc/get_id.php");
}

if(isset($_GET['go']) and in_array($_GET['vcode'],$_SESSION['secur'])){
	$pris=explode("|",$player['prison']);
	if($pris[0]<time()){change_get($_GET['go']);}
}

if(isset($_GET['post_id'])){
	if($_GET['post_id']==98 or $_GET['post_id']==109 or $_GET['post_id']==112  or $_GET['post_id']==114){include($_SERVER["DOCUMENT_ROOT"]."/inc/post_id.php");}
	else if(in_array($_GET['vcode'],$_SESSION['secur'])){include($_SERVER["DOCUMENT_ROOT"]."/inc/post_id.php");}	
}

if(isset($_GET['post_act'])){include($_SERVER["DOCUMENT_ROOT"]."/inc/post_act.php");}
#end check get

#POST CHECKS 
if(!isset($_GET['get']) and isset($_POST['get']) and in_array($_POST['vcode'],$_SESSION['secur'])){
	$_SESSION['user']['pos']=$_POST['get'];
	mysqli_query($GLOBALS['db_link'],"UPDATE `user` SET `useaction`='".$_POST['get']."' WHERE `id`='".$player['id']."'");
}

if(!isset($_GET['get_id']) and isset($_POST['get_id'])	and in_array($_POST['vcode'],$_SESSION['secur'])){
	include($_SERVER["DOCUMENT_ROOT"]."/inc/get_id.php");
}

if(!isset($_GET['go']) and isset($_POST['go']) and in_array($_POST['vcode'],$_SESSION['secur'])){
	$pris=explode("|",$player['prison']);
	if($pris[0]<time()){change_get($_POST['go']);}
}

if(!isset($_GET['post_id']) and isset($_POST['post_id'])){
	if($_POST['post_id']==98 or $_POST['post_id']==109 or $_POST['post_id']==112){include($_SERVER["DOCUMENT_ROOT"]."/inc/post_id.php");}
	else if(in_array($_POST['vcode'],$_SESSION['secur'])){include($_SERVER["DOCUMENT_ROOT"]."/inc/post_id.php");}	
}
if(isset($_POST['fightmagicstart']) and (in_array($_POST['fmc'],$_SESSION['secur']) or in_array($_POST['fmc'],$_SESSION['vcodes']))){include "inc/post_attack.php";}
#end check post

$player=player();
$plst=explode("|",$player['st']);
$plstt=allparam($player);
unset($_SESSION['secur']);

#calc rank
list($uronMin,$uronMax) = split("-", $plst[1]);
$player['rank_i'] = (($plstt[30]+$plstt[31]+$plstt[32]+$plstt[33]+$plstt[34]+($plst[9]+($perk[32]*30)))*0.3 + (($plst[7]+($perk[5]*30))+($plst[5]+($perk[19]*30))+($plst[6]+($perk[0]*30))+($plst[8]+($perk[15]*30)))*0.03 + ($player["hp_all"]+$player["mp_all"])*0.04+($uronMin+$uronMax)*0.3);
mysqli_query($GLOBALS['db_link'],"UPDATE `user` SET `rank_i` = '".$player['rank_i']."' WHERE `id` = '".$player['id']."'");
#end cals

if($_GET['QuestBattle']){
	if(mysqli_result(mysqli_query($GLOBALS['db_link'],"SELECT `que_st` FROM `quest_completed` WHERE `que_id`='4' AND `usr_id`='".$player['id']."'"),0) == '0'){
		mysqli_query($GLOBALS['db_link'],"UPDATE `quest_completed` SET `que_st`='2' WHERE `que_id`='4' AND `usr_id`='".$player['id']."'");
		TraneAttack($player,array(1000,1001));
	}
	if(mysqli_result(mysqli_query($GLOBALS['db_link'],"SELECT `que_st` FROM `quest_completed` WHERE `que_id`='10' AND `usr_id`='".$player['id']."'"),0) == '2'){
		TraneAttack($player,array(1005));
	}
	if(mysqli_result(mysqli_query($GLOBALS['db_link'],"SELECT `que_st` FROM `quest_completed` WHERE `que_id`='2' AND `usr_id`='".$player['id']."'"),0) == '2'){
		TraneAttack($player,array(1002));
	}
}

if($player['battle']!=0 and $player['fight']!=0 and $_GET['useaction'] != 'admin-action' and $_GET['useaction'] != 'client-action'){
	require ($_SERVER["DOCUMENT_ROOT"]."/inc/battle.php");
	exit;
}

if(isset($_GET['useaction']) and $player['waitprof']<=time()){
		switch($_GET['useaction']){
			case'addon-action':
				exit(include($_SERVER["DOCUMENT_ROOT"]."/inc/addon-action.php"));
			break;
			case'addons-action':
				exit(include($_SERVER["DOCUMENT_ROOT"]."/inc/addons-action.php"));
			break;
			case'client-action':
				exit(include($_SERVER["DOCUMENT_ROOT"]."/inc/client-action.php"));
			break;
			case'clan-action':
				if($_SESSION['user']['clan'] == '') {
					exit(header("location: /core2.php?useaction=clan-action"));
				}
			break;
			case'watchers-action':
				if(($player['access'] == 'watchers') or ($player['access'] == 'admins')) {
					exit(header("location: /core2.php?useaction=watchers-action"));
				}
			break;		
			case'admin-action':
				if($player['access'] == 'admins'){
					exit(header("location: /core2.php?useaction=admin-action"));
				}
			break;
			case'trade':
				exit(include($_SERVER["DOCUMENT_ROOT"]."/inc/trade.php"));
			break;
		}
}

if($player['loc'] == 500){
    list($pers['x'], $pers['y']) = explode('_', $player['pos']);
    $labyrinth = mysqli_fetch_assoc(mysqli_query($GLOBALS['db_link'],"SELECT * FROM `labyrinth` WHERE `x`='".$pers['x']."' and `y`='".$pers['y']."'"));
    if(empty($labyrinth)){
        mysqli_query($GLOBALS['db_link'],"UPDATE `user` SET `loc` = '500',`pos`='121_16' WHERE `id`='".$player['id']."'");
    }elseif($labyrinth['L_img'] != 11){
        $_SESSION['user']['pos'] = 3;
        mysqli_query($GLOBALS['db_link'],"UPDATE `user` SET `useaction`='3' WHERE `id`='".$player['id']."'");
        header("Location: /core2.php");
        exit;
    } 
}

if($player['battle']!=0 or $player['wait']>time())$_SESSION['user']['pos']=3;
include($_SERVER["DOCUMENT_ROOT"]."/inc/hedder.php");
if($_SESSION['user']['pos']<2){$inc="mpers.php";}
if($_SESSION['user']['pos']>1){$inc=$ret[3]."/".pl_loc($player['loc']);} // pl_loc($player['loc']) - пхп файл локации.
switch ($inc) // $case - имя переменной передаваемой в параметре к скрипту
{
case "mpers.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/mpers.php");break;
case "pitom.php": include($_SERVER["DOCUMENT_ROOT"]."/pitom.php");break;
case "forpost/arena.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/arena.php");break;
case "forpost/farena.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/farena.php");break;
case "forpost/arena.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/arena.php");break;
case "forpost/aukcion.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/aukcion.php");break;
case "forpost/bank.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/bank.php");break;
case "forpost/bank_op.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/bank_op.php");break;
case "forpost/bazar.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/bazar.php");break;
case "forpost/city.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/city.php");break;
case "forpost/city2.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/city2.php");break;
case "forpost/city3.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/city3.php");break;
case "forpost/city4.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/city4.php");break;
case "forpost/city5.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/city5.php");break;
case "forpost/city6.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/city6.php");break;
case "forpost/dilers.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/dilers.php");break;
case "forpost/dom.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/dom.php");break;
case "forpost/hospital.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/hospital.php");break;
case "forpost/hpv.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/hpv.php");break;
case "forpost/mark_pod.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/mark_pod.php");break;
case "forpost/market.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/market.php");break;
case "forpost/master.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/master.php");break;
case "maps/mine.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/mine.php");break;
case "maps/teleport.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/teleport.php");break;
case "forpost/Devil.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/Devil.php");break;
case "forpost/obelisk.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/obelisk.php");break;
case "forpost/post.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/post.php");break;
case "forpost/prison.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/prison.php");break;
case "forpost/tavern.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/tavern.php");break;
case "forpost/viselica.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/viselica.php");break;
case "forpost/gilotina.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/gilotina.php");break;
case "forpost/warshool.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/warshool.php");break;
case "forpost/wedding.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/wedding.php");break;
case "forpost/zaks.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/zaks.php");break;
case "forpost/meria.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/meria.php");break;
case "forpost/meria2.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/meria2.php");break;
case "forpost/besedka.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/besedka.php");break;
case "forpost/akadem.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/akadem.php");break;
case "forpost/strannik.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/strannik.php");break;
case "forpost/fstrannik.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/fstrannik.php");break;
case "forpost/fcraft.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/fcraft.php");break;
case "forpost/povar.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/povar.php");break;
case "forpost/pitom.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/pitom.php");break;
case "forpost/strannik3.php": include($_SERVER["DOCUMENT_ROOT"]."/includes/addons/admin-action/playera.php");break;
case "forpost/fish_shop.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/fish_shop.php");break;
case "forpost/fort.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/fort.php");break;
case "forpost/magpov.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/magpov.php");break;
case "forpost/clancraft.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/Hell/clancraft.php");break;
case "forpost/clanlavka.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/Hell/clanlavka.php");break;
case "forpost/clansklad.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/Hell/clansklad.php");break;
case "forpost/les_shop.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/les_shop.php");break;
case "forpost/labyrinth.php": echo"<script>window.location='/core2.php';</script>";break;
case "forpost/labyrinth_1.php": echo"<script>window.location='/core2.php';</script>";break;
case "forpost/kazino.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/kazino.php");break;
case "schools/lib.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/schools/lib.php");break;
case "schools/mag1.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/schools/mag1.php");break;
case "schools/mag2.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/schools/mag2.php");break;
case "schools/mag3.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/schools/mag3.php");break;
case "schools/mag4.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/schools/mag4.php");break;
case "besedka/besedka1.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/besedka/besedka1.php");break;
case "besedka/besedka2.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/besedka/besedka2.php");break;
case "besedka/besedka3.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/besedka/besedka3.php");break;
case "besedka/besedka4.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/besedka/besedka4.php");break;
case "besedka/besedka5.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/besedka/besedka5.php");break;
case "schools/magic.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/schools/magic.php");break;
case "image/obrazy/": include($_SERVER["DOCUMENT_ROOT"]."/image/obrazy/");break;
case "schools/secondary.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/schools/secondary.php");break;
case "forpost/skol.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/skol.php");break;
case "maps/map.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/maps/map.php");break;
case "maps/alhim.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/maps/alhim.php");break;
case "maps/alhim_create.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/maps/alhim/alhim_create.php");break;
case "maps/lab.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/maps/alhim/lab.php");break;
case "maps/map1.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/maps/map1.php");break;
case "maps/unground.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/village/unground.php");break;
case "forpost/mark_pod2.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/mark_pod2.php");break;
case "forpost/elka.php": include($_SERVER["DOCUMENT_ROOT"]."/inc/forpost/elka.php");break;
case "forum.seditio.local/": include($_SERVER["DOCUMENT_ROOT"]."/forum.seditio.local/index.php");break;
default: echo "<script type='text/javascript'> parent.location.href = 'http://seditio.local/'; </script>";break;
}
?>
<!--<input type="button" value="text" onclick="parent.$('#basic-modal-content').html('<img src=http://w.wenl.ru/image/gameplay/labyrinth/images/1/background.jpg />');parent.ShowModal(400,500);" />-->
</BODY>
</HTML>
