<?php
/**
 * Created by PhpStorm.
 * User: wsst17
 * Date: 16.11.17
 * Time: 9:36
 */

namespace app\utilities;


use app\models\Catalog;
use GuzzleHttp\Client;

class InfoSpravki extends Site
{
    public function parser()
    {

        foreach ($this->generalDocument->find('div') as $divSimple){
            $catalog = new Catalog();
            $div = pq($divSimple);
            $catalog->name = $div->find('strong')->text();
            if(strpos($div->text(), 'Адрес:') !== false){
                $catalog->address = substr($div->text(), -7, 0);
            }
            if(strpos($div->text(), 'Телефон:') !== false){
                $catalog->address = substr($div->text(), -9, 0);
            }
            $catalog->link = $this->link;
            $catalog->save();
        }
    }
}