<?php


namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class LinkForm
 * @package app\models
 *
 * @property $link string
 */
class LinkForm extends Model
{
    public $link;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['link'], 'required'],
            [['link'], 'string'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'link' => 'Link',
        ];
    }
}