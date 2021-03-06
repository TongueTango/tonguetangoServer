<?php

/**
 * This is the model base class for the table "devices".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "Devices".
 *
 * Columns in table "devices" available as properties of the model,
 * followed by relations of table "devices" available as properties of the model.
 *
 * @property string $id
 * @property string $user_id
 * @property string $device_type
 * @property string $push_token
 * @property string $model
 * @property string $version
 * @property string $unique_id
 * @property string $auth_token
 * @property string $create_date
 * @property string $update_date
 * @property string $delete_date
 *
 * @property Users $user
 */
abstract class BaseDevices extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'devices';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'Devices|Devices', $n);
	}

	public static function representingColumn() {
		return 'device_type';
	}

	public function rules() {
		return array(
			array('user_id, device_type, unique_id', 'required'),
			array('user_id', 'length', 'max'=>10),
			array('device_type', 'length', 'max'=>45),
			array('push_token', 'length', 'max'=>128),
			array('model, version, unique_id, auth_token', 'length', 'max'=>255),
			array('delete_date', 'safe'),
			array('push_token, model, version, delete_date', 'default', 'setOnEmpty' => true, 'value' => null),
			array('id, user_id, device_type, push_token, model, version, unique_id, auth_token, create_date, update_date, delete_date', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'id' => Yii::t('app', 'ID'),
			'user_id' => null,
			'device_type' => Yii::t('app', 'Device Type'),
			'push_token' => Yii::t('app', 'Push Token'),
			'model' => Yii::t('app', 'Model'),
			'version' => Yii::t('app', 'Version'),
			'unique_id' => Yii::t('app', 'Unique'),
			'auth_token' => Yii::t('app', 'Auth Token'),
			'create_date' => Yii::t('app', 'Create Date'),
			'update_date' => Yii::t('app', 'Update Date'),
			'delete_date' => Yii::t('app', 'Delete Date'),
			'user' => null,
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('device_type', $this->device_type, true);
		$criteria->compare('push_token', $this->push_token, true);
		$criteria->compare('model', $this->model, true);
		$criteria->compare('version', $this->version, true);
		$criteria->compare('unique_id', $this->unique_id, true);
		$criteria->compare('auth_token', $this->auth_token, true);
		$criteria->compare('create_date', $this->create_date, true);
		$criteria->compare('update_date', $this->update_date, true);
		$criteria->compare('delete_date', $this->delete_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}