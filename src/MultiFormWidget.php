<?php
namespace vetal06\multiform;
;

use vetal06\multiform\assets\MultiFormAssets;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/**
 * Множественное схоранение форм
 */
class MultiFormWidget extends \yii\widgets\InputWidget
{
    public $template = '';
    public $columns;

    public $childrenFieldClass = 'vetal06\multiform\MultiActiveField';

    public $deleteButtonTemplate = '<label class="control-label">Delete</label><a class="form-control js-delete-row btn btn-danger">Delete</a>';
    public $addButtonTemplate = '<a class="js-add-button btn btn-success">Add</a>';


    private $key;

    public function run()
    {


        $rowContext = $this->template;
        $rowKey = $this->getKey();
        $jsOld = $this->view->js;
        $this->view->js = [];
        foreach ($this->columns as $attribute => $column) {
            $field = $this->createChildrenField($column, $rowKey);
            $rowContext = str_replace("{{$attribute}}", $field->render(), $rowContext);
        }
        $rowContext = $this->renderRow($rowContext, $rowKey);
        $content = $rowContext;
        $contentExist = $this->renderExistData();
        if (!empty($contentExist)) {
            $content = $contentExist;
        }
        $jsRow = isset($this->view->js[View::POS_READY])?$this->view->js[View::POS_READY]:'';
        foreach ($this->view->js as $position => $js) {
            $this->view->js[$position] = array_merge($js, empty($jsOld[$position])?[]:$jsOld[$position]);
        }
        $jsRowString = Json::encode(implode(' ', $jsRow));
        $rowContextEncode = Json::encode($rowContext);

        $view = $this->getView();
        MultiFormAssets::register($view);
        $view->registerJs("
            MultiForm.widgetId = '#{$this->getId()}';
            MultiForm.jsRowTemplate = {$jsRowString};
            MultiForm.rowTemplate = {$rowContextEncode};
            MultiForm.init();
        ");
        return Html::tag('div', $this->addButtonTemplate.$content, ['id' => $this->id]);
    }

    public function renderExistData()
    {
        $fullContent = '';
        $attribute = $this->attribute;
        $model = $this->model;
        if (!empty($model->$attribute)) {
            foreach ($model->$attribute as $modelData)
            {
                $rowKey = $this->getKey();
                $rowContext = $this->template;
                foreach ($this->columns as $attribute => $column) {
                    $field = $this->createChildrenField($column, $rowKey);
                    $field->model->load($modelData);
                    $rowContext = str_replace("{{$attribute}}", $field->render(), $rowContext);
                }
                $fullContent .= $this->renderRow($rowContext, $rowKey);
            }
        }
        return $fullContent;

    }

    private function createChildrenField($column, $rowKey)
    {
        return \Yii::createObject(array_merge([
            'class' => $this->childrenFieldClass,
            'parentField' => $this->field,
            'form' => $this->field->form,
            'rowKey' => $rowKey,
        ],$column));
    }

    protected function renderRow($data, $rowKey)
    {
        $data = str_replace("{deleteButton}", $this->deleteButtonTemplate, $data);
        return Html::tag('div', $data, ['class' => 'js-multiform-row', 'data-id-key' => $rowKey ]);
    }

    public function getKey()
    {
        if (empty($this->key)) {
            $this->key = 0;
        }
        return ++$this->key;
    }

}