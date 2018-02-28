<?php

namespace app\utilities;


use app\models\Catalog;
use GuzzleHttp\Client;

class Compromesso extends Site
{

    private $pages;
    private $pagesLinks;
    private $startPage;

    public function __construct($link, Client $client,$startPage, $pages)
    {
        parent::__construct($link, $client);
        $this->pages = $pages;
        $this->startPage = $startPage;
        $this->pagesLinks = $this->getPages();

    }

    public function getPages()
    {
        $pagesLinks = [];
        for ($i = $this->startPage; $i <= $this->pages; $i++){
            array_push($pagesLinks, $this->link.'?page='.$i);
        }
        return $pagesLinks;
    }

    public function parser()
    {
        foreach ($this->pagesLinks as $pagesLink){
            $currentPage = $this->getPhpQueryDoc($pagesLink);
            $organizations = $this->getOrganizations($currentPage);
            foreach ($organizations as $orgLink){
                $this->saveOrganization($orgLink);
            }
        }
    }

    public function getOrganizations(\phpQueryObject $page)
    {
        $items = $page->find('div.hotel-name');
        $hrefs = [];
        foreach ($items as $item) {
            print_r($item->textContent);
            $ipq = pq($item);
            foreach($ipq->find('a') as $a){
                $apq = pq($a);
                array_push($hrefs, $apq->attr('href'));
            }

        }
        return $hrefs;
    }

    public function saveOrganization($link)
    {
        $orgPage = $this->getPhpQueryDoc($link);
        $h1s = $orgPage->find('h1');
        $catalog = new Catalog();
        foreach ($h1s as $h1){
            $catalog->name = $h1->textContent;
        }
        $i = 0;
        foreach ($orgPage->find('div.customer-like') as $item) {
            $itemPq = pq($item);
           if ($i == 0){
               foreach ($itemPq->find('li') as $li)
               {
                   $catalog->address = $li->textContent;
               }
           } elseIf ($i == 1){
               foreach ($itemPq->find('li') as $li)
               {
                   $catalog->contact = $li->textContent;
               }
           }
           $catalog->save();
        $i++;
        }
    }
}