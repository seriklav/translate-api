<?php
class Big_Text_Translate {
    /**
     * @var int - максимальное число символов для отправки переводчику
     */
    public $symbolLimit = 2000;

    /**
     * @var int - максимально число символов, переводимое за время до таймаута
     */
    public $totalSymbolLimit = 60000;

    /**
     * @var - символы, по которым текст делится на предложения
     */
    public $sentensesDelimiter = '.';

    protected function toSentenses ($text) {
        $sentArray = explode($this->sentensesDelimiter, $text);
        return $sentArray;
    }

    /**
     * Разделение текста на массив больших кусков
     * @param  $text
     * @return
     */

    public function toBigPieces ($text) {
        $sentArray = $this->toSentenses($text);
        $i = 0;
        $bigPiecesArray[0] = '';
        for ($k = 0; $k < count($sentArray); $k++) {
            $bigPiecesArray[$i] .= $sentArray[$k].$this->sentensesDelimiter;
            if (strlen($bigPiecesArray[$i]) > $this->symbolLimit){
                $i++;
                $bigPiecesArray[$i] = '';
            }
        }

        return $bigPiecesArray;
    }

    /**
     * Склеивание текста. Собственно, можно обойтись без этой ф-и (см. примеры)
     * @param array $bigPiecesArray
     * @return string
     */
    public function fromBigPieces (array $bigPiecesArray) {
        return implode($bigPiecesArray);
    }

    /**
     * Проверка текста на превышение максимального размера, при первышении - false
     * @param  $text
     * @return bool
     */
    public function symbolCountControl ($text){
        return true;
        if (strlen($text) > $this->totalSymbolLimit){
            return false;
        }
    }

}
 
