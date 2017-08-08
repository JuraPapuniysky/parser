<?php


namespace app\utilities;


use GuzzleHttp\Client;

class Ucheba extends Site
{
    const SITE_URL = 'www.ucheba.ru';

    private $currentPage;
    private $pageNum;

    private $client;

    public function __construct($link, Client $client)
    {
        parent::__construct($link, $client);
        $this->client = $client;
        $this->currentPage = $link;
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
}