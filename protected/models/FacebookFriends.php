<?php

Yii::import('application.models._base.BaseFacebookFriends');

class FacebookFriends extends BaseFacebookFriends
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
    
    public function beforeSave()
    {
        if(parent::beforeSave())
        {
            if($this->isNewRecord)
            {
                if(is_null($this->create_date) || $this->create_date == '') {
                    $this->create_date  = date("Y-m-d H:i:s");
                    $this->update_date = date("Y-m-d H:i:s");
                }
                
            }
            else {
                if(is_null($this->update_date) || $this->update_date == '') {
                    $this->update_date = date("Y-m-d H:i:s");
                }
                
            }
                
                
            return true;
        }
        else
            return false;
    }
}