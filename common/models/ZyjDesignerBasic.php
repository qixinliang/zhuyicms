<?php

namespace common\models;
use yii\db\ActiveRecord;
class ZyjDesignerBasic extends ActiveRecord{
    public static function tableName(){
        
        return '{{%zyj_designer_basic}}';
    }
    
    public function getAllDesigner(){
        
        return $this->find()->all();
    }
}
