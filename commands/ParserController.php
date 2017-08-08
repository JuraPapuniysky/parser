<?php


namespace app\commands;


use app\utilities\Metaprom;
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

    public function actionUcheba($link = 'https://www.ucheba.ru/for-abiturients/college')
    {
        $client = new Client();
    }
}