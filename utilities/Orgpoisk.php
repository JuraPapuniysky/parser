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
        $catalog->link = $link;
        $catalog->email = '';
        $catalog->site = $this->getSite($document);
        $catalog->contact = $this->getContact($document);
        echo $catalog->name."\n";
        echo $catalog->address."\n";
        echo $catalog->link."\n";
        echo $catalog->site."\n";
        echo $catalog->contact."\n";
        echo "////////////////\n";
        $catalog->save();
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

    /**
     * @param \phpQueryObject $document
     * @return array|null|\phpQueryObject|string
     */
    protected function getSite(\phpQueryObject $document)
    {
        $as = $document->find('a');
        $site = '';
        foreach ($as as $a){
            $pq = pq($a);
            if($pq->attr('target') === '_BLANK'){
                $site = $pq->attr('href');
                break;
            }
        }
        return $site;
    }

    /**
     * @param \phpQueryObject $document
     * @return String
     */
    protected function getContact(\phpQueryObject $document)
    {
        $contact = '';
        foreach ($document->find('p') as $p){
            $pq = pq($p);
            if (strpos($pq->text(), '(495)') !== false or strpos($pq->text(), 'Тел.') !== false or strpos($pq->text(), 'Телефон') !== false){
                $contact = $pq->text();
                break;
            }
        }
        return $contact;
    }
}