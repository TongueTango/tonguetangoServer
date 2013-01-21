<?php

Yii::import('application.models._base.BaseAddressBook');

class AddressBook extends BaseAddressBook
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}