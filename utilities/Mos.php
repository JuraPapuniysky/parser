<?php
/**
 * Created by PhpStorm.
 * User: wsst17
 * Date: 26.09.17
 * Time: 8:46
 */

namespace app\utilities;


use app\models\Catalog;
use GuzzleHttp\Client;

class Mos extends Site
{

    public function __construct($link, Client $client)
    {
        parent::__construct($link, $client);
    }

    public function getItems()
    {
        $document = $this->generalDocument;
        $items = $document->find('div.item');
        foreach ($items as $item)
        {
            $pq = pq($item);
            foreach ($pq->find('a.name') as $item){

                $catalog = new Catalog();
                $catalog->name = $item->nodeValue;
                //$catalog->save();
            }
            foreach ($pq->find('div') as $item){

                if (substr($item->nodeValue, 0, 2) == 'А'){
                    $catalog->address = $item->nodeValue;
                }
                if (substr($item->nodeValue, 0, 1) == 'E'){
                    $catalog->email = $item->nodeValue;
                }
                if(substr($item->nodeValue, 0, 2) == 'Т'){
                    $catalog->contact = $item->nodeValue;
                }
                if(substr($item->nodeValue, 0, 2) == 'С'){
                    $catalog->site = $item->nodeValue;
                }
                $catalog->save();
                echo "New item was add with name $catalog->name \n";
            }
        }
    }
}