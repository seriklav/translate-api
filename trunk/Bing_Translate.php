<?php
/**
 * Класс для перевода с помощью Bing (Microsoft)
 * http://msdn.microsoft.com/en-us/library/ff512404.aspx
 * Обязательно требуется получение appId
 * Текущий тупо выдран FireBug'ом из виджета (см. вики), код виджета по адресу:
 * http://www.microsofttranslator.com/widget/
 */
class Bing_Translate {
    protected $rootURL = 'http://api.microsofttranslator.com/V2/Ajax.svc';
    protected $translatePath = '/Translate';
    protected $langCodesListPath = '/GetLanguagesForTranslate';
    protected $langNamesListPath = '/GetLanguageNames';

    protected $appId = 'TBD3hh_Lf1ffS7YQhkYmQmybfoDUurFBlsKgC0owiLiQ*';


    /**
     * @var string - символ или тег конца абзаца
     */
    public $eolSymbol = '<br />';

    /**
     * @var string разделитель списка - если нужен чистый список языков
     */
    public $listDelimiter = ', ';

    /**
     * @var string - язык, на котором выводятся полные названия языков
     */
    public $locale = 'ru';

    protected $cURLHeaders = array(
            'User-Agent' => "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0; .NET CLR 2.0.50727)",
            'Accept' => "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
            'Accept-Language' => "ru,en-us;q=0.7,en;q=0.3",
            'Accept-Encoding' => "gzip,deflate",
            'Accept-Charset' => "windows-1251,utf-8;q=0.7,*;q=0.7",
            'Keep-Alive' => '300',
            'Connection' => 'keep-alive',
        );

    protected function bingConnect($path, $transferData = array()) {
        $transferData['appId'] = $this->appId;
        $res = curl_init();
        $url = $this->rootURL.$path.'?'.http_build_query($transferData);
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $this->cURLHeaders,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 30,

        );
        curl_setopt_array($res, $options);
        $response = curl_exec($res);
        curl_close($res);

        return $response;
    }

    /**
     * Получает "сырой" список кодов языков (ru, en, fr ...) в виде, похожем на json
     * параметров нет - полный список получается с сайта
     * @return mixed
     */
    public function bingGetLangCodes() {

        $rawLangCodesList = $this->bingConnect($this->langCodesListPath);

        return $rawLangCodesList;
    }

    /**
     * Получаем список названий языков с учетом локали. Аргумент - список кодов языков,
     * сырой или чистый - без разницы. Можно для одного языка получить название, например
     * bingGetlangNames('uk') - получим Украинский при locale=ru и Ukrainian при locale=en
     * Список получаем "сырой", для использования нужо очистить ф-ей clearList()
     *
     * @param  $rawLangCodesList
     * @return mixed
     */
    public function bingGetlangNames($rawLangCodesList) {

        $transferData = array(
            'locale' => $this->locale,
            'languageCodes' => $rawLangCodesList,
        );

        $rawLangNamesList = $this->bingConnect($this->langNamesListPath, $transferData);

        return $rawLangNamesList;

    }

    /**
     * @param  $rawLangList - сырой (с кавычками и пр.) список языков или их кодов
     * @return mixed - очищенный от посторонних символов список, разделитель - listDelimiter
     */
    public function clearList ($rawLangList){

        $rawList = str_replace(array('["', '"]', '"'), '', $rawLangList);

        return str_replace(',', $this->listDelimiter, $rawList);
    }
    //Делаем массив из списка
    public function listToArray ($rawLangsList){
        $list = $this->clearList($rawLangsList);
        $arrayOfLangs = explode($this->listDelimiter, $list);

        return $arrayOfLangs;
    }

    /**
     * Собственно перевод
     *
     * @param  $fromLang - с какого языка (код, 'ru' например)
     * @param  $toLang - на какой язык (код, 'en' например)
     * @param  $text - переводимый контент
     * @return mixed - пригодный к употреблению перевод. Следите за eolSymbol
     */
    public function bingTranslate($fromLang, $toLang, $text){
        $transferData = array(
            'from' => $fromLang,
            'to' => $toLang,
            'text' => $text,
        );

        $rawTranslate = $this->bingConnect($this->translatePath, $transferData);

        $result = str_replace('\u000d\u000a', $this->eolSymbol, rtrim(mb_substr($rawTranslate,4), '"'));

        return $result;
    }





}
 
