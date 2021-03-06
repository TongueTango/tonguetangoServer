<?php

/**
 * This is the model base class for the table "person_phones".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "PersonPhones".
 *
 * Columns in table "person_phones" available as properties of the model,
 * followed by relations of table "person_phones" available as properties of the model.
 *
 * @property string $id
 * @property string $person_id
 * @property string $phone_type
 * @property string $phone_number
 * @property string $create_date
 * @property string $update_date
 * @property string $delete_date
 *
 * @property People[] $peoples
 * @property People $person
 * @property UserPersonPhones[] $userPersonPhones
 */
abstract class BasePersonPhones extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'person_phones';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'PersonPhones|PersonPhones', $n);
	}

	public static function representingColumn() {
		return 'phone_type';
	}

	public function rules() {
		return array(
			array('person_id, phone_type, phone_number', 'required'),
			array('person_id', 'length', 'max'=>10),
			array('phone_type', 'length', 'max'=>255),
			array('phone_number', 'length', 'max'=>50),
			array('delete_date', 'safe'),
			array('delete_date', 'default', 'setOnEmpty' => true, 'value' => null),
			array('id, person_id, phone_type, phone_number, create_date, update_date, delete_date', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'peoples' => array(self::HAS_MANY, 'People', 'phone_id'),
			'person' => array(self::BELONGS_TO, 'People', 'person_id'),
			'userPersonPhones' => array(self::HAS_MANY, 'UserPersonPhones', 'person_phone_id'),
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'id' => Yii::t('app', 'ID'),
			'person_id' => null,
			'phone_type' => Yii::t('app', 'Phone Type'),
			'phone_number' => Yii::t('app', 'Phone Number'),
			'create_date' => Yii::t('app', 'Create Date'),
			'update_date' => Yii::t('app', 'Update Date'),
			'delete_date' => Yii::t('app', 'Delete Date'),
			'peoples' => null,
			'person' => null,
			'userPersonPhones' => null,
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('person_id', $this->person_id);
		$criteria->compare('phone_type', $this->phone_type, true);
		$criteria->compare('phone_number', $this->phone_number, true);
		$criteria->compare('create_date', $this->create_date, true);
		$criteria->compare('update_date', $this->update_date, true);
		$criteria->compare('delete_date', $this->delete_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}