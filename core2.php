<?php 
session_start();
include($_SERVER["DOCUMENT_ROOT"]."/includes/config.inc.php");
include($_SERVER["DOCUMENT_ROOT"]."/includes/functions.php");
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
$pers = GetUser($_SESSION['user']['login']);
/* Go To Old Core */
if($_GET['go'] == 'inf' and $pers['wait']<=time()){
	$_SESSION['secur'] = $_SESSION['vcodes'];
	header("Location: /main.php?get=0&vcode=".$_GET['vcode']);	
}
if($_GET['go'] == 'inv' and $pers['wait']<=time()){
	$_SESSION['secur'] = $_SESSION['vcodes'];
	header("Location: /main.php?get=1&vcode=".$_GET['vcode']);	
}
/* Go To Old Core */


if(isset($_POST['post_id']) and in_array($_POST['vcode'],$_SESSION['vcodes'])){include($_SERVER["DOCUMENT_ROOT"]."/includes/post_id.php");}
if(isset($_GET['get_id']) and in_array($_GET['vcode'],$_SESSION['vcodes'])){include($_SERVER["DOCUMENT_ROOT"]."/includes/get_id.php");}

if($_GET['useaction'] != 'navigator-action'){
	unset($_SESSION['vcodes']);
	$_GET['addid'] = (($_GET['addid']) ? $_GET['addid'] : 'lots' );
}

if(isset($_GET['useaction'])){
	switch($_GET['useaction']){
		case'addon-action':
			exit(include($_SERVER["DOCUMENT_ROOT"]."/inc/addon-action.php"));
		break;
		case'auction-action':
			exit(include($_SERVER["DOCUMENT_ROOT"]."/includes/addons/auction-action.php"));
		break;
		case'ñraft-action':
			exit(include($_SERVER["DOCUMENT_ROOT"]."/includes/addons/craft-action.php"));
		break;
		case'navigator-action':
			exit(include($_SERVER["DOCUMENT_ROOT"]."/includes/addons/navigator-action.php"));
		break;
		case'buffs-action':
			exit(include($_SERVER["DOCUMENT_ROOT"]."/inc/buffs.php"));
		break;
		case'clanbuffs-action':
			exit(include($_SERVER["DOCUMENT_ROOT"]."/inc/clan_bonus.php"));
		break;
		case'masebuffs-action':
			exit(include($_SERVER["DOCUMENT_ROOT"]."/inc/masebuffs.php"));
		break;
		case'clan-action':
		if($pers['clan_id']!='none'){
			exit(include("includes/addons/clan-action.php"));
		}
		break;
		case'admin-action':
		if($pers['access'] =='admins'){
			exit(include("includes/addons/admin-action.php"));
		}
		break;
				case'watchers-action':
		if(accesses($pers['id'],'uid')){
			exit(include("includes/addons/watchers-action.php"));
		}

                break;
				case'rulette-action':
		if(accesses($pers['id'],'uid')){
			exit(include("includes/addons/rulette-action.php"));
		}
		break;
				case'map-action':
		if(accesses($pers['id'],'uid')){
			exit(include("includes/addons/map-action.php"));
		}
		break;
	}
}

switch($pers['useaction']){
	case'0':
		header("Location: /main.php");
	break;
	case'1':
		header("Location: /main.php");
	break;
	case'3':
		$location = mysqli_fetch_assoc(mysqli_query($GLOBALS['db_link'],"SELECT * FROM `loc` WHERE `id`='".$pers['loc']."'"));
		include($_SERVER["DOCUMENT_ROOT"]."/includes/locations/".$location['folder']."/".$location['inc']);
	break;
}
?>