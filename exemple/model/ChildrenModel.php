<?php
namespace vetal06\multiform\exemple\model;

class ChildrenModel extends \yii\base\Model
{

    public $id;
    public $title;
    public $file_image;
    public $image;

    public function getClassNameShort()
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    public function rules()
    {
        return [
            [['id', 'title', 'image'], 'safe']
        ];
    }

}