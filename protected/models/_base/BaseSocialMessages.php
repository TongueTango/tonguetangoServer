<?php

/**
 * This is the model base class for the table "social_messages".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "SocialMessages".
 *
 * Columns in table "social_messages" available as properties of the model,
 * followed by relations of table "social_messages" available as properties of the model.
 *
 * @property integer $id
 * @property string $user_id
 * @property string $message_type
 * @property string $message_body
 * @property string $create_date
 * @property string $update_date
 * @property string $delete_date
 *
 * @property Users $user
 */
abstract class BaseSocialMessages extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'social_messages';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'SocialMessages|SocialMessages', $n);
	}

	public static function representingColumn() {
		return 'message_type';
	}

	public function rules() {
		return array(
			array('user_id, message_type, message_body', 'required'),
			array('user_id', 'length', 'max'=>10),
			array('message_type', 'length', 'max'=>15),
			array('message_body', 'length', 'max'=>300),
			array('delete_date', 'safe'),
			array('delete_date', 'default', 'setOnEmpty' => true, 'value' => null),
			array('id, user_id, message_type, message_body, create_date, update_date, delete_date', 'safe', 'on'=>'search'),
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
			'message_type' => Yii::t('app', 'Message Type'),
			'message_body' => Yii::t('app', 'Message Body'),
			'create_date' => Yii::t('app', 'Create Date'),
			'update_date' => Yii::t('app', 'Update Date'),
			'delete_date' => Yii::t('app', 'Delete Date'),
			'user' => null,
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('message_type', $this->message_type, true);
		$criteria->compare('message_body', $this->message_body, true);
		$criteria->compare('create_date', $this->create_date, true);
		$criteria->compare('update_date', $this->update_date, true);
		$criteria->compare('delete_date', $this->delete_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}