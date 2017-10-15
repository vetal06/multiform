<?php
namespace vetal06\multiform\assets;

/**
 * Class MultiFormAssets
 */
class MultiFormAssets extends \yii\web\AssetBundle
{
    public $js = [
        'js/multiform.js',
    ];
    public $css = [
    ];

    public function init()
    {
        $this->sourcePath = __DIR__;
        parent::init();
    }

    public $depends = [
        '\yii\web\JqueryAsset'
    ];
}