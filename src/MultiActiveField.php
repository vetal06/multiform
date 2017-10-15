<?php
namespace vetal06\multiform;


class MultiActiveField extends \yii\widgets\ActiveField
{

    const TYPE_TEXT = 'textInput';
    const TYPE_WIDGET = 'widget';
    const TYPE_HIDDEN = 'hiddenInput';

   public $parentField;
   public $type;
   public $rowKey;
   public $callOptions;

   public function render($context=null)
   {

        if($this->type === self::TYPE_WIDGET) {
            $options = empty($options['options'])?[]:$options['options'];
            $options = array_merge($options, [
                'name' => $this->getInputName(),
                'id' => $this->getInputId(),
            ]);
            $this->widget($this->options['class'], array_merge($this->options, [
                'options' => $options,
            ], $this->getCallOptions()));
        }else {
            call_user_func([$this, $this->type], array_merge($this->options, [
                'name' => $this->getInputName(),
                'id' => $this->getInputId(),
            ], $this->getCallOptions()));
        }
        return parent::render($context);

   }

   private function getCallOptions()
   {
       if (is_callable($this->callOptions)) {
           return call_user_func($this->callOptions, $this);
       }
       return [];
   }

   protected function getInputName()
   {
       $parentModelName = (new \ReflectionClass($this->parentField->model))->getShortName();
       $modelName = (new \ReflectionClass($this->model))->getShortName();
       return "{$parentModelName}[{$this->parentField->attribute}][$this->rowKey][{$modelName}][$this->attribute]";
   }


   protected function getInputId()
   {
       $parentModelName = (new \ReflectionClass($this->parentField->model))->getShortName();
       $modelName = (new \ReflectionClass($this->model))->getShortName();
       return "{$parentModelName}-{$this->parentField->attribute}-$this->rowKey-{$modelName}-$this->attribute";
   }

   public function hiddenInput($options=[])
   {
      return parent::hiddenInput($options)->label(false);
   }

}