<?php


namespace app\utilities;


use GuzzleHttp\Client;

class MosgorzdravParcer
{
    public $link;
    private $client;


    public function __construct($link, $client)
    {
        $this->client = $client;
        $this->link = $link;


    }

    /**
     * @param $url
     * @return \phpQueryObject|\QueryTemplatesParse|\QueryTemplatesSource|\QueryTemplatesSourceQuery
     */
    protected function getPhpQueryDoc($url)
    {
        echo "URL is $url \n";
        $res = $this->client->request('GET', $url);
        $body = $res->getBody();
        return \phpQuery::newDocumentHTML($body);
    }

    /**
     * @return \phpQueryObject|\QueryTemplatesParse|\QueryTemplatesSource|\QueryTemplatesSourceQuery
     */
    public function getItems()
    {
        $document = $this->getPhpQueryDoc($this->link);
        $q = $document->find('div.medical-main');
        var_dump($q);
        return $q;
    }

    public function parser()
    {
        foreach ($this->getItems() as $item){
            $pq = pq($item);
            $content = $pq->find('div.col.col-6');
            $a = $content->find('a');
        }
    }
}