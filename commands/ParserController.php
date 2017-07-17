<?php


namespace app\commands;

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

    public function actionMoscowMap($link = '')
    {
        $client = new Client();
        $parser = Yii::createObject(MoscowMapParser::class, [$link, $client]);
        $parser->pager();
    }
}