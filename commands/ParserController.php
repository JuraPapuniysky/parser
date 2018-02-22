<?php


namespace app\commands;


use app\utilities\DoubleSeven;
use app\utilities\InfoSpravki;
use app\utilities\Metaprom;
use app\utilities\Mos;
use app\utilities\MosgorzdravParcer;
use app\utilities\Orgpoisk;
use app\utilities\Ucheba;
use app\utilities\Compromesso;
use Codeception\Module\Cli;
use Yii;
use GuzzleHttp\Client;
use yii\console\Controller;
use app\utilities\MoscowMapParser;

class ParserController extends Controller
{

    public function actionIndex()
    {
        echo 'It works!';
    }

    public function actionMoscowMap($link = '', $pageNum = 1, $count = 1)
    {
        $client = new Client();
        $parser = Yii::createObject(MoscowMapParser::class, [$link, $client, $pageNum, $count]);
        $parser->pager();
    }

    public function actionMetaprom($link = 'http://www.metaprom.ru/companies/construction-equipment/')
    {
        $client = new Client();
        $parser = Yii::createObject(Metaprom::class, [$link, $client]);
        $parser->saveCompany('http://www.metaprom.ru/companies/id579042-oborudovanie-tehnologii-ooo');
    }

    public function actionUcheba($link = 'https://www.ucheba.ru/for-abiturients/college', $countPage = 9)
    {
        $client = new Client();
        $parser = Yii::createObject(Ucheba::class, [$link, $client, $countPage]);
        $parser->pager();
    }

    public function actionMosgorzdrav($link)
    {
        $client = new Client();
        $parser = Yii::createObject(MosgorzdravParcer::class, [$link, $client]);
        $parser->parser();
    }

    public function actionMos($link)
    {
        $client = new Client();
        $parser = Yii::createObject(Mos::class, [$link, $client]);
        $parser->getItems();
    }

    public function actionDoubleSeven($link)
    {
        $client = new Client();
        $parser = Yii::createObject(DoubleSeven::class, [$link, $client]);
        $parser->parser();

    }

    /**
     * orgpoisk.ru
     */
    public function actionOrgpoisk($link, $countList)
    {
        $client = new Client();
        $orgpoisk = Yii::createObject(Orgpoisk::class, [$link, $countList, $client]);
        $orgpoisk->parser();
    }

    /**
     * http://moscva.info-spravki.ru
     */
    public function actionInfoSpravky($link)
    {
        $client = new Client();
        $orgpoisk = Yii::createObject(InfoSpravki::class, [$link, $client]);
        $orgpoisk->parser();
    }

    /**
     * http://moskva.compromesso.ru/catalog/obuvnye-magaziny/
     */
    public function actionCompromesso($link = 'http://moskva.compromesso.ru/catalog/obuvnye-magaziny/', $start, $pages = 1)
    {
        $client = new Client();
        $parser = new Compromesso($link, $client,$start, $pages);
        $parser->parser();

    }
}