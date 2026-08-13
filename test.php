<?php
$Need = "2598;1;Волшебную Тыкву";

$ItemNeed = explode(";",$Need);

$Quest_Name = 'Волшебная ночка.';

$Quest_Desc = 'Отправляетесь в Барус и поговорите со Стариком о Волшебной Тыкве. Срок - 1 день.';

$Quest_No = '"Бууууу...","А ты отважный воин, мне какраз нужен смелый и отчаянный человек для ответственного задания.","Ну что, ты готов меня выслушать?","Для начала тебе надо отправиться в Барус и поговорить со Стариком о волшебной тыкве.","Ну что ты готов отправиться в путешевствие?"';

$Quest_Yes = '"Бууууу! А, это ты. Ну как?"';

$Quest_Get = '"Отправляетесь в Барус и поговорить со Стариком о Волшебной Тыкве"';

$Quest_Status_err = '"Я тебе дал вроде очень легкое задание, а ты и его не выполнил, мда видать я ошибся в тебе!"';

$Quest_Status_ok = '"Вот не ожидал что ты так быстро выполнишь моё поручение!","Ну что настало время умирать?!"';

if($QuestStepThree == true){
	mysqli_query($GLOBALS['db_link'],"UPDATE `quest_completed` SET `que_st`='2' WHERE `que_id`='12' AND `usr_id`='".$pers['id']."'");
	mysqli_query($GLOBALS['db_link'],"UPDATE `quest_completed` SET `que_st`='2' WHERE `que_id`='10' AND `usr_id`='".$pers['id']."'");
	mysqli_query($GLOBALS['db_link'],"INSERT INTO `chat` (`time`,`login`,`dlya`,`msg`) VALUES ('".time()."','sys','<".$pers['login'].">','".addslashes("parent.frames['chmain'].add_msg('<font class=chattime>&nbsp;".date("H:i:s")."&nbsp;</font> <font color=000000><font color=#cc0000><b>Системная информация.</b></font> <b>На вас напал Мистер-Хеллоуин.</b></font><BR>'+'');parent.frames['main_top'].location='main.php?QuestBattle=".time()."';")."');");
}