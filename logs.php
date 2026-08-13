<?php 
include($_SERVER["DOCUMENT_ROOT"]."/func/connect.php");
include($_SERVER["DOCUMENT_ROOT"]."/func/sql_func.php");
db_open();
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

 ?>
<HTML>
<HEAD>
<TITLE>Лог боя - Мир Смерти.. Информация браузерной онлайн игры seditio.local- </TITLE>
<LINK href="/css/logs.css" rel="STYLESHEET" type="text/css">
<META Http-Equiv="Content-Type" Content="text/html; charset=windows-1251">
<META Http-Equiv="Cache-Control" Content="No-Cache">
<META Http-Equiv="Pragma" Content="No-Cache">
<META Http-Equiv="Expires" Content="0">
<SCRIPT src="/js/signs.js"></SCRIPT>
<SCRIPT src="/js/vlogs.js"></SCRIPT>
<SCRIPT src="/js/png.js"></SCRIPT>
<SCRIPT src="/js/top.js"></SCRIPT>
<SCRIPT src="/js/ft_v01.js"></SCRIPT>
</HEAD>
<BODY bgcolor="#FFFFFF">
<?php
$sql=mysqli_query($GLOBALS['db_link'],"SELECT arena.id_battle, arena.vis, user.side, user.id, user.sklon, user.clan_gif, user.level, user.login, user.hp, user.hp_all, user.dmg, user.invisible  FROM (arena LEFT JOIN user ON arena.id_battle = user.battle) WHERE (((arena.id_battle) = '".$fid."'))");
while ($pl = mysqli_fetch_assoc($sql)) {
	if($pl[vis]==0){
		$vis=0;
	}else{
		$vis=1;
	}
	if($pl['side']==1 and $pl['hp']>0){
		if(isset($livg1)){
			$z=",";	
		}
		if($pl['invisible']<time()){
			$livg1.=$z."[1,\"$pl[login]\",$pl[level],$pl[sklon],\"$pl[clan_gif]\",$pl[hp],$pl[hp_all],$pl[id]]";
		}else{
			$livg1.=$z."[4,1]";
		}
	}else if($pl['side']==2 and $pl['hp']>0){
		if(isset($livg2)){
			$z2=",";
		}
		if($pl['invisible']<time()){
			$livg2.=$z2."[1,\"$pl[login]\",$pl[level],$pl[sklon],\"$pl[clan_gif]\",$pl[hp],$pl[hp_all],$pl[id]]";
		}else{
			$livg2.=$z2."[4,1]";
		}
	}
}


$s=mysqli_fetch_assoc(mysqli_query($GLOBALS['db_link'],"SELECT Count(logs.id) AS count FROM logs WHERE bid='".$fid."' and list=0;"));$num = ceil($s['count']/10);
if(!isset($p) or $p==1){$p1=0;$p=1;}else{$p1=$p*10-11;}
?>
<SCRIPT language="JavaScript">
var d = document;
<? if(!isset($stat)){?>var logs = [<?php $sql=mysqli_query($GLOBALS['db_link'],"SELECT * FROM logs WHERE bid='".$fid."' and list=0 ORDER BY `id` ASC LIMIT ".$p1.", 10;"); while ($log = mysqli_fetch_assoc($sql)) {echo $log[log];}?>];<? }else{?>
var list = [[],<? $list=mysqli_fetch_assoc(mysqli_query($GLOBALS['db_link'],"SELECT * FROM logs WHERE bid='".$fid."' and list=1 LIMIT 1;")); echo $list[log]?>];<? }?>
var params = [<?=$num?>,<?=$stat+1?>,<?=$fid?>,<?=$p?>,1];
var show = <?=$stat+1?>;
var off = <?=$vis?>;
<? if($vis==0){?>
var lives_g1 = [<?=$livg1?>];
var lives_g2 = [<?=$livg2?>];<? }?>
viewlog();
NewLinksView();
</SCRIPT>

</BODY>
</HTML>