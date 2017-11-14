<?php


namespace app\utilities;

use app\models\Catalog;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;


class Orgpoisk extends Site
{

    const SITE_URL = 'orgpoisk.ru';

    private $countList;

    public function __construct($link, $countList, Client $client)
    {
        parent::__construct($link, $client);
        $this->countList = $countList;
    }

    public function parser()
    {
        $pageNum = 1;
        $isPage = $pageNum <= $this->countList;
        while ($isPage) {
            try {
                $this->generalDocument = $this->getPhpQueryDoc(substr($this->link, 0, -1) . $pageNum);
            } catch (RequestException $e) {
                $isPage = false;
            }
            foreach ($this->getPageCompanyList($this->generalDocument) as $link) {
                $this->saveCompanyInfo($link);
            }
            $pageNum++;
        }
    }

    protected function getPageCompanyList($document)
    {
        $block = $document->find("div.brd-block");
        $hrefs = [];
        foreach ($block->find("a") as $org) {
            $pq = pq($org);
            if (substr($pq->attr('href'),0, 5) == '/info'){
                array_push($hrefs, self::SITE_URL . $pq->attr('href'));
            }
        }
        return $hrefs;
    }

    protected function saveCompanyInfo($link)
    {
        $catalog = new Catalog();
        $document = $this->getPhpQueryDoc($link);
        $catalog->name = $document->find("h1")->text();
        $catalog->address = $this->getAddress($document);
        echo $catalog->address."\n";
    }

    /**
     * @param \phpQueryObject $document
     * @return String
     */
    protected function getAddress(\phpQueryObject $document)
    {
        $divs = $document->find("div");
        $name = '';
        foreach ($divs as $div){
            $pq = pq($div);
            if($pq->attr("itemtype") == 'http://schema.org/PostalAddress'){
                $name = substr($pq->text(), 12);
                break;
            }
        }
        return $name;
    }
}