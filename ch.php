<?php
session_start ();
error_reporting(0);
require_once ($_SERVER["DOCUMENT_ROOT"]."/func/connect.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/func/sql_func.php");
db_open();
header('Content-type: text/html; charset=windows-1251'); 

$textstr = $_POST['text'];
foreach($_POST as $keypost=>$valp){
	if($$keypost != 'text'){
		$valp = varcheck($valp);
		$_POST[$keypost] = $valp;
		$$keypost = $valp;
	}
}
$text = $textstr;
foreach($_GET as $keyget=>$valg){
	$valg = varcheck($valg);
	$_GET[$keyget] = $valg;
	$$keyget = $valg;

}
foreach($_SESSION as $keyses=>$vals){
	$$keyses = $vals;
}
if(isset($_GET['ch_mode'])){
	switch(intval($_GET['ch_mode'])){
		case 0: $_SESSION['chat']['mode']=0;break;
		case 1: $_SESSION['chat']['mode']=1;break;
		#case 2: $_SESSION['chat']['mode']=2;break;
		default: $_SESSION['chat']['mode']=0;break;
	}
}
switch($_SESSION['chat']['mode']){
	case 0: $ch_mode=0; break;
	case 1: $ch_mode=1; break;
	#case 2: $ch_mode=2; break;
	default: $ch_mode=0; break;
}	
if($a=="ign"){
if($u!=$_SESSION['user']['login']){
if($s==0)ignor_rem($u);
if($s==1)ignor_add($u);}}
$player=player();

//Functions


function is_mat($m){
	global $player;
	$m=" ".strtolower(trim($m))." ";
	$a=explode(" ",$m);
	foreach($a as $m){
		if ($m){
		$m=" ".$m." ";
		if ((
		substr_count($m," бля") or
		substr_count($m,"бля ") or
		substr_count($m," пизд") or
		substr_count($m,"fuck") or
		substr_count($m,"сука") or
		substr_count($m,"хуё") or
		substr_count($m,"хуе") or
		substr_count($m," еба") or
		substr_count($m," ёба") or
		substr_count($m," бляд") or
		substr_count($m," лох") or
		substr_count($m,"лох ") or
		substr_count($m,"чмо ") or
		substr_count($m,"чёрт ") or
		substr_count($m,"мудак ") or
		substr_count($m," уёбок") or
		substr_count($m,"долбаёб ") or
		substr_count($m," далбаеб") or
		substr_count($m," блят") or
		(substr_count($m,"хуй") and !substr_count($m,"страх"))
		)
	and $player["login"]!='seditio.local'
//		and $player["clan_id"]!='chaos'
		) return true;
		}
	}
	return false;
}

function is_sysText($m){
	global $player;
	$m=" ".strtolower(trim($m))." ";
	$a=explode(" ",$m);
	foreach($a as $m){
		if($m){
			$m=" ".$m." ";
			if (
				substr_count($m,".ru/") or 
				substr_count($m,".ru/")
			)
				return true;
		}
	}
	return false;
}

function is_rvs($m){
	global $player;
	$m=strtolower(trim($m));
	$m=str_replace("/","",$m);
	$a=explode(" ",$m);
	foreach($a as $m)
	{
		$m=" ".$m." ";
		if ((substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
                or substr_count($m,"")
                or substr_count($m,"")
                or substr_count($m,"")
                or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")
		or substr_count($m,"")) 
		and !substr_count($m,"")
		and !substr_count($m,"")
		and !substr_count($m,"")
		and !substr_count($m,"")
		and !substr_count($m,"")
		and !substr_count($m,"")
		and $player["login"]!='Vellini'
		and $player["login"]!='Инквизитор'
//		and $player["clan_id"]!='chaos'
		) return true;
	}
	return false;
}


