<?php


namespace app\utilities;


class MoscowMapParser
{
    const SITE_URL = 'https://www.moscowmap.ru';
    const OPTION = CURLOPT_RETURNTRANSFER;
    const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36';

    private $link;
    private $listPage;

    private $countPage;

    public function __construct($link)
    {
        $this->link = $link;
        $this->listPage = $this->listPage();
        $this->countPage = $this->countPage();
    }

    private function listPage()
    {
        $ch = curl_init($this->link);
        curl_setopt($ch, CURLOPT_USERAGENT, self::USER_AGENT);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $listPage = curl_exec($ch);
        curl_close($ch);
        return $listPage;
    }
}