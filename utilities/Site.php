<?php


namespace app\utilities;


use GuzzleHttp\Client;

abstract class Site
{
    protected $client;
    protected $link;
    protected $generalDocument;
    protected $countPage;

    public function __construct($link, Client $client)
    {
        $this->client = $client;
        $this->link = $link;
        $this->generalDocument = $this->getPhpQueryDoc($this->link);
    }

    /**
     * @param $url
     * @return \phpQueryObject|\QueryTemplatesParse|\QueryTemplatesSource|\QueryTemplatesSourceQuery
     */
    protected function getPhpQueryDoc($url)
    {
        $res = $this->client->request('GET', $url);
        usleep(1000);
        $body = $res->getBody();
        return \phpQuery::newDocumentHTML($body);
    }
}