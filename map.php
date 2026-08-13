
<?php
// Запуск сессий

 
// Подгружаем конфиги
include($_SERVER["DOCUMENT_ROOT"]."/includes/config.inc.php");
 
// Стандартные данные
$mapData = array(
    'tableW'    => 3000,
    'tableH'    => 200,
    'startX'    => 1,
    'startY'    => 1,
    'endX'      => 30,
    'endY'      => 20,
 
);
if( isset($_GET['map']) ){
    $mapData = array(
        'tableW'    => 3000,
        'tableH'    => 2000,
        'startX'    => 100,
        'startY'    => 1,
        'endX'      => 30,
        'endY'      => 20
    );
}
 
// Загружаем информацию о карте
$allmap = mysqli_query($GLOBALS['db_link'],"SELECT * FROM `nature`;");
while($r = mysqli_fetch_assoc($allmap)){
    $naturexy[] = $r['x'].'@'.$r['y'];
}
if(!isset($_GET['ajax'])){
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" id="html">
<head>
<title>Карта - Природы</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta http-equiv="Cache-Control" content="No-Cache" />
<meta http-equiv="Pragma" content="No-Cache" />
<meta http-equiv="Expires" content="0" />
<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/js/mousewheel.js"></script>
<script type="text/javascript" src="/js/ft_v01.js"></script>
<script type="text/javascript" src="/js/t_v01.js"></script>
<script type="text/javascript" src="/js/png.js"></script>
<link rel="stylesheet" type="text/css" href="/css/mapinfo.css" />
<link rel="stylesheet" type="text/css" href="/css/game.css" />
</head>
<body>
<style>
#viewMap{
    position: relative;
    overflow: hidden;
    display: none;
    z-index: 5;
    left: 0px;
    top: 0px
}
</style>
<script>
var mapID = '<?php echo ( isset($_GET['map']) ? 'map=' . intval($_GET['map']) . '&' : '' ); ?>';
function resizer(){
    $("#viewMap").css({
        "height": $(window).height() + "px",
        "width": $(window).width() + "px",
        "display": "block"
    });
    $("#viewIMG").css({
        "height": $(window).height() + "px",
        "width": $(window).width() + "px"
    });
}
function loadMap(url){
    $("#fullmap").load("/map.php?ajax=true&" + mapID + url);
}
$(document).ready(function(){
    var last_click_x = last_click_y = 0;
    var moving = false;
   
    $( window ).resize(function() {
        resizer();
    });
   
    $("#viewMap").mousedown(function(e){
        if (e.which != 1) return;
        $("#viewIMG").show();
        moving = true;
        last_click_x = e.pageX;
        last_click_y = e.pageY;
        return false;
    });
   
    $("#viewMap").mouseup(function(e){
        if (e.which!=1) return;
        $("#viewIMG").hide();
        moving = false;
        return false;
    });
   
    $(window).mouseleave(function() {
        moving = false;
        return false;
    });
   
    $("#viewMap").mousemove(function(e){
        if (moving != true) return;
        $("#viewMap").scrollTop($("#viewMap").scrollTop()+last_click_y-e.pageY);
        $("#viewMap").scrollLeft($("#viewMap").scrollLeft()+last_click_x-e.pageX);
        last_click_x = e.pageX;
        last_click_y = e.pageY;
    });
    resizer();
});
mapLinks = function(text){
    d.write('<table cellpadding="0" cellspacing="0" align="center" width="100%" border="0"><tr><td width="25%" align="left" style="position:relative;">'+PNGImage('forum/design/b1.gif','forum/design/b1.png',158,42)+'<div id="leftCounter"></div></td><td width="25%" align="center" valign="bottom"><font color="red">' + text + '</font></td><td width="25%" align="right" style="position:relative;">'+PNGImage('forum/design/b2.gif','forum/design/b2.png',158,42)+'<div id="rightCounter"></div></td></tr></table>');
}
</script>
<div id="viewMap">
    <img src="/image/1x1.gif" style="position:fixed;left:0px;top:0px;cursor:-moz-grabbing;cursor:-webkit-grabbing;display:none;" id="viewIMG" />
    <div style="position:fixed;right:0px;left:0px;top:0px;text-align:center;z-index:6;">
        <table border=0 cellpadding="0" style="background: #ffffff;border-bottom-left-radius: 20px;border-bottom-right-radius: 20px;" cellspacing="0" align="center" width="500" height="20">
            <tr>
                <td align="center" width="100%">
                    <a href="/map.php" style="color:#cc0000"><b>Карта острова Баруса</b></a> 
                </td>
            </tr>
        </table>
        <table border="0" cellpadding="0" style="background: #ffffff;border-bottom-left-radius: 20px;border-bottom-right-radius: 20px;" cellspacing="1" align="center" width="250" height="20">
            <tr align="center">
                <td align="center" width="100%">
                    <a href="javascript:loadMap('info=1');" style="color:black;"><b>Боты<b></a> | <a href="javascript:loadMap('info=2');" style="color:green;"><b>Травы</b></a> | <a href="javascript:loadMap('info=3');" style="color:brown;"><b>Леса</b></a> | <a href="javascript:loadMap('info=4');" style="color:blue;"><b>Рыба</b></a>
      			
