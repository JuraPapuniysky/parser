<?php

namespace app\commands;


use app\models\Catalog;
use yii\console\Controller;

class ToolsController extends Controller
{
    public function actionFormatNames($search)
    {
        $catalogs = Catalog::find()->all();
        $i = 1;
        foreach ($catalogs as $catalog){
            $oldName = $catalog->name;
            $catalog->name = str_replace($search, '', $catalog->name);
            $catalog->save();
            echo $i. '. Catatalog name was changed '. $oldName .' to '. $catalog->name. "\n";
            $i++;
        }
    }

    public function actionDeleteWithOutNumber()
    {
        $catalogs = Catalog::findAll(['contact' => null]);
        echo 'Found with out numbers' . count($catalogs)."\n";
        foreach ($catalogs as $catalog){

            echo $catalog->name.' '.$catalog->id." deleted\n";

            $catalog->delete();
        }
        echo "Done!\n";
    }

    public function actionShowCount()
    {
        echo count(Catalog::find()->all())."\n";
    }

    public function actionClear()
    {
        foreach (Catalog::find()->all() as $catalog){
            $catalog->delete();
        }
        echo "Done\n";
    }
}