<?php

namespace app\modules\photogallery\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $slug
 * @property string|null $status
 * @property int|null $count
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status','title','slug'],'required'],
            [['count'], 'integer'],
            [['title', 'slug', 'status'], 'string', 'max' => 50],
            ['slug','match','pattern' => '/^[a-zA-Z0-9\-_]+$/'],
            ['title','unique','targetClass' => 'app\modules\photogallery\models\Category'],
            ['slug','unique','targetClass' => 'app\modules\photogallery\models\Category'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'status' => 'Status',
            'count' => 'Count',
        ];
    }
}
