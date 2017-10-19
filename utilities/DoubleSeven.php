<?php


namespace app\utilities;


use app\models\Catalog;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class DoubleSeven extends Site
{

    const SITE_URL = 'http://xn--77--8cdj1cnq1a.xn--p1ai';

    public function __construct($link, Client $client)
    {
        parent::__construct($link, $client);
    }

    public function parser()
    {
        $isPage = true;
        $pageNum = 1;
        while ($isPage) {
            try {
                $this->generalDocument = $this->getPhpQueryDoc($this->link . "?page=$pageNum");
            } catch (RequestException $e) {
                $isPage = false;
            }
            foreach ($this->getPageCompanyList($this->generalDocument) as $link) {
                $this->saveCompanyInfo($link);
            }
            $pageNum++;
        }
    }

    private function getPageCompanyList($document)
    {
        $block = $document->find("div.company");
        $hrefs = [];
        foreach ($block->find("a") as $org) {
            $pq = pq($org);
            array_push($hrefs, self::SITE_URL . $pq->attr('href'));
        }
        return $hrefs;
    }

    private function saveCompanyInfo($link)
    {
        $companyDocument = $this->getPhpQueryDoc($link);
        $catalog = new Catalog();
        $catalog->name = $companyDocument->find("h1.name")->text();
        $catalog->site = $companyDocument->find("span.website")->text();
        $city = '';
        $address = '';
        $email = '';
        $phone = '';
        foreach ($companyDocument->find("span") as $span) {
            $pq = pq($span);
            if ($pq->attr('itemprop') == 'addressLocality') {
                $city = $pq->text();
            } else if ($pq->attr('itemprop') == 'streetAddress'){
                $address = $pq->text();
            } else if ($pq->attr('itemprop') == 'telephone'){
                $phone = $pq->text();
            }
        }

        foreach ($companyDocument->find("a") as $a){
            $apq = pq($a);
            if ($apq->attr('itemprop') == 'email'){
                $email = $apq->text();
            }
        }

        $catalog->contact = $phone;
        $catalog->email = $email;
        $catalog->address = "$city, $address";
        $catalog->link = $link;
        if ($catalog->save()){
            echo "New company $catalog->name was saved id=$catalog->id \n";
        }
    }
}