function is_rkp($m){
	global $player;
	$m=strtolower(trim($m));
	if ((substr_count($m,"escilon")
		or substr_count($m,"ereality")
		or substr_count($m,"w i n d l a n d")
		or substr_count($m,"neolands")
                or substr_count($m,"olands . ru")                
                or substr_count($m,"olands.ru")
                or substr_count($m,"olands ru")
                or substr_count($m,"narlands.ru")
                or substr_count($m,"narlands . ru")
                or substr_count($m,"narlands ru")
                or substr_count($m,"w l g a m e . r u")
		or substr_count($m,"mycombats.org")
		or substr_count($m,"mycombats. org")
		or substr_count($m,"mycombats.o r g")
		or substr_count($m,"windland")
                or substr_count($m,"s a m - l a n d s . r u")
                or substr_count($m,"sam-lands.ru")
                or substr_count($m,"s a m")
                or substr_count($m,"l a n d s")
                or substr_count($m,"lands")
                or substr_count($m,". r u")
                or substr_count($m,".ru")
                or substr_count($m,"r u")
                or substr_count($m,"Moon Light.ru")
                or substr_count($m,"Moon Light . ru")
                or substr_count($m,"narlands .ru")
                or substr_count($m,"narlands")
                or substr_count($m,"olands.ru")
                or substr_count($m,"olands")
                or substr_count($m,"seditio.local")
                or substr_count($m,"neverlands. ru")
                or substr_count($m,"alnoworlds . ru")
		or substr_count($m,"mortal-world")
		or substr_count($m,"wlgame.ru")
		or substr_count($m,"wlgame-ru")
                or substr_count($m,"wlgame - ru")
                or substr_count($m,"wlgame . ru")
                or substr_count($m,"neverkinght . ru")
                or substr_count($m,"neverkinght")
                or substr_count($m,"mortal - world . ru")
                or substr_count($m,"su")
                or substr_count($m,"n e v e r k i n g h t ")
                or substr_count($m,"neverwords.ru")
                or substr_count($m,"n e v e r k i n g h t-r u ")
                or substr_count($m,"neverwords . r u ")
                or substr_count($m,"n e v e r w o r d s . r u ")
                or substr_count($m,"N E V E R W O R D S . R U")
                or substr_count($m,"neverwords")
                or substr_count($m,"never")
                or substr_count($m,"w  l  g  a  m  e . r u")
                or substr_count($m,"legendbattles . ru ")
                or substr_count($m,"w  l     g     a   m       e . r u")
                or substr_count($m,"wlgame.ru")
		or substr_count($m," mortal-world(точка)ru")
                or substr_count($m," mortal-world.ru")
                or substr_count($m," mortal-world . ru")
                or substr_count($m,"mortal-world")
		or substr_count($m,"mortal-world.ru")
		or substr_count($m,"waroflands.ru"))
		and !substr_count($m,"glowen .ru")
		and !substr_count($m,"Боги Хаосаiswar.ru")
	        and $player["login"]!='Vellini'
		and $player["clan_id"]!='777'
		) return true;
	return false;
}
//End Functions

if($player['block']!='' or $_SESSION['user']['login']==''){
    if($player['block']){
        exit("<script>parent.location='index.php?act=logout';</script>");
    }
    exit("<script>parent.location='error.php';</script>");
}

if(!preg_match("/" . $_SERVER['HTTP_HOST'] . "/", getenv ('HTTP_REFERER' )) or $_SESSION['user']['login']=='' or $_SESSION['user']['uin']!=$player['pcid'] or $player['block']!='' or isset($check['id']) or isset($check2['id'])){
	if($player['block']!=''){
		header("Location: /index.php?act=logout");
	}else{
		header("Location: /error.php");
	}
}

if(isset($_GET['lo'])){
    $FiltsList = array('a_z','z_a','0_35','35_0');
    if(in_array($_GET['order'],$FiltsList)){
        $_SESSION['user']['filt'] = $_GET['order'];
    }
    if(!isset($_SESSION['user']['filt'])){
        $_SESSION['user']['filt'] = $player['filt'];
    }else{
        $player['filt'] = $_SESSION['user']['filt'];
    }

?>
<html>
<head>
<LINK href="/ch/list.css?v4" rel=STYLESHEET type=text/css>
<LINK href="/css/NewDesign.css?v4" rel=STYLESHEET type=text/css>

<meta content="text/html; charset=windows-1251" http-equiv=Content-type>
<META Http-Equiv=Cache-Control Content=no-cache>
<meta http-equiv=PRAGMA content=NO-CACHE>
<META Http-Equiv=Expires Content=0>
</HEAD>

<script LANGUAGE="JavaScript" src='/js/jquery-1.7.2.min.js'></script>
<script type="text/javascript" src="/js/jquery.smodal.js"></script>
<SCRIPT LANGUAGE="JavaScript" src='/ch/ch_list.js?v4'></SCRIPT>
<table border=0 cellpadding=0 cellspacing=0 width=100%>

<td bgcolor="#ffffff"    topmargin="1" bottommargin="1" marginwidth="1" marginheight="1" leftmargin="1" rightmargin="1">
	    <div height="56" align="center" valign="middle" ><? if($r){$player['loc']=$r;} $on_room=locations($player["loc"],$player["pos"]);?></div>
<div bgcolor=#ffffff mardginheight=0 topmardgin=0 topmargin=0 marginheight=0 height="56" align="center" valign="middle"  onscroll="parent.save_scroll_p()" onLoad="document.body.scrollTop=parent.OnlineScrollPosition">
     
 <script>
        document.write ('<input type=checkbox onclick="parent.OnlineStop=!parent.OnlineStop;parent.reload(false);" '+ (parent.OnlineStop ? '' : 'checked') + '>');
      </script>
  <INPUT TYPE=button class="menu-refresh" onClick="location='ch.php?lo=1&'"><br>
      <a href="ch.php?lo=1&order=a_z" class="sort">
      <?php
        if($_SESSION['user']['filt']=="a_z"){
			echo '<b>a-z</b>';
		}else{
			echo 'a-z';
		}
	  ?></a> <a href="ch.php?lo=1&order=z_a" class="sort">
	  <?php
	    if($_SESSION['user']['filt']=='z_a'){
			echo '<b>z-a</b>';
		}else{
			echo 'z-a';
		}
	  ?></a> <a href="ch.php?lo=1&order=0_35" class="sort">
	  <?php
		if($_SESSION['user']['filt']=='0_35'){
			echo '<b>0-35</b>';
		}else{
			echo '0-35';
		}
	  ?></a> <a href="ch.php?lo=1&order=35_0" class="sort">
	  <?php
	    if($_SESSION['user']['filt']=='35_0'){
			echo '<b>35-0</b>';
	    }else{
			echo '35-0';
		}
	  ?></a>
<img src="/image/1x12.gif" width="306" height="1"><br></div>

<SCRIPT>
var ChatListU=new Array(
<?php
$tarr[] = '"Страж Порядка:Страж Порядка:32;1:lut_cl.gif;Верховная Инквизиция;Глава Клана:0:0:0:0:0:0:0:0:0"';
if($player['login']!='Vellini' and $player['login']!='Инквизитор' and $player['login']!='' and $player['login']!='Vellini'){
	$res=mysqli_query($GLOBALS['db_link'],"SELECT `user`.`login`,`user`.`invisible`,`user`.`sleep`,`user`.`loc`,`user`.`clan_d`,`user`.`level`,`user`.`clan`,`user`.`clan_gif`,`user`.`sklon`,`user`.`last`,`user`.`affect`,`user`.`pos`,`user`.`a_m`,`user`.`premium`,`user`.`id`,`user`.`u_lvl`,`user`.`fcolor`,`user`.`fcolor_time`,`user`.`vzlomshik_nav`,`user`.`semija` FROM `user` LEFT JOIN `loc` ON `user`.`loc`=`loc`.`id` WHERE `user`.`loc`='".$player['loc']."' ".($player['loc']!=28 ? "" : "AND `user`.`pos`='".$player['pos']."'")." AND `user`.`last`>'".(time()-300)."' and `user`.`id`!='15391743';");
}else{//полный список чата
	$res=mysqli_query($GLOBALS['db_link'],"SELECT `user`.`login`,`user`.`invisible`,`user`.`sleep`,`user`.`loc`,`user`.`clan_d`,`user`.`level`,`user`.`clan`,`user`.`clan_gif`,`user`.`sklon`,`user`.`last`,`user`.`affect`,`user`.`pos`,`user`.`a_m`,`user`.`premium`,`user`.`id`,`user`.`u_lvl`,`user`.`fcolor`,`user`.`fcolor_time`,`user`.`vzlomshik_nav`,`user`.`semija` FROM `user` LEFT JOIN `loc` ON `user`.`loc`=`loc`.`id` WHERE `user`.`last`>'".(time()-300)."';");
}
while ($row=mysqli_fetch_assoc($res)) {
		$prem=explode("|",$row['premium']);
		$dealer=0;
		if($row['fcolor_time']>time() or $row['fcolor_time']==0){
			$nickclr = $row['fcolor'];
			if($nickclr=='ffffff'){$nickclr='';}
		}else{$nickclr='';}
		if($row['sleep']>time()){
			$sleep=$row['sleep']-time();
		}else{
			$sleep=0;
		}
		if($row['clan']!='0'){
			$row['clan_d']=preg_replace("/:/","",$row['clan_d']);
			$row['clan_d']=preg_replace("/;/","",$row['clan_d']);
			$clan=$row['clan_gif'].";".$row['clan'].";".$row['clan_d'];
		}else{
			$clan='0';
		}
		if (array_key_exists($row['login'], varcheck($_SESSION['ignor']))){
			$ign=1;
		}else{
			$ign=0;
		}
		if(affect($row['affect'],0,true)!=''){
			$traw=affect($row['affect'],0,true);
		}else{
			$traw=0;
		}
		if($row['invisible']<time() or $player['login']=='seditio.local' or $player['login']=='Vellini' or $player['login']=='Vellini'){
			$tarr[]= '"'.strtolower($row['login']).':'.$row['login'].':'.$row['level'].';'.$row['vzlomshik_nav'].':'.$clan.':'.$sleep.':'.$ign.':'.$traw.':'.(accesses($row['id'],'dealer')?((accesses($row['id'],'dealer',1)!=3)?"2":"0"):"0").':'.$row['sklon'].':'.$prem[0].':'.$nickclr.($player['login']=='gggg'?':1:'.scode().'':':0')."\"\n";
		}
}
print implode(",",$tarr);
echo "); ";
mysqli_free_result($res);
?>
chatlist_build('<?=$_SESSION['user']['filt']?>');
</SCRIPT><br>
</td></tr>
</table>
</body>

<?php }
//-------пишем в чат-----------
if (isset($text)){ if($player['sleep']<time()){
	$msg=ltrim($text);
	if($player['clan_id']!='none'){
		$msg=preg_replace("/%clan%/","%<".$player['clan_id'].">",$msg);
	}
	preg_match("/^((?:\%?\<[^\>]{2,20}\>\s?)+)(.*?)$/", $msg, $arr);
	if ($arr){
		if ($arr[2]){
			$message=htmlspecialchars(strip_tags($arr[2], ''));
			$to=$arr[1];
		}else{
			$message='';
		}
	}else{
		$message=htmlspecialchars(strip_tags($msg, ''));
	}
	if(preg_match("/%<".$player['clan_id'].">/i", $arr[1])){
		$chtime='clchattime';
	}else if(preg_match("/%</i",$arr[1])){
		$chtime='prchattime';
	}else{
		$chtime='yochattime';
	}
/*$message=chat($_SESSION['user']['login'],$message);*/ 
	if($message!=''){
		if($pactiondo==1){
			$message="<i><b>".$message."</b></i>";
		}
		if($player['fcolor_time']>time() or $player['fcolor_time']==0){
			$nickclr = $player['fcolor'];
			if($nickclr=='000000'){$nickclr='';}
		}else{$nickclr='';}
		$message=preg_replace("/(\\\\[0-9]*)/i",'&acute;',$message);
		if(strlen($message)>250){$minus = (250-strlen($message))*(-1);$message=substr($message,0,strlen($message)-$minus);}
		$message="<font color=".$player['chcolor']."> ".preg_replace("/\'/","&acute;",$message)."</font> <BR>'+'');";
		if($player['invisible']>time() and $chtime == 'yochattime'){
			$users=$to?'<SPL><b>невидимка</b><SPL>'.preg_replace("<SPL>","",$to).'<SPL>':'<b>невидимка</b>:';
		}else{
			$users=$to?'<SPL><font style="color: #'.($nickclr?$nickclr:'#000000').'"><SPAN>'.$player['login'].'</SPAN></font><SPL>'.preg_replace("<SPL>","",$to).'<SPL>':'<font style="color: #'.($nickclr?$nickclr:'000000').'"><SPAN>'.$player['login'].'</SPAN></font>:';
		}
		if(is_sysText($message)){
			$bbcodes = array("/карта/is" => "<a href=# onclick=\"window.open(\'/map.php\',\'\');\" target=_blank><font color=#3564A5><b>карта</b></font></a>","/карту/is" => "<a href=# onclick=\"window.open(\'/map.php\',\'\');\" target=_blank><font color=#3564A5><b>карту</b></font></a>");
			$message = preg_replace(array_keys($bbcodes), array_values($bbcodes), $message);
		}
		echo "<script>parent.clr_input();parent.frames['chmain'].add_msg".($ch_mode==0?'':($ch_mode==1?'_trade':''))."('<font class=".$chtime.">&nbsp;".date("H:i:s")."&nbsp;</font> ".$users." ".$message."</script>";
			if (time()<($_SESSION['StopFlood']['time']+2)){
                $_SESSION['StopFlood']['count']++;
            }else{
                $_SESSION['StopFlood']['count'] = 0;
            }
            $_SESSION['StopFlood']['time'] = time();
            if(is_rvs($message) and $player['login']!='Vellini' and $player['login']!='Vellini' and $player['login']!='Vellini'){
				mysqli_query($GLOBALS['db_link'],"INSERT INTO `chat` (`time`,`login`,`msg`) VALUES ('".time()."','sys','".addslashes("parent.frames['chmain'].add_msg".($ch_mode==0?'':($ch_mode==1?'_trade':''))."('<font class=chattime>&nbsp;".date("H:i:s")."&nbsp;</font> <font color=000000><b><font color=#CC0000>Внимание!</font></b></font>&nbsp;На персонажа <b>".$player['login']."</b> наложено заклятие молчания сроком на <b>5</b> мин. Подозрение на РВС. (<b>Страж Порядка</b>)</font><BR>'+'');")."');");
				mysqli_query($GLOBALS['db_link'],"UPDATE `user` SET `sleep`='".(time()+5*60)."' WHERE `login`='".$player['login']."' LIMIT 1;");
			}elseif(is_mat($message) and $player['login']!='Vellini' and $player['login']!='Vellini' and $player['login']!='Vellini'){
				mysqli_query($GLOBALS['db_link'],"INSERT INTO `chat` (`time`,`login`,`msg`) VALUES ('".time()."','sys','".addslashes("parent.frames['chmain'].add_msg".($ch_mode==0?'':($ch_mode==1?'_trade':''))."('<font class=chattime>&nbsp;".date("H:i:s")."&nbsp;</font> <font color=000000><b><font color=#CC0000>Внимание!</font></b></font>&nbsp;На персонажа <b>".$player['login']."</b> наложено заклятие молчания сроком на <b>10</b> мин. Подозрение на мат. (<b>Страж Порядка</b>)</font><BR>'+'');")."');");
				mysqli_query($GLOBALS['db_link'],"UPDATE `user` SET `sleep`='".(time()+10*60)."' WHERE `login`='".$player['login']."' LIMIT 1;");
			}elseif(is_rkp($message) and $player['login']!='Vellini' and $player['login']!='Vellini' and $player['login']!='Vellini'){
				mysqli_query($GLOBALS['db_link'],"INSERT INTO `chat` (`time`,`login`,`msg`) VALUES ('".time()."','sys','".addslashes("parent.frames['chmain'].add_msg".($ch_mode==0?'':($ch_mode==1?'_trade':''))."('<font class=chattime>&nbsp;".date("H:i:s")."&nbsp;</font> <font color=000000><b><font color=#CC0000>Внимание!</font></b></font>&nbsp;На персонажа <b>".$player['login']."</b> наложено заклятие молчания сроком на <b>10</b> мин. Подозрение на РКП. (<b>Страж Порядка</b>)</font><BR>'+'');")."');");
				mysqli_query($GLOBALS['db_link'],"UPDATE `user` SET `sleep`='".(time()+10*60)."' WHERE `login`='".$player['login']."' LIMIT 1;");
			}elseif($_SESSION['StopFlood']['count'] > 2 and $player['login']!='Vellini' and $player['login']!='Vellini' and $player['login']!='Vellini'){
                $_SESSION['StopFlood']['count'] = 0;
			}else{
				mysqli_query($GLOBALS['db_link'],"INSERT INTO `chat` (`time`,`login`,`ot_color`,`inv`,`dlya`,`loc`,`pos`,`msg`,`mode`) VALUES ('".time()."','".varcheck($_SESSION['user']['login'])."','".($nickclr?$nickclr:'000000')."','".(($player['invisible']<time())?'0':'1')."','".$to."','".$player['loc']."','".$player['pos']."','".mysqli_real_escape_string($GLOBALS['db_link'],stripcslashes($message))."','".$ch_mode."')");
			}
			if($to == '<Страж Порядка> ' or $to == '%<Страж Порядка> '){
				include("includes/database/NewChatBot.php");
				if($to != '%<Страж Порядка> '){
					if($response != ''){
						
						$response = "<font color=000000> ".$response."</font> <BR>'+'');";
				mysqli_query($GLOBALS['db_link'],"INSERT INTO `chat` (`time`,`login`,`inv`,`dlya`,`loc`,`pos`,`msg`) VALUES ('".time()."','Страж Порядка','0','<".$player['login']."> ','".$player['loc']."','".$player['pos']."','".mysqli_real_escape_string($GLOBALS['db_link'],stripcslashes($response))."');");						
						echo"<script>parent.frames['chmain'].add_msg".($ch_mode==0?'':($ch_mode==1?'_trade':''))." ('<font class=yochattime>&nbsp;".date("H:i:s")."&nbsp;</font> <SPL><SPAN>Страж Порядка</SPAN><SPL><".$player['login']."> <SPL> ".$response."\nparent.set_lmid(8);\n</script>";
					}
				}
				
			}
	}
}
else{
	echo "<script>parent.clr_input();</script>";
}
}

if(isset($_GET['show'])){

if($player['battle']>0 and $player['fight']==2){
$sqltime=mysqli_query($GLOBALS['db_link'],"SELECT `user`.`battle` FROM `arena` LEFT JOIN `user` ON `arena`.`id_battle`=`user`.`battle` WHERE `user`.`id`='".$player['id']."' AND `arena`.`vis`!='3' AND `arena`.`t2`+`arena`.`timeout`<'".time()."';");
if(mysqli_num_rows($sqltime)>0){
	while ($r=mysqli_fetch_assoc($sqltime)) {
		$win[0]=4;
		$win[1]=$player['level'];
		$win[999]=1;
		endbat($player['battle'],$win);
	}	
}
}
//Считаем время баффов\зелий игрока
if($player['buffs']==''){$player['buffs']="||||";}
$buffs=explode("|",$player['buffs']);
$rebuff=0;
foreach ($buffs as $value){
	$buff=explode("@",$value);
	if($buff[2]!='' and $buff[2]<time()){
		$newbuff.="";
		$rebuff=1;
	}
	else{
		$newbuff.=implode("@",$buff)."|";
	}
}
if($rebuff==1){
	mysqli_query($GLOBALS['db_link'],"UPDATE `user` SET `buffs`='".$newbuff."' WHERE `id`='".$player['id']."';");
	calcstat($player['id']);
}
if($player['firstlogin']=='0'){
include($_SERVER["DOCUMENT_ROOT"]."/inc/flogin.php");
}
if($player['lastbattle']<time() and $player['fight']=='0' and $player['loc']=='28' and $player['hp']>='1' and ($player['battle'] == '0' or $player['battle'] == '') and $player['wait']<time()){
	BotAttack($player);
}

if ($_SESSION['user']['on_time'] <= time()){
	$_SESSION['user']['on_time']=time()+200;
	mysqli_query($GLOBALS['db_link'],"UPDATE `user` SET `last`='".time()."' WHERE `login`='".varcheck($_SESSION['user']['login'])."' LIMIT 1;");
}  
if($_SESSION['user']['wait']==1 and $player['fight']>0){
if($player['side']==1){$side=2;}else{$side=1;}
$en=mysqli_num_rows(mysqli_query($GLOBALS['db_link'],"SELECT `user`.`id`,`user`.`side`,`user`.`battle`,`user`.`hp`,`fight`.`eid` FROM `user` LEFT JOIN `fight` ON `user`.`id`=`fight`.`eid` WHERE `user`.`battle`='".$player['battle']."' AND `user`.`side`='".$side."' AND `user`.`hp`>'0' AND `fight`.`eid` Is Null LIMIT 1;"));
if($en>0){echo "<script> parent.frames['main_top'].location='main.php';</script>";}}

//---------------ЧАТ-----------------

/*

*/

/*
	$result = mysqli_query($GLOBALS['db_link'],"SELECT * FROM `chat` WHERE ((`login`!='".varcheck($_SESSION['user']['login'])."' AND `chat`.`dlya` LIKE '%\%<".varcheck($_SESSION['user']['login']).">%' AND `id`>'".varcheck($_SESSION['user']['lastch'])."') or (`login`!='".varcheck($_SESSION['user']['login'])."' AND `chat`.`dlya` LIKE '%".varcheck($_SESSION['user']['login'])."%' AND `id`>'".varcheck($_SESSION['user']['lastch'])."') OR (`login`!='".varcheck($_SESSION['user']['login'])."' AND (`chat`.`dlya` not LIKE '%\%<%>%' OR `dlya`='') AND `id`>'".varcheck($_SESSION['user']['lastch'])."')) OR ((`login`='sys' or `login`='mass') and `id`>'".varcheck($_SESSION['user']['lastch'])."') OR ((`login`='sys' or `login`='mass') and `id`>'".varcheck($_SESSION['user']['lastch'])."');"); 
*/
$result=mysqli_query($GLOBALS['db_link'],"SELECT * FROM `chat` WHERE ((`login`!='".varcheck($_SESSION['user']['login'])."' AND `chat`.`dlya` LIKE '%\%<".varcheck($_SESSION['user']['login']).">%' AND `id`>'".varcheck($_SESSION['user']['lastch'])."') or (`login`!='".varcheck($_SESSION['user']['login'])."' AND `chat`.`dlya` LIKE '%".varcheck($_SESSION['user']['login'])."%' AND `id`>'".varcheck($_SESSION['user']['lastch'])."') OR (`login`!='".varcheck($_SESSION['user']['login'])."' AND (`chat`.`dlya` not LIKE '%\%<%>%' OR `dlya`='') AND `id`>'".varcheck($_SESSION['user']['lastch'])."')) OR ((`login`='sys' or `login`='mass') and `id`>'".varcheck($_SESSION['user']['lastch'])."') OR ((`login`='sys' or `login`='mass') and `id`>'".varcheck($_SESSION['user']['lastch'])."');"); 
echo "<script>";
while ($row=mysqli_fetch_assoc($result)) {
$msg=$row['msg'];$dlya=$row['dlya'];
$ot=$row['login'];
$time=$p.date("H:i:s",$row['time']);  $_SESSION['user']['lastch']=$row['id'];
if (array_key_exists($row['login'],varcheck($_SESSION['ignor']))){continue;} 
if(($fyo!=2 and $fyo!=1) and $ot=='mass' and (preg_match("/<".varcheck($_SESSION['user']['login']).">/i", $dlya, $regs) or $dlya=='')){echo "parent.frames['chmain'].add_msg".($row['mode']==0?'':($row['mode']==1?'_trade':($row['mode']==2?'_clan':'')))."('".$msg."<BR>'+'');"; }
else if($ot=='sys' and (preg_match("/<".varcheck($_SESSION['user']['login']).">/i", $dlya, $regs)or $dlya=='')){echo $msg; }else{
if($fyo==2){$msg='';}
else if($fyo==1){
	if(preg_match("/%<".$player['clan_id'].">/i", $dlya, $regs)){$ctimecolor="clchattime";}
	else if(preg_match("/%<".varcheck($_SESSION['user']['login']).">/i", $dlya, $regs)){$ctimecolor="prchattime";}
	else if(preg_match("/<".varcheck($_SESSION['user']['login']).">/i", $dlya, $regs)){$ctimecolor="yochattime";}
	else{$msg='';}
}else if($fyo==0){
	if(preg_match("/%<".$player['clan_id'].">/i", $dlya, $regs)){$ctimecolor="clchattime";}
	else if(preg_match("/%<".varcheck($_SESSION['user']['login']).">/i", $dlya, $regs)){$ctimecolor="prchattime";}
	else if(preg_match("/<".varcheck($_SESSION['user']['login']).">/i", $dlya, $regs)){$ctimecolor="yochattime";}
	else if(!preg_match("/%</i", $dlya, $regs)){$ctimecolor="chattime";}
else{$msg='';}}
if ($msg!='' and $ot!='sys'){
	$ShowMsg = 1;
	if($row['inv']=='1' and ($ctimecolor == 'chattime' or $ctimecolor == 'yochattime')){
		$ot_1=((accesses($player['id'],'pvu'))?'<i>'.$ot.'</i>':'');
		$users=$dlya?'<SPL>'.((accesses($player['id'],'pvu'))?'<s><i><font style="color: #'.$row['ot_color'].'"><SPAN>'.$ot.'</SPAN></font></i></s>':'невидимка').'<SPL>'.$dlya.'<SPL>':((accesses($player['id'],'pvu'))?'<s><i><font style="color: #'.$row['ot_color'].'"><SPAN>'.$ot.'</SPAN></font></i></s>':'невидимка:');
	}else{
		$users=$dlya?'<SPL><font style="color: #'.$row['ot_color'].'"><SPAN>'.$ot.'</SPAN></font><SPL>'.$dlya.'<SPL>':'<font style="color: #'.$row['ot_color'].'"><SPAN>'.$ot.'</SPAN></font>:';
	}
	if($ot == 'Страж Порядка' and $dlya == '<'.$player['login'].'> '){
		$ShowMsg = 0;
	}
	if($ShowMsg == 1){		
		echo "\nparent.frames['chmain'].add_msg".($row['mode']==0?'':($row['mode']==1?'_trade':''))." ('<font class=".$ctimecolor.">&nbsp;".$time."&nbsp;</font> ".$users." ".$msg;	
	}
	}}
}
echo "parent.set_lmid(8);
</script>";
 mysqli_free_result($result);
 
//-----------КЛАНЧАТ----------------
$resultclan=mysqli_query($GLOBALS['db_link'],"SELECT * FROM `chat` WHERE `chat`.`dlya` LIKE '%\%<".$player['clan_id'].">%' AND `id`>'".varcheck($_SESSION['user']['lastch'])."' AND `login`!='".varcheck($_SESSION['user']['login'])."';");
echo "<script>";
while ($row=mysqli_fetch_assoc($resultclan)) {
	$ctimecolor="prchattime";
	$msg=$row["msg"];
	$dlya=$row["dlya"];
	$ot=$row["login"];
	$time=$p.date("H:i:s",$row["time"]); 
	$_SESSION['user']['lastch']=$row["id"]; 
	$ctimecolor="clchattime";
	$checkuser=mysqli_fetch_assoc(mysqli_query($GLOBALS['db_link'],"SELECT `user`.`clan_id` FROM `user` WHERE `login`='".$ot."' LIMIT 1;"));
	if ($checkuser['clan_id'] == $player['clan_id'] or $ot == 'Vellini' or $ot == 'Vellini'){
		$users=$dlya?'<SPL><font style="color: #FF3333'.$row['ot_color'].'"><SPAN>'.$ot.'</SPAN></font><SPL>'.$dlya.'<SPL>':'<SPAN>'.$ot.'</SPAN>:';
		echo "parent.frames['chmain'].add_msg ('<font class=".$ctimecolor.">&nbsp;".$time."&nbsp;</font> ".$users." ".$msg;
	}
}
echo "parent.set_lmid(8);
</script>";
 mysqli_free_result($resultclan);
}