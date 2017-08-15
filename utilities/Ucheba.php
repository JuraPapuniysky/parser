<?php


namespace app\utilities;


use app\models\Catalog;
use GuzzleHttp\Client;

class Ucheba extends Site
{
    const SITE_URL = 'www.ucheba.ru';

    private $currentPage;
    private $pageNum;


    public function __construct($link, Client $client, $countPage)
    {
        parent::__construct($link, $client);
        $this->currentPage = $link;
        $this->countPage = $countPage;
    }

    private function getItems()
    {
        $document = $this->generalDocument;
        $block = $document->find("a.search-results-img.js_webstat");
        $hrefs = [];
        foreach ($block as $org) {
            $pq = pq($org);
            array_push($hrefs, self::SITE_URL . $pq->attr('href'));
        }
        return $hrefs;
    }

    public function pager()
    {
        $page = 20;
        for($i = 0; $i < $this->countPage; $i++) {
            if($i != 0){
                $currentPage = $this->currentPage.'?s='.$page;
                $this->generalDocument = $this->getPhpQueryDoc($currentPage);
            }
            foreach ($this->getItems() as $item) {
                $document = $this->getPhpQueryDoc($item);
                $this->getData($document, $item);
            }
            $page = $page + 20;
        }
    }

    private function getData($document, $link)
    {
        $catalog = new Catalog();
        $catalog->name = $this->getName($document);

       // $catalog->site = $this->getSite($document);
        //$catalog->email = $this->getEmail($document);
        //$catalog->address = $this->getAddress($document);
        //$catalog->contact = $this->getContact($document);
        //$catalog->activity = $this->getActivity($document);
        //$catalog->comment = $this->getComment($document);
        //$catalog->link = $link;

    }

    private function getName($document)
    {
        $content = $document->find("div.head-annonce__col-left");
        print_r($content);
        return $content->attr('alt');
    }

    private function getContact($document)
    {
        $address = $document->find('address.address-panel row');
        $ul = $address->find('ul.col-sm-6.list-unstyled');
        $lis = $ul[0]->find('li');
        return $lis[2]->textContent;
    }

    private function getEmail($document)
    {

        $li = $document->find('li.large.bold');
        $a = $li->find('a');

        return $a[0]->textContent;
    }

    private function getActivity($document)
    {

    }

    private function getAddress($document)
    {
        $address = $document->find('address.address-panel row');
        $ul = $address->find('ul.col-sm-6.list-unstyled');
        $lis = $ul[1]->find('li');
        return $lis[0]->textContent;
    }

    private function getComment($document)
    {

    }

    private function getSite($document)
    {
        $li = $document->find('li.large.bold.js_webstat_stat_sended');
        $a = $li->find('a');
        return $a->attr('href');
    }
}