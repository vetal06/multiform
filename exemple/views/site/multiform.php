<?php

/* @var $this \yii\web\View */
/* @var $content string */

?>
<?php $form =\yii\widgets\ActiveForm::begin()?>
<?=$form->field($model, 'title')?>
<?=$form->field($model, 'name')?>

<?php
$tagModel = new \vetal06\multiform\exemple\model\ChildrenModel();
?>
<?=$form->field($model, 'contents')->widget(\vetal06\multiform\MultiFormWidget::className(), [
    'template' => '<div class="row"> <div class="col-sm-5">{title}</div><div class="col-sm-5">{file_image}</div><div class="col-sm-2">{id}{deleteButton}</div></div>',
    'columns' => [
        'id' => [
            'model' => $tagModel,
            'attribute' => 'id',
            'type' => \vetal06\multiform\MultiActiveField::TYPE_HIDDEN,
        ],
        'title' => [
            'model' => $tagModel,
            'attribute' => 'title',
            'type' => \vetal06\multiform\MultiActiveField::TYPE_TEXT,
            'options' => [],
        ],
        'file_image' => [
            'model' => $tagModel,
            'attribute' => 'file_image',
            'type' => \vetal06\multiform\MultiActiveField::TYPE_WIDGET,
            'options' => [
                'class' => \kartik\file\FileInput::className(),
            ],
            'callOptions' => function ($field) {
                if (!empty($field->model->image)) {
                    return [
                        'pluginOptions' => [
                            'initialPreview' => $field->model->image,
                            'initialPreviewAsData' => true,
                            'initialPreviewFileType'=>'image',
                            'purifyHtml' => true,
                            'overwriteInitial' => false,
                            'showPreview' => true,
                            'showRemove' => false,
                            'showUpload' => false
                        ],
                    ];
                }
                return [];
            },
        ],
    ]
])?>

<?php $form->end()?>
