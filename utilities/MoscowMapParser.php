<?php


namespace app\utilities;

use GuzzleHttp\Client;

class MoscowMapParser
{
    const SITE_URL = 'https://www.moscowmap.ru';
    const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36';

    private $client;
    private $link;
    private $generalDocument;
    private $countPage;
    private $listPages;


    public function __construct($link, Client $client)
    {
        $this->client = $client;
        $this->link = $link;
       // $this->listPage = $this->listPage();
        $this->generalDocument = $this->getPhpQueryDoc($this->link);
        $this->countPage;
    }

    /**
     * @return array
     */
    private function listPages()
    {
        $document = $this->generalDocument;
        $block = $document->find("div.raitingsort alterview");
        $block = pq($block);
        $hrefs = [];
        foreach ($block->find("a.name") as $org){
            $pq = pq($org);
            array_push($hrefs,self::SITE_URL.$pq->attr('href'));
        }
        return $hrefs;
    }

    private function countPage()
    {
        $document = $this->generalDocument;
        $counter = $document->find("span.counter");
        //todo
        return $counter;
    }

    public function pager()
    {
        for($i = 1; $i<= $this->countPage(); $i++){
            if($i = 1){
                $this->linker();
            }else{
                $link = $this->link."page$i/";
                $this->generalDocument = $this->getPhpQueryDoc($this->link);
                $this->linker();
            }
        }
    }

    /**
     *
     */
    private function linker()
    {
        $this->listPages = $this->listPages();
        foreach ($this->listPages as $listPage){
            $this->page($listPage);
        }
    }

    private function page($href)
    {
       $document = $this->getPhpQueryDoc($href);
        //todo

    }

    private function getPhpQueryDoc($url)
    {
        $res = $this->client->request('GET', $url);
        usleep(2000);
        $body = $res->getBody();
        return \phpQuery::newDocumentHTML($body);
    }

}