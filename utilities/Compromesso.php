<?php

namespace app\utilities;


use GuzzleHttp\Client;

class Compromesso extends Site
{

    private $pages;
    private $pagesLinks;

    public function __construct($link, Client $client, $pages)
    {
        parent::__construct($link, $client);
        $this->pages = $pages;
        $this->pagesLinks = $this->getPages();
    }

    public function getPages()
    {
        $pagesLinks = [];
        for ($i = 1; $i <= $this->pages; $i++){
            array_push($pagesLinks, $this->link.'/?page='.$i);
        }
        return $pagesLinks;
    }

    public function parser()
    {
        foreach ($this->pagesLinks as $pagesLink){
            $currentPage = $this->getPhpQueryDoc($pagesLink);
            $organizations = $this->getOrganizations($currentPage);
            print_r($organizations);
        }
    }

    public function getOrganizations(\phpQueryObject $page)
    {
        $items = $page->find('div.hotel-name');
        return $items;
    }
}