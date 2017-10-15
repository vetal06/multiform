<?php
namespace vetal06\multiform\exemple\model;



class MainModel extends \yii\base\Model
{

    public $title;
    public $name;
    public $contents = [
        [
            'ChildrenModel' => [
                'id' => 1,
                'title' => 'test1',
                'image' => 'https://ovg.cc//sites/ovg.cc/files/styles/cover_grid/public/image/m/9238.jpg?',
            ],
        ],
        [
            'ChildrenModel' => [
                'id' => 2,
                'title' => 'test2',
                'image' => 'https://ovg.cc/sites/ovg.cc/files/styles/cover_grid/public/image/m/9082.jpg',
            ],
        ]
    ];

    public function getClassNameShort()
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}