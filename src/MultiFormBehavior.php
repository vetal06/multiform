<?php
namespace vetal06\multiform;


use yii\base\Behavior;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class MultiFormBehavior extends Behavior
{

    public $attributeProperty = [];
    public $fkAttributesId = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'updateData',
            ActiveRecord::EVENT_AFTER_INSERT => 'insertData',
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            ActiveRecord::EVENT_BEFORE_DELETE => 'deleteAllData',

        ];
    }

    public function updateData()
    {
        $model = $this->owner;
        foreach ($this->attributeProperty as $attribute => $formModelClass) {
            if(!empty($model->$attribute)) {
                $this->deleteData($formModelClass, $model, $attribute);
                foreach ($model->$attribute as $key => $data) {
                    $formModel = \Yii::createObject($formModelClass);
                    $dataModel = $this->loadFormData($model, $formModel, $data, $attribute, $key);
                    $dataModel->save();
                }
            }
        }
    }

    public function insertData()
    {
        $model = $this->owner;
        foreach ($this->attributeProperty as $attribute => $formModelClass) {
            if(!empty($model->$attribute)) {
                foreach ($model->$attribute as $key => $data) {
                    $formModel = \Yii::createObject($formModelClass);
                    $dataModel = $this->loadFormData($model, $formModel, $data, $attribute, $key);
                    $dataModel->save();
                }
            }
        }
    }

    public function afterFind()
    {
        $model = $this->owner;
        foreach ($this->attributeProperty as $attribute => $formModelClass) {
            $formModel = \Yii::createObject($formModelClass);
            $formName =  (new \ReflectionClass($formModel))->getShortName();
            $fkModels = $formModel->findAll([$this->getFkAttribute($attribute) => $model->id]);
            $resArray = [];
            foreach ($fkModels as $m)
            {
                $resArray[$formName][] = $m->getAttributes();
            }
            $model->$attribute = $resArray;
        }
    }

    /**
     * Загрузка дынных
     * @param Model $model
     * @param $data
     * @param $attribute
     */
    protected function loadFormData(Model $model, $form, $data, $attribute, $key)
    {
        $formName =  (new \ReflectionClass($form))->getShortName();
        $modelName = (new \ReflectionClass($model))->getShortName();
        if (empty($data[$formName])) {
            return $form;
        }
        $modelData = $data[$formName];
        if (!empty($modelData['id'])) {
            $form = $form->findOne(['id' => $modelData['id']]);
        } else {
            $fkAttribute = $this->getFkAttribute($attribute);
            $form->$fkAttribute = $model->id;
        }
        $form->load($data);
        // load instance
        foreach ($modelData as $attr => $attrData) {
            $fileInputName = "{$modelName}[$attribute][$key][{$formName}][$attr]";
            if ($fileInstance = UploadedFile::getInstanceByName($fileInputName)) {
                $form->load([
                    $attr => $fileInstance
                ], '');
            }

        }
        return $form;

    }

    /**
     * Удаление данных
     * @param $formModelClass
     * @param $model
     * @param $attribute
     */
    protected function deleteData($formModelClass, $model, $attribute)
    {
        $formModel = \Yii::createObject($formModelClass);
        $dataIds = ArrayHelper::getColumn($model->$attribute, function($row) use ($formModel){
            $formName =  (new \ReflectionClass($formModel))->getShortName();
            return empty($row[$formName])?'':$row[$formName]['id'];
        });
        $fkAttribute = $this->getFkAttribute($attribute);
        if (empty($dataIds)) {
            $formModel->deleteAll([$fkAttribute => $model->id]);
        } else {
            $formModel->deleteAll(['and', "$fkAttribute = :id", ['not in', 'id', $dataIds]], [':id' => $model->id]);
        }

    }

    public function deleteAllData()
    {
        $model = $this->owner;
        foreach ($this->attributeProperty as $attribute => $formModelClass) {
            $formModel = \Yii::createObject($formModelClass);
            $fkAttribute = $this->getFkAttribute($attribute);
            $formModel->deleteAll("$fkAttribute = :id", [':id' => $model->id]);
        }
    }

    /**
     * Название аттрибута FK
     * @param $attribute
     * @return mixed
     */
    private function getFkAttribute($attribute)
    {
        return $this->fkAttributesId[$attribute];
    }
}