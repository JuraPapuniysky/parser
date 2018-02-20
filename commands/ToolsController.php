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
}