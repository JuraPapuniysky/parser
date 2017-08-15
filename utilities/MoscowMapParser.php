<?php


namespace app\utilities;

use app\models\Catalog;
use GuzzleHttp\Client;
use PHPUnit\Framework\Exception;
use yii\base\ErrorException;

class MoscowMapParser extends Site
{
    const SITE_URL = 'https://www.moscowmap.ru';
    const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36';

    private $count;
    private $pageNum;
    public function __construct($link, Client $client, $pageNum, $count)
    {
        parent::__construct($link, $client);
        $this->count = $count;
        $this->pageNum = $pageNum;
        $this->countPage = $this->countPage();
    }

    /**
     * @return array
     */
    private function listPages()
    {
        $document = $this->generalDocument;
        $block = $document->find("div.raitingsort.alterview");
        $hrefs = [];
        foreach ($block->find("a.name") as $org) {
            $pq = pq($org);
            array_push($hrefs, self::SITE_URL . $pq->attr('href'));
        }
        return $hrefs;
    }

    /**
     * @return mixed
     */
    private function countPage()
    {
        $document = $this->generalDocument;
        $counter = $document->find("span.counter");
        $element = $counter->elements[0]->textContent;
        $count = preg_replace("/[^0-9]/", '', substr($element, strlen($element) / 2));
        return $count;
    }

    /**
     *
     */
    public function pager()
    {
        for ($i = $this->pageNum; $i <= $this->countPage; $i++) {
            if ($i == 1) {
                $this->linker();
            } else {

                $link = $this->link . "page$i/";
                $this->pageNum = $i;
                $this->generalDocument = $this->getPhpQueryDoc($link);
                $this->linker();
            }
        }
    }

    /**
     *
     */
    private function linker()
    {
        foreach ($this->listPages() as $listPage) {
            $this->page($listPage);

        }
    }

    private function page($href)
    {
        $document = $this->getPhpQueryDoc($href);
        $info = $document->find("div.fs14.text-wrapper.alterview.main-info");
        $catalog = new Catalog();
        $catalog->name = $this->getName($document);
        $catalog->contact = $this->getContact($info);
        $catalog->email = $this->getEmail($info);
        $catalog->activity = $this->getActivity($info);
        $catalog->address = $this->getAddress($info);
        $catalog->comment = $this->getComment($info);
        $catalog->site = $this->getSite($info);
        $catalog->link = $href;
        $catalog->save();
        echo "$this->count. Page # $this->pageNum. New item name $catalog->name \n with site $catalog->site \n";
        unset($catalog);
        $this->count++;
        usleep(500000);
    }


    /**
     * @param $document
     * @return mixed
     */
    private function getName($document)
    {
        $content = $document->find("h1.subtitle-index.htag.th3.black");
        $pieces = explode(':', $content->elements[0]->textContent);
        return $pieces[0];
    }

    /**
     * @param $info
     * @return mixed|string
     */
    private function getContact($info)
    {
        $content = $info->find("p.as-span.m-left");
        $phone = '';
        $secondPhone = '';
        $tmp = '';
        foreach ($content as $item) {
            $item = pq($item);
            $text = $item->elements[0]->textContent;
            if (strlen($text) > 0) {
                if ($text[0] == '+') {
                    $phone = $text;
                }
            }
        }
        $secondContent = $info->find("p.as-span.box");
        if (count($secondContent->elements) > 0) {
            $secondPhone = $secondContent->elements[0]->textContent;
        }
        if (strlen($secondPhone) > 0) {
            if ($secondPhone[0] != '+') {
                $secondPhone = '';
            }
        } else {
            $secondPhone = '';
        }
        return $phone . ' ' . $secondPhone;

    }

    /**
     * @param $info
     * @return mixed
     */
    private function getEmail($info)
    {
        $content = $info->find("a.black.nou");
        $email = '';
        foreach ($content->elements as $element) {
            if (stristr($element->textContent, '@')) {
                $email = $element->textContent;
            }
        }
        return $email;
    }

    /**
     * @param $info
     * @return mixed
     */
    private function getActivity($info)
    {
        $content = $info->find("p#about1");
        try{
            $content = $content->elements[0]->textContent;
        }catch (ErrorException $e){
            echo "No activity \n";
            $content = '';
        }
        return $content;
    }

    /**
     * @param $info
     * @return mixed
     */
    private function getAddress($info)
    {
        $content = $info->find("p.as-span.m-left");
        if (count($content->elements) > 0) {
            return $content->elements[0]->textContent;
        } else {
            return '';
        }

    }

    /**
     * @param $info
     * @return null
     */
    private function getComment($info)
    {
        return null;
    }

    /**
     * @param $info
     * @return mixed
     */
    private function getSite($info)
    {
        $site = '';
        $content = $info->find("a.as.black.nou");
        foreach ($content->elements as $element) {
            if (strlen($element->textContent) > 0) {
                if ($element->textContent[0] == 'w') {
                    $site = $element->textContent;
                }
            }
        }
        return $site;
    }

}