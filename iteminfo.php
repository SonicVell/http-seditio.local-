<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"]."/includes/config.inc.php");
include($_SERVER["DOCUMENT_ROOT"]."/includes/functions.php");

function itemparams($inv,$ITEM,$player,$plstt,$mass){	
	$par_i='';$tr_b[0]='';$tr_b[1]=0;	
	$par=explode("|",$ITEM['param']);
	$mod=explode("|",$ITEM['mod']);
	$need=explode("|",$ITEM['need']);
	if($inv==1){
		//торговая лицензия персонажа
			if($player[level]<5){$licen=1;}
			else{$licen=tradelic($player['licens'],1);}
		//
		$iz=$ITEM[dolg]-$ITEM[iznos];
		$izn = round(($iz/($ITEM[dolg]/100))*0.62);
		$pro = 62-$izn;
		if($ITEM[dd_price]>0){
			$licen=0.9;
			$price_dd=round(($ITEM[dd_price]*$licen*$iz/$ITEM[dolg]),2);
		}
		else if($ITEM[gift]==1){
			$licen=0.4;
			$price=round($ITEM[price]*$licen*$iz/$ITEM[dolg]);
			if($price<1){$price=1;}
		}
		else{
			$price=round($ITEM[price]*$licen*$iz/$ITEM[dolg]);
			if($price<1){$price=1;}
		}	
	}
	$modstat='';
	foreach ($mod as $value){
		$modstats=explode("@",$value);
		$modstat[$modstats[0]]=$modstats[1];
	}	
	//параметры
	//if($par){	
		if($ITEM['type']=='w70'){ 
			if($ITEM['effect']>0){$par_i.="&nbsp;Время действия: <font color=#BB0000><b>".$ITEM['effect']."</b></font> минут<br>";}
			switch($ITEM['num_a']){
				case '32': $par_i.="&nbsp;Снимает эффекты: <font color=#BB0000><b>мази.</b></font><br>";break;
				case '33': $par_i.="&nbsp;Снимает эффекты: <font color=#BB0000><b>травмы.</b></font><br>";break;
				case '34': $par_i.="&nbsp;Снимает эффекты: <font color=#BB0000><b>зелья, абилки.</b></font><br>";break;
				case '1': $par_i.="&nbsp;Снимает эффекты: <font color=#BB0000><b>травмы, зелья, абилки, мази.</b></font><br>";break;
				case '2': $par_i.="&nbsp;Снимает эффекты: <font color=#BB0000><b>травмы, зелья, абилки, мази.<br>&nbsp;Со всех персонажей на клетке.</b></font><br>"; break;
				case '3': $par_i.="&nbsp;Снимает эффекты: <font color=#BB0000><b>мази.<br>&nbsp;Со всех персонажей на клетке.</b></font><br>"; break;
				case '4': $par_i.="&nbsp;Снимает эффекты: <font color=#BB0000><b>травмы.<br>&nbsp;Со всех персонажей на клетке.</b></font><br>"; break;
				case '5': $par_i.="&nbsp;Снимает эффекты: <font color=#BB0000><b>зелья, абилки.<br>&nbsp;Со всех персонажей на клетке.</b></font><br>"; break;
			}
		}		
		foreach ($par as $value){
			$stat=explode("@",$value);
			if($stat[1]>0){
				$plus = "+";		
			}else{
				$plus ="";
			}
			if ($stat[0]>4 and $stat[0]<11 and $stat[0]!=9){
				$percent="%";
			}else{
				$percent="";
			}
			if($stat[0]==1){
				$pr=explode("-",$modstat[1]);		
				$pri=explode("-",$stat[1]);
				$modstroke="".($modstat[1]!='' ?  ($pr[0]+$pri[0])."-".($pr[1]+$pri[1])."$percent  </b>[".($modstat[1]>0 ? "<font color=green> <b>".$modstat[1]."</b>$percent" : "<font color=red><b>".$modstat[1]."</b>$percent")."</font></b> ]<b> " : "$stat[1]$percent")."";
			}else{
				$modstroke="".($modstat[$stat[0]]!='' ?  $stat[1]+$modstat[$stat[0]]."$percent  </b>[".($modstat[$stat[0]]>0 ? "<font color=green>+<b>".$modstat[$stat[0]]."</b>$percent" : "<font color=red><b>".$modstat[$stat[0]]."</b>$percent")."</font></b> ]<b> " : "$stat[1]$percent")."";
			}
			switch($stat[0]){
				//case 0: $par_i.= "&nbsp;Гравировка: <b>".$modstroke."</b><br>"; break;
				case 1: $par_i.= "&nbsp;Удар: <b>".$modstroke."</b><br>";break;
				case 2: 
					($iz)? $par_i.= "&nbsp;Долговечность: <b>".(($iz==1 and $ITEM['slot']!=5) ? "<font color=red>".$iz."</font>" : $iz)."/$ITEM[dolg]</b><br>" 
					: 
					$par_i.= "&nbsp;Долговечность: <b>$stat[1]/$stat[1]</b><br>"
					; 
					break;
				case 3: $par_i.= "&nbsp;Карманов: <b>".$modstroke."</b><br>";break;
				case 4: $par_i.= "&nbsp;Материал: <b>".$modstroke."</b><br>";break;
				case 5: $par_i.= "&nbsp;Уловка: $plus<b>".$modstroke."</b><br>";break;
				case 6: $par_i.= "&nbsp;Точность: $plus<b>".$modstroke."</b><br>";break;
				case 7: $par_i.= "&nbsp;Сокрушение: $plus<b>".$modstroke."</b><br>";break;
				case 8: $par_i.= "&nbsp;Стойкость: $plus<b>".$modstroke."</b><br>";break;
				case 9: $par_i.= "&nbsp;Класс брони: <b>".$modstroke."</b><br>";break;
				case 10: $par_i.= "&nbsp;Пробой брони: $plus<b>".$modstroke."</b><br>";break;
				case 11: $par_i.= "&nbsp;Пробой колющим ударом: $plus<b>".$modstroke."%</b><br>";break;
				case 12: $par_i.= "&nbsp;Пробой режущим ударом: $plus<b>".$modstroke."%</b><br>";break;
				case 13: $par_i.= "&nbsp;Пробой проникающим ударом: $plus<b>".$modstroke."%</b><br>";break;
				case 14: $par_i.= "&nbsp;Пробой пробивающим ударом: $plus<b>".$modstroke."%</b><br>";break;
				case 15: $par_i.= "&nbsp;Пробой рубящим ударом: $plus<b>".$modstroke."%</b><br>";break;
				case 16: $par_i.= "&nbsp;Пробой карающим ударом: $plus<b>".$modstroke."%</b><br>";break;
				case 17: $par_i.= "&nbsp;Пробой отсекающим ударом: $plus<b>".$modstroke."%</b><br>";break;
				case 18: $par_i.= "&nbsp;Пробой дробящим ударом: $plus<b>".$modstroke."%</b><br>";break;
				case 19: $par_i.= "&nbsp;Защита от колющих ударов: $plus<b>".$modstroke."</b><br>";break;
				case 20: $par_i.= "&nbsp;Защита от режущих ударов: $plus<b>".$modstroke."</b><br>";break;
				case 21: $par_i.= "&nbsp;Защита от проникающих ударов: $plus<b>".$modstroke."</b><br>";break;
				case 22: $par_i.= "&nbsp;Защита от пробивающих ударов: $plus<b>".$modstroke."</b><br>";break;
				case 23: $par_i.= "&nbsp;Защита от рубящих ударов: $plus<b>".$modstroke."</b><br>";break;
				case 24: $par_i.= "&nbsp;Защита от карающих ударов: $plus<b>".$modstroke."</b><br>";break;
				case 25: $par_i.= "&nbsp;Защита от отсекающих ударов: $plus<b>".$modstroke."</b><br>";break;
				case 26: $par_i.= "&nbsp;Защита от дробящих ударов: $plus<b>".$modstroke."</b><br>";break;
				case 27: $par_i.= "&nbsp;НР: $plus<b>".$modstroke."</b><br>";break;
				case 28: $par_i.= "&nbsp;Очки действия: $plus<b>".$modstroke."</b><br>";break;
				case 29: $par_i.= "&nbsp;Мана: $plus<b>".$modstroke."</b><br>";break;
				case 30: $par_i.= "&nbsp;Мощь: $plus<b>".$modstroke."</b><br>";break;
				case 31: $par_i.= "&nbsp;Проворность: $plus<b>".$modstroke."</b><br>";break;
				case 32: $par_i.= "&nbsp;Везение: $plus<b>".$modstroke."</b><br>";break;
				case 33: $par_i.= "&nbsp;Здоровье: $plus<b>".$modstroke."</b><br>";break;
				case 34: $par_i.= "&nbsp;Разум: $plus<b>".$modstroke."</b><br>";break;
				case 35: $par_i.= "&nbsp;Сноровка: $plus<b>".$modstroke."</b><br>";break;
				case 36: $par_i.= "&nbsp;Владение мечами: $plus<b>".$modstroke."%</b><br>";break;
				case 37: $par_i.= "&nbsp;Владение топорами: $plus<b>".$modstroke."%</b><br>";break;
				case 38: $par_i.= "&nbsp;Владение дробящим оружием: $plus<b>".$modstroke."%</b><br>";break;
				case 39: $par_i.= "&nbsp;Владение ножами: $plus<b>".$modstroke."%</b><br>";break;
				case 40: $par_i.= "&nbsp;Владение метательным оружием: $plus<b>".$modstroke."%</b><br>";break;
				case 41: $par_i.= "&nbsp;Владение алебардами и копьями: $plus<b>".$modstroke."%</b><br>";break;
				case 42: $par_i.= "&nbsp;Владение посохами: $plus<b>".$modstroke."%</b><br>";break;
				case 43: $par_i.= "&nbsp;Владение экзотическим оружием: $plus<b>".$modstroke."%</b><br>";break;
				case 44: $par_i.= "&nbsp;Владение двуручным оружием: $plus<b>".$modstroke."%</b><br>";break;
				case 45: $par_i.= "&nbsp;Магия огня: $plus<b>".$modstroke."%</b><br>";break;
				case 46: $par_i.= "&nbsp;Магия воды: $plus<b>".$modstroke."%</b><br>";break;
				case 47: $par_i.= "&nbsp;Магия воздуха: $plus<b>".$modstroke."%</b><br>";break;
				case 48: $par_i.= "&nbsp;Магия земли: $plus<b>".$modstroke."%</b><br>";break;
				case 49: $par_i.= "&nbsp;Сопротивление магии огня: $plus<b>".$modstroke."%</b><br>";break;
				case 50: $par_i.= "&nbsp;Сопротивление магии воды: $plus<b>".$modstroke."%</b><br>";break;
				case 51: $par_i.= "&nbsp;Сопротивление магии воздуха: $plus<b>".$modstroke."%</b><br>";break;
				case 52: $par_i.= "&nbsp;Сопротивление магии земли: $plus<b>".$modstroke."%</b><br>";break;
				case 53: $par_i.= "&nbsp;Воровство: $plus<b>".$modstroke."%</b><br>";break;
				case 54: $par_i.= "&nbsp;Осторожность: $plus<b>".$modstroke."%</b><br>";break;
				case 55: $par_i.= "&nbsp;Скрытность: $plus<b>".$modstroke."%</b><br>";break;
				case 56: $par_i.= "&nbsp;Наблюдательность: $plus<b>".$modstroke."%</b><br>";break;
				case 57: $par_i.= "&nbsp;Торговля: $plus<b>".$modstroke."%</b><br>";break;
				case 58: $par_i.= "&nbsp;Странник: $plus<b>".$modstroke."%</b><br>";break;
				case 59: $par_i.= "&nbsp;Рыболов: $plus<b>".$modstroke."%</b><br>";break;
				case 60: $par_i.= "&nbsp;Лесоруб: $plus<b>".$modstroke."%</b><br>";break;
				case 61: $par_i.= "&nbsp;Ювелирное дело: $plus<b>".$modstroke."%</b><br>";break;
				case 62: $par_i.= "&nbsp;Самолечение: $plus<b>".$modstroke."%</b><br>";break;
				case 63: $par_i.= "&nbsp;Оружейник: $plus<b>".$modstroke."%</b><br>";break;
				case 64: $par_i.= "&nbsp;Доктор: $plus<b>".$modstroke."%</b><br>";break;
				case 65: $par_i.= "&nbsp;Самолечение: $plus<b>".$modstroke."%</b><br>";break;
				case 66: $par_i.= "&nbsp;Быстрое восстановление маны: $plus<b>".$modstroke."%</b><br>";break;
				case 67: $par_i.= "&nbsp;Лидерство: $plus<b>".$modstroke."%</b><br>";break;
				case 68: $par_i.= "&nbsp;Алхимия: $plus<b>".$modstroke."%</b><br>";break;
				case 69: $par_i.= "&nbsp;Развитие горного дела: $plus<b>".$modstroke."%</b><br>";break;
				case 70: $par_i.= "&nbsp;Травничество: $plus<b>".$modstroke."%</b><br>";break;
				case 71: $par_i.= "&nbsp;<font color=#BB0000>Коэффициент: $plus<b>".$modstroke."%</b></font><br>";break;
				case 'expbonus': 
					$par_i.= "&nbsp;Бонус опыта: <font color=#BB0000>$plus<b>".$modstroke."%</b></font><br>";
					$par_i.= "&nbsp;Максимальный опыт: <font color=#BB0000>$plus<b>".$modstroke."%</b></font><br>";
				break;
				case 'massbonus': $par_i.= "&nbsp;Масса: <font color=#BB0000>$plus<b>".$modstroke."</b></font><br>";break;
                               case 105: $par_i.= "&nbsp;Разделка добычи: $plus<b>".$modstroke."%</b><br>";break;
			}			
		}
		if($ITEM['damage_mod']){
			$dmodarr = array(1=>'&nbsp;Урон огнем',2=>'&nbsp;Урон льдом',3=>'&nbsp;Вампиризм',4=>'&nbsp;Лечение',5=>'&nbsp;Водой');
			$dmgm=explode("|",$ITEM['damage_mod']);
			foreach($dmgm as $val){
				$dmod=explode("@",$val);
				switch($dmod[0]){
					case 1: $par_i .= $dmodarr[$dmod[0]].': <b><font color="#B00000">'.$dmod[1].'</b></font><br>'; break;
					case 2: $par_i .= $dmodarr[$dmod[0]].': <b><font color="#000099">'.$dmod[1].'</b></font><br>'; break;
					case 3: $par_i .= $dmodarr[$dmod[0]].': <b><font color="#6633CC">'.$dmod[1].'</b></font><br>'; break;
					case 4: $par_i .= $dmodarr[$dmod[0]].': <b><font color="#FFBB88">'.$dmod[1].'</b></font><br>'; break;
                                        case 5: $par_i .= $dmodarr[$dmod[0]].': <b><font color="#0000FF">'.$dmod[1].'</b></font><br>'; break;
         
				}
			}				
		}
		$immunes = explode("|",$ITEM['immunes']);
		$immunes_arr = array(
			0=>'&nbsp;<b><font color="#993399">Иммунитет к огню.</b></font><br>',
			1=>'&nbsp;<b><font color="#993399">Иммунитет к льду.</b></font><br>',
			2=>'&nbsp;<b><font color="#993399">Иммунитет к вампиризму.</b></font><br>',
			3=>'&nbsp;<b><font color="#993399">Иммунитет к яду.</b></font><br>',
			4=>'&nbsp;<b><font color="#993399">Иммунитет к физическому урону.</b></font><br>'
		);
		foreach($immunes as $key=>$val){
			if($val==1){
				$par_i .= $immunes_arr[$key];
			}
		}
		//return $par_i;
	//}
	//требования
	//if($need and $plstt and $ITEM['level']>=0 and $Imass>=0){
		foreach ($need as $value) {
			$treb=explode("@",$value);
			if($treb[0]==72){
				$treb[1]=$ITEM['level'];
			}
			if($treb[0]==71){
				$treb[1]=$ITEM['massa'];
				if($mass-$plstt[71]<$treb[1]){
					$treb[1]="<font color=#cc0000>$treb[1]</font>";
				}
			}
			if($treb[0]!=28 and $treb[0]!=71){
				if($plstt[$treb[0]]<$treb[1]){
					$treb[1]="<font color=#cc0000>$treb[1]</font>";
					$tr_b[1]=1;
				}
			}
			switch($treb[0]){
				case 28: $tr_b[0].="&nbsp;Очки действия: <b>$treb[1]</b><br>";break;
				case 30: $tr_b[0].="&nbsp;Мощь: <b>$treb[1]</b><br>";break;
				case 31: $tr_b[0].="&nbsp;Проворность: <b>$treb[1]</b><br>";break;
				case 32: $tr_b[0].="&nbsp;Везение: <b>$treb[1]</b><br>";break;
				case 33: $tr_b[0].="&nbsp;Здоровье: <b>$treb[1]</b><br>";break;
				case 34: $tr_b[0].="&nbsp;Разум: <b>$treb[1]</b><br>";break;
				case 35: $tr_b[0].="&nbsp;Сноровка: <b>$treb[1]</b><br>";break;
				case 36: $tr_b[0].="&nbsp;Владение мечами: <b>$treb[1]</b><br>";break;
				case 37: $tr_b[0].="&nbsp;Владение топорами: <b>$treb[1]</b><br>";break;
				case 38: $tr_b[0].="&nbsp;Владение дробящим оружием: <b>$treb[1]</b><br>";break;
				case 39: $tr_b[0].="&nbsp;Владение ножами: <b>$treb[1]</b><br>";break;
				case 40: $tr_b[0].="&nbsp;Владение метательным оружием: <b>$treb[1]</b><br>";break;
				case 41: $tr_b[0].="&nbsp;Владение алебардами и копьями: <b>$treb[1]</b><br>";break;
				case 42: $tr_b[0].="&nbsp;Владение посохами: <b>$treb[1]</b><br>";break;
				case 43: $tr_b[0].="&nbsp;Владение экзотическим оружием: <b>$treb[1]</b><br>";break;
				case 44: $tr_b[0].="&nbsp;Владение двуручным оружием: <b>$treb[1]</b><br>";break;
				case 45: $tr_b[0].="&nbsp;Магия огня: <b>$treb[1]</b><br>";break;
				case 46: $tr_b[0].="&nbsp;Магия воды: <b>$treb[1]</b><br>";break;
				case 47: $tr_b[0].="&nbsp;Магия воздуха: <b>$treb[1]</b><br>";break;
				case 48: $tr_b[0].="&nbsp;Магия земли: <b>$treb[1]</b><br>";break;
				case 53: $tr_b[0].="&nbsp;Воровство: <b>$treb[1]</b><br>";break;
				case 54: $tr_b[0].="&nbsp;Осторожность: <b>$treb[1]</b><br>";break;
				case 55: $tr_b[0].="&nbsp;Скрытность: <b>$treb[1]</b><br>";break;
				case 56: $tr_b[0].="&nbsp;Наблюдательность: <b>$treb[1]</b><br>";break;
				case 57: $tr_b[0].="&nbsp;Торговля: <b>$treb[1]</b><br>";break;
				case 58: $tr_b[0].="&nbsp;Странник: <b>$treb[1]</b><br>";break;
				case 59: $tr_b[0].="&nbsp;Рыболов: <b>$treb[1]</b><br>";break;
				case 60: $tr_b[0].="&nbsp;Лесоруб: <b>$treb[1]</b><br>";break;
				case 61: $tr_b[0].="&nbsp;Ювелирное дело: <b>$treb[1]</b><br>";break;
				case 62: $tr_b[0].="&nbsp;Самолечение: <b>$treb[1]</b><br>";break;
				case 63: $tr_b[0].="&nbsp;Оружейник: <b>$treb[1]</b><br>";break;
				case 64: $tr_b[0].="&nbsp;Доктор: <b>$treb[1]</b><br>";break;
				case 65: $tr_b[0].="&nbsp;Самолечение: <b>$treb[1]</b><br>";break;
				case 66: $tr_b[0].="&nbsp;Быстрое восстановление маны: <b>$treb[1]</b><br>";break;
				case 67: $tr_b[0].="&nbsp;Лидерство: <b>$treb[1]</b><br>";break;
				case 68: $tr_b[0].="&nbsp;Алхимия: <b>$treb[1]</b><br>";break;
				case 69: $tr_b[0].="&nbsp;Развитие горного дела: <b>$treb[1]</b><br>";break;
				case 70: $tr_b[0].="&nbsp;Травничество: <b>$treb[1]</b><br>";break;
				case 71: $tr_b[0].="&nbsp;Масса: <b>$treb[1]</b><br>";break;
				case 72: $tr_b[0].="&nbsp;Уровень: <b>$treb[1]</b><br>";break;
				case 105: $tr_b[0].="&nbsp;Разделка добычи: <b>$treb[1]</b><br>";break;
			}
		}
		$arr[0] = $par_i;
		$arr[1] = $tr_b;
		$arr[2] = $iz;
		return $arr;
	//}	
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

if (empty($_GET["i"])) {
$_GET["i"]=(iconv("UTF-8","Windows-1251",urldecode($_SERVER['QUERY_STRING']))?iconv("UTF-8","Windows-1251",urldecode($_SERVER['QUERY_STRING'])):urldecode($_SERVER['QUERY_STRING']));
}
$_GET["i"] = str_replace("'","",$_GET["i"]);
$ITEM = GetItem(urldecode($_GET['i']));
function blocks($bl){
	if($bl!="") {
	switch($bl)
       	{
            case 40: echo "<font class=weaponch><b><font color=#cc0000>Блок 1-ой точки</font></b><br>"; break;
            case 70: echo "<font class=weaponch><b><font color=#cc0000>Блок 2-х точек</font></b><br>"; break;
	    	case 90: echo "<font class=weaponch><b><font color=#cc0000>Блок 3-х точек</font></b><br>"; break;
    	}
		}
}
if(empty($ITEM)){
echo'<HTML><HEAD>
<TITLE>Moon Light - Жизнь cильнейших.</TITLE>
<SCRIPT  LANGUAGE="JavaScript" src="./js/ft_v01.js"></SCRIPT>
<SCRIPT  LANGUAGE="JavaScript" src="./js/png.js"></SCRIPT>
<LINK href="/css/game.css" rel="STYLESHEET" type="text/css">
<META Http-Equiv=CONTENT-TYPE Content="text/html; charset=windows-1251">
<META Http-Equiv=PRAGMA Content="NO-CACHE">
<META Http-Equiv=CACHE-CONTROL Content="NO-CACHE, NO-STORE">
<META Http-Equiv=EXPIRES Content="0">
</HEAD>Ошибка, такого предмета нет в базе.';
}
elseif($ITEM['dd_price']){
echo'<HTML><HEAD>
<TITLE>Moon Light  - Жизнь cильнейших.</TITLE>
<SCRIPT  LANGUAGE="JavaScript" src="./js/ft_v01.js"></SCRIPT>
<SCRIPT  LANGUAGE="JavaScript" src="./js/png.js"></SCRIPT>
<LINK href="/css/game.css" rel="STYLESHEET" type="text/css">
<META Http-Equiv=CONTENT-TYPE Content="text/html; charset=windows-1251">
<META Http-Equiv=PRAGMA Content="NO-CACHE">
<META Http-Equiv=CACHE-CONTROL Content="NO-CACHE, NO-STORE">
<META Http-Equiv=EXPIRES Content="0">
</HEAD>Невозможно получить параметры предмета, который является артефактом.';
}
else{
echo'<HTML><HEAD>
<SCRIPT  LANGUAGE="JavaScript" src="./js/ft_v01.js"></SCRIPT>
<SCRIPT  LANGUAGE="JavaScript" src="./js/png.js"></SCRIPT>
<TITLE>['.$ITEM['name'].'] Информация предмета игровой амуниции Мира Смерти.. - Браузерная Онлайн игра Moon Light ™..</TITLE>
<LINK href="/css/game.css" rel="STYLESHEET" type="text/css">
<META Http-Equiv=CONTENT-TYPE Content="text/html; charset=windows-1251">
<META Http-Equiv=PRAGMA Content="NO-CACHE">
<META Http-Equiv=CACHE-CONTROL Content="NO-CACHE, NO-STORE">
<META Http-Equiv=EXPIRES Content="0">
</HEAD>
<body>
<table cellpadding=0 cellspacing=0 border=0 width=740>
<tr><td bgcolor=#e0e0e0>
<table cellpadding=3 cellspacing=1 border=0 width=100%>';
$bt=0;$tr_b='';$par_i='';$pararr ='';$m=0;
$pararr = itemparams(0,$ITEM,$player,$plstt,$mass);
$tr_b = $pararr[1][0]; $iz = $pararr[2];//требования
$bt = $pararr[1][1]; //доступность кнопок
$par_i = $pararr[0]; //параметры
echo'
<tr>
<td bgcolor=#f9f9f9>
	<div align=center>
		<img src=/image/weapon/'.$ITEM['gif'].' border=0>
	</div>
</td>
<td width=100% bgcolor=#ffffff valign=top>
	<table cellpadding=0 cellspacing=0 border=0 width=100%>
		<tr>
			<td bgcolor=#ffffff width=100%>
				<font class=weaponch><b>'.$ITEM['name'].'</b><font class=weaponch><br>
				<img src=/image/1x1.gif width=1 height=3></td><td><br><img src=http://seditio.local/image/1x1.gif width=1 height=3
			</td>
		</tr>
		<tr>
			<td colspan=2 width=100%>
				<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td bgcolor=#D8CDAF width=50%>
					<div align=center><font class=invtitle>свойства</div></td><td bgcolor=#B9A05C>
					<img src=/image/1x1.gif width=1 height=1></td><td bgcolor=#D8CDAF width=50%>
					<div align=center><font class=invtitle>требования</div>
				</td></tr>
<tr><td bgcolor=#FCFAF3><font class=weaponch>&nbsp;Цена: <b>'.$ITEM['price'].' Серебро</b><br>';
if($ITEM['slot']==16) {echo "<font class=weaponch><b><font color=#cc0000>Можно одевать на кольчуги</font></b><br>";}
blocks($ITEM['block']);
//============= новая функция вывода параметров вещи => sql_func.php: function itemparams($par,$eff,$modstat,$damage_mod,$iz,$dolg,$slot,$need,$plstt,$itlevel,$itmass).
//Адаптирована под магазины или вывод эффектов мазей. $par,$eff,$modstat,$damage_mod,$iz,$dolg,$slot,$need,$plstt,$itlevel,$itmass - могут быть пустыми. надо передать либо $par либо need
echo $par_i;
//==== END ====
echo'</td><td bgcolor=#B9A05C><img src=/image/1x1.gif width=1 height=1></td><td bgcolor=#FCFAF3><font class=weaponch>'.$tr_b.'</font></td></tr></table></td></tr></table></td></tr></table>';
echo'
<div align=center>
<font class=weaponchdis> ©  Команда «Moon Light  », | Все права защищены 2018-2019.</font>
<SCRIPT language="JavaScript">
NewLinksView();
</SCRIPT>
</div>
</td></tr></table>
</td></tr></table>
</body></html>';
}
?>


