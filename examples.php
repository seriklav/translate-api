<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
include_once 'Yandex_Translate.php';
include_once 'Big_Text_Translate.php';



$translator = new Yandex_Translate();

$text = file_get_contents('text.txt');

//Простой перевод
echo  $translator->yandexTranslate('ru', 'uk', $text);
echo '<br />';
//Получение списков языков
$langPairs = $translator->yandexGetLangsPairs();

print_r($translator->yandexGet_FROM_Langs($langPairs));
echo '<br />';

print_r($translator->yandexGet_TO_Langs($langPairs));
echo '<br />';
//Перевод большого текста

$bigText = file_get_contents('text1.txt');

$piecer = new Big_Text_Translate();
$pArray = $piecer->toBigPieces($bigText);
$outText = '';
foreach ($pArray as $p){
    $outText = $outText.$translator->yandexTranslate('ru', 'uk', $p);
}
echo $outText;