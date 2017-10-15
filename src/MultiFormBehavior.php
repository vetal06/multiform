<?php
namespace vetal06\multiform;


use common\db\ActiveRecord;
use common\helpers\ArrayHelper;
use yii\base\Behavior;
use yii\base\Model;
use yii\web\UploadedFile;

class MultiFormBehavior extends Behavior
{

    public $attributeProperty = [];
    public $fkAttributesId = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];
    }

    public function afterUpdate()
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

    public function afterFind()
    {
        $model = $this->owner;
        foreach ($this->attributeProperty as $attribute => $formModelClass) {
            $formModel = \Yii::createObject($formModelClass);
            $fkModels = $formModel->findAll([$this->getFkAttribute($attribute) => $model->id]);
            $resArray = [];
            foreach ($fkModels as $m)
            {
                $resArray[$formModel->getClassNameShort()][] = $m->getAttributes();
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
        $modelData = $data[$form->getClassNameShort()];
        if (!empty($modelData['id'])) {
            $form = $form->findOne(['id' => $modelData['id']]);
        } else {
            $fkAttribute = $this->getFkAttribute($attribute);
            $form->$fkAttribute = $model->id;
        }
        $form->load($data);
        // load instance
        foreach ($modelData as $attr => $attrData) {
            $fileInputName = "{$model->getClassNameShort()}[$attribute][$key][{$form->getClassNameShort()}][$attr]";
            if ($fileInstance = UploadedFile::getInstanceByName($fileInputName)) {
                $form->load([
                    $attr => $fileInstance
                ], '');
            }

        }
//        var_dump($form->file_image);
//        exit;
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
            return $row[$formModel->getClassNameShort()]['id'];
        });
        $fkAttribute = $this->getFkAttribute($attribute);
        if (empty($dataIds)) {
            $formModel->deleteAll([$fkAttribute => $model->id]);
        } else {
            $formModel->deleteAll(['and', $fkAttribute => $model->id, ['not in', 'id', $dataIds]]);
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