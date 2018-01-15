<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bad_links".
 *
 * @property integer $id
 * @property string $link
 * @property integer $created_at
 * @property integer $updated_at
 */
class BadLinks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bad_links';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link' => 'Link',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
