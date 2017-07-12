<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "catalog".
 *
 * @property integer $id
 * @property string $name
 * @property string $contact
 * @property string $email
 * @property string $activity
 * @property string $address
 * @property string $comment
 * @property string $link
 */
class Catalog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'contact', 'email', 'activity', 'address', 'comment', 'link'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'contact' => 'Contact',
            'email' => 'Email',
            'activity' => 'Activity',
            'address' => 'Address',
            'comment' => 'Comment',
            'link' => 'Link'
        ];
    }
}
