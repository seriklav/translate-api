<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
include_once 'Yandex_Translate.php';
include_once 'Big_Text_Translate.php';

$translator = new Yandex_Translate();

//Ниже для экспериментов раскомментируйте нужное

//Массив языков, с которых можно переводить
echo '<pre>';
$pairs = $translator->yandexGetLangsPairs();
//print_r($pairs);
echo '</pre>';

//Массив языков, на которые можно переводить
echo '<pre>';
$to = $translator->yandexGet_FROM_Langs();
//print_r($to);
echo '</pre>';


//Перевод

$text = file_get_contents('text.txt');

//Это повторение значения свойства по умолчанию - см. код класса
$translator->eolSymbol = '<br />';

$translatedText = $translator->yandexTranslate('ru', 'uk', $text);

//echo $translatedText;


//Работа с большими текстами

$bigText = file_get_contents('text_big.txt');
$textArray = Big_Text_Translate::toBigPieces($bigText);

$numberOfTextItems = count($textArray);

foreach ($textArray as $key=>$textItem){

    //Показываем прогресс перевода
    echo 'Переведен фрагмент '.$key.' из '.$numberOfTextItems.'<br />';
    flush();

    $translatedItem = $translator->yandexTranslate('ru', 'uk', $textItem);
    $translatedArray[$key] = $translatedItem;
}

$translatedBigText = Big_Text_Translate::fromBigPieces($translatedArray);

echo $translatedBigText;