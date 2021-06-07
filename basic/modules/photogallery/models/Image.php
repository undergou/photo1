<?php

namespace app\modules\photogallery\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property string|null $author
 * @property string|null $category
 * @property string|null $title
 * @property string|null $date
 * @property string|null $status
 * @property string|null $extension
 * @property string|null $image
 * @property string|null $watermark
 */
class Image extends \yii\db\ActiveRecord
{
    public $watermark;
    public $load_image;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title','status','category','watermark','author','load_image'], 'required'],
            [['author','category','title','date','status','extension','image','watermark'],'string', 'max' => 50],
            [['load_image'], 'file', 'extensions' => 'png, jpg, gif, jpeg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author' => 'Author',
            'category' => 'Category',
            'title' => 'Title',
            'date' => 'Date',
            'status' => 'Status',
            'extension' => 'Extension',
            'image' => 'Image',
            'watermark' => 'Watermark position',
            'load_image' => 'Upload image',
        ];
    }

    public function upload()
    {
        if (!$this->validate()) {
            return false;
        } else {
            return true;
        }
    }

}
