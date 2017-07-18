<?php


namespace app\utilities;


use GuzzleHttp\Client;

class Metaprom extends Site
{

    private $countCompanies;
    private $linksPage;


    public function __construct($link, Client $client)
    {
        parent::__construct($link, $client);
        $this->countCompanies = $this->countCompanies();
        $this->countPage = $this->countPage();
        $this->linksPage = $this->linksPage();

    }

    private function countCompanies()
    {
        $document = $this->generalDocument;
        $block = $document->find("td.bord_tb");
        $block = strip_tags($block);
        return (float)preg_replace("/[^0-9]/", '', substr($block, 0, strlen($block) / 2));
    }

    private function countPage()
    {
        return ceil($this->countCompanies / 50);
    }

    private function linksPage()
    {
        $links = [];
        $count = 50;
        for ($i = 0; $i < $this->countPage; $i++) {
            if ($i == 0) {
                array_push($links, $this->link);
            } else {
                array_push($links, $this->link."start$count/");
                $count = $count + 50;
            }
        }
        return $links;
    }

    private function listCompanies()
    {
        $document = $this->generalDocument;
        $block = $document->find("div.firm_name");
        $hrefs = [];
        foreach ($block->find("a") as $org) {
            $pq = pq($org);
            array_push($hrefs, $pq->attr('href'));
        }
        return $hrefs;
    }

    public function pager()
    {
        $i = 0;
        foreach ($this->linksPage as $page){
            if ($i == 0){
                $this->linker();
            }else{
                $this->generalDocument = $this->getPhpQueryDoc($page);
                $this->linker();
            }
        }
    }

    private function linker($page)
    {
        foreach ($this->listCompanies() as $company){
            $this->saveCompany($company);
        }
    }

    public function saveCompany($href)
    {

        //print_r(iconv($this->detect_encoding($this->get_xml_page($href)), 'cp1251', $this->get_xml_page($href)));
        //$document = \phpQuery::newDocumentHTML(mb_convert_encoding($this->get_xml_page($href), 'utf-8'));
        //print_r(iconv('cp1251', 'utf-8', strval($document)));
       // $document = iconv('windows-1251', 'utf-8', $document);
        $document = $this->getPhpQueryDoc($href);
        $table = $document->find("table.maintable");
        $items = $table->find("tr");
        foreach ($items as $item){
            $pq = pq($item);
            //print_r(mb_detect_encoding($pq->find('td')));
            echo $text = strip_tags($pq->find("td"));
            //print_r($text);
            //echo $this->detect_encoding($text);
            //echo $text = mb_convert_encoding(mb_convert_encoding($text, 'UTF-8', 'windows-1251'), "windows-1251", "UTF-8");
           // echo $first = mb_convert_encoding($pq->find("td"), $this->detect_encoding($pq->find("td")), 'UTF-8');
            //print_r(mb_detect_encoding($text));


        }
    }

    private function get_xml_page($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $page = curl_exec($ch);
        curl_close($ch);
        return $page;
    }



    private function detect_encoding($string, $pattern_size = 50)
    {
        $list = array('cp1251', 'utf-8', 'ascii', '855', 'KOI8R', 'ISO-IR-111', 'CP866', 'KOI8U');
        $c = strlen($string);
        if ($c > $pattern_size)
        {
            $string = substr($string, floor(($c - $pattern_size) /2), $pattern_size);
            $c = $pattern_size;
        }

        $reg1 = '/(\xE0|\xE5|\xE8|\xEE|\xF3|\xFB|\xFD|\xFE|\xFF)/i';
        $reg2 = '/(\xE1|\xE2|\xE3|\xE4|\xE6|\xE7|\xE9|\xEA|\xEB|\xEC|\xED|\xEF|\xF0|\xF1|\xF2|\xF4|\xF5|\xF6|\xF7|\xF8|\xF9|\xFA|\xFC)/i';

        $mk = 10000;
        $enc = 'ascii';
        foreach ($list as $item)
        {
            $sample1 = @iconv($item, 'cp1251', $string);
            $gl = @preg_match_all($reg1, $sample1, $arr);
            $sl = @preg_match_all($reg2, $sample1, $arr);
            if (!$gl || !$sl) continue;
            $k = abs(3 - ($sl / $gl));
            $k += $c - $gl - $sl;
            if ($k < $mk)
            {
                $enc = $item;
                $mk = $k;
            }
        }
        return $enc;
    }



}