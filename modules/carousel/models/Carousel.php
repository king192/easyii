<?php
namespace yii\easyii\modules\carousel\models;

use Yii;
use yii\easyii\behaviors\CacheFlush;
use yii\easyii\behaviors\SortableModel;
use yii\easyii\helpers\Upload;

class Carousel extends \yii\easyii\components\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    const CACHE_KEY = 'easyii_carousel';

    public static function tableName()
    {
        return 'easyii_carousel';
    }

    public function rules()
    {
        return [
            ['image', 'image'],
            [['title', 'text', 'link'], 'trim'],
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON],
        ];
    }

    public function attributeLabels()
    {
        return [
            'image' => Yii::t('easyii', 'Image'),
            'link' =>  Yii::t('easyii', 'Link'),
            'title' => Yii::t('easyii', 'Title'),
            'text' => Yii::t('easyii', 'Text'),
        ];
    }

    public function behaviors()
    {
        return [
            CacheFlush::className(),
            SortableModel::className()
        ];
    }

    public function getImage()
    {
        return Upload::getLink($this->image_file);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(!$insert && $this->image_file != $this->oldAttributes['image_file'] && $this->oldAttributes['image_file']){
                Upload::delete($this->oldAttributes['image_file']);
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        Upload::delete($this->image_file);
    }
}