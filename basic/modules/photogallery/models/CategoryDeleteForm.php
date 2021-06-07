<?php

namespace app\modules\photogallery\models;

use Yii;
use yii\base\Model;

class CategoryDeleteForm extends Model
{
    public $action;
    public $category;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['action'],'required'],
            [['action','category'], 'string', 'max' => 50],
            [['category'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'action' => 'Action',
            'category' => 'Category',
        ];
    }
}