</td>
            </tr>
        </table>
    </div>
    <div id="fullmap"><?php
    }
    echo'<table border="1" cellpadding="0" cellspacing="0" align="center" width="' . $mapData['tableW'] . '" height="' . $mapData['tableH'] . '">';
    for( $y = $mapData['startY']; $y <= $mapData['endY']; $y++ ){
        echo'<tr>';
        for( $x = $mapData['startX']; $x <= $mapData['endX']; $x++ ){
            echo'<td background="http://seditio.local//image/wmap/map/'.( in_array(($x.'@'.$y), $naturexy) ? 'day' : 'night' ).'/'.$y.'/'.$x.'_'.$y.'.gif" valign=top height="100" width="100">';
                echo'<table border="0" cellpadding="0" cellspacing="0" width=100% height=100% style="background:none">';
               
                    // Этот шлак надо переписать
                    if($_GET['info']==1){
                        $bot=mysqli_fetch_assoc(mysqli_query($GLOBALS['db_link'],"SELECT SQL_CACHE * FROM `nature_bots` WHERE `x`=".$x." AND `y`=".$y.";"));
                        if($bot['x']==$x and $bot['y']==$y and ($x+$y)!=0){
                            echo'<tr><td valign=top align=right><b class=levelbot>'.$bot['lvlmin'].'-'.$bot['lvlmax'].'</b></td></tr>';
						if($bot['lvlmax']>=6){
							$b=$bot['lvlmin'];
							$c=$bot['lvlmax'];
							while($b<=$c){
								if($b==1){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Паук </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Паук" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==2){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Скилет </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Скилет" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==3){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Лускар </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Лускар" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==4){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Архангел </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Архангел" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==5){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Арамор </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Арамор" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==6){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Дикий Волк </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Дикий Волк" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==7){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Гиена </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?ДГиена" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==8){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Фея </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Фея" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==9){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Восставший Командир </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Восставший Командир" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==10){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Ядовитый паук </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Ядовитый паук" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==11){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Крыса </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Крыса" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==12){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Кристальный Рыцарь </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Кристальный Рыцарь" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==14){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Гоблин </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Гоблин" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==13){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Лесной Ангел </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Лесной Ангел" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==15){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Орк </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Орк" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==16){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Берсерк </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Берсерк" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==17){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Ледяной Голем </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Ледяной Голем" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==18){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Демон </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Демон" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==19){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Лунарка </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Лунарка" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==20){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Странник </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Странник" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==21){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Кентавр </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Кентавр" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==22){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Оборотень </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Оборотень" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==23){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Циклоп </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Циклоп" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==24){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Страж Леса </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Страж Леса" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==25){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Дракон </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Дракон" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==26){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Минотавр </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Восставший Командир" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==27){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Бессмертный орк </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Бессмертный орк" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==28){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Технодрон </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Технодрон" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==29){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Диабло </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Диабло" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==30){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Повелитель Огня </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Повелитель огня" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==31){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Повелитель Излечения </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Повелитель изличения" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==32){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Повелитель Льда </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Повелитель льда" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==33){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Повелитель Вампиризма </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Повелитель Вамперизма" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==34){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Мумия </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Мумия" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==35){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Саблезубый Тигр </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Саблезубый Тигр" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==36){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Болотный Тролль </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Горец из Атинии" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==37){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Темный Эльф </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Болотный троль" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==38){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Вепрь </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Вепрь" target="_blank"><b class=boss>'.$boss.'</b></td></tr>';
								}
								if($b==39){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Цербер </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Цербер" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==40){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Паладин </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Паладин" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
								}
								if($b==41){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Шаман </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Шаман" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
                                                                }
								if($b==42){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Андромеда </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Андромеда" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
                                                                }
								if($b==43){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Бог Молнии </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Бог Молнии" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
                                                                }
								if($b==44){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Царь Тритон </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Царь Тритон" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
                                                                }
								if($b==60){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Диабло </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Диабло" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
                                                                }
								if($b==45){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Дед Мороз </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Дед Мороз" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
                                                                }
								if($b==46){
									$boss="<font style=\"color: red;font-weight: bold;background:white;\"><b color=red>&nbsp;Снегурочка </b></font>";
									echo'<tr><td valign=top align=center><a href="ipers.php?Снегурочка" target="_blank"><b class=boss>'.$boss.'</b></a></td></tr>';
                                                                }

                                    $b++;
                                }
                            }
                        }
                    }
                    if($_GET['info']==2){
                        $grasssql=mysqli_fetch_assoc(mysqli_query($GLOBALS['db_link'],"SELECT * FROM `nature_grass` WHERE `x`='".$x."' AND `y`='".$y."' LIMIT 1;"));
                        if($grasssql){
                            $grass = explode("|",$grasssql['grass']);
                            $tr=0;
                            foreach ($grass as $val){
                                $tr++;
                                $grn=explode("@",$val);
                                $allgrass = mysqli_fetch_assoc(mysqli_query($GLOBALS['db_link'],"SELECT `items`.`name` FROM `items` WHERE `id`='".$grn[0]."' LIMIT 1;"));
                                echo'<tr><td valign=top align=center><a href="iteminfo.php?'.$allgrass['name'].'" target="_blank"><b class=boss>'.$allgrass['name'].'</b></a></td></tr>';
                                if($tr>7){break;}
                            }  
                        }      
                    }
                    if($_GET['info']==3){
                        $grasssql=mysqli_fetch_assoc(mysqli_query($GLOBALS['db_link'],"SELECT * FROM `nature_les` WHERE `x`='".$x."' AND `y`='".$y."' LIMIT 1;"));
                        if($grasssql){
                            $grass = explode("|",$grasssql['grass']);
                            $tr=0;
                            foreach ($grass as $val){
                                $tr++;
                                $grn=explode("@",$val);
                                $allgrass = mysqli_fetch_assoc(mysqli_query($GLOBALS['db_link'],"SELECT `items`.`name` FROM `items` WHERE `id`='".$grn[0]."' LIMIT 1;"));
                                echo'<tr><td valign=top align=center><a href="iteminfo.php?'.$allgrass['name'].'" target="_blank"><b class=boss>'.$allgrass['name'].'</b></a></td></tr>';
                                if($tr>7){break;}
                            }
                        }
                    }
                    if($_GET['info']==4){
                        $grasssql=mysqli_fetch_assoc(mysqli_query($GLOBALS['db_link'],"SELECT * FROM `nature_fish` WHERE `x`='".$x."' AND `y`='".$y."' LIMIT 1;"));
                        if($grasssql){
                            $grass = explode("|",$grasssql['grass']);
                            $tr=0;
                            foreach ($grass as $val){
                                $tr++;
                                $grn=explode("@",$val);
                                $allgrass = mysqli_fetch_assoc(mysqli_query($GLOBALS['db_link'],"SELECT `items`.`name` FROM `items` WHERE `id`='".$grn[0]."' LIMIT 1;"));
                                echo'<tr><td valign=top align=center><a href="iteminfo.php?'.$allgrass['name'].'" target="_blank"><b class=boss>'.$allgrass['name'].'</b></a></td></tr>';
                                if($tr>7){break;}
                            }  
                        }
                    }
                    // Отображаем текущюю клетку
                    echo'<tr>';
                        echo'<td valign="bottom" align="left">';
                            echo'<b class="kletka">&nbsp;' . $x . ':' . $y . '&nbsp;</b>';
                        echo'</td>';
                    echo'</tr>';
                echo'</table>';
            echo'</td>';
        }
        echo'</tr>';
    }
    echo'</table>';
    if(!isset($_GET['ajax'])){
    ?></div>
    <div style="position:fixed;right:0px;left:0px;bottom:0px;text-align:center;">
        <script language="JavaScript">mapLinks('<font class="weaponchdis" color="red">&copy;  Copyright  2018<br />Все права защищены.</font>');</script>
    </div>
</div>
</body>
</HTML><?php
}