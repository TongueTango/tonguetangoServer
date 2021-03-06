<?php

/**
 * This is the model base class for the table "messages".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "Messages".
 *
 * Columns in table "messages" available as properties of the model,
 * followed by relations of table "messages" available as properties of the model.
 *
 * @property string $id
 * @property string $user_id
 * @property string $message_type_id
 * @property string $message_header
 * @property string $message_body
 * @property string $message_path
 * @property string $create_date
 * @property string $update_date
 * @property string $delete_date
 *
 * @property MessageFavorites[] $messageFavorites
 * @property MessageRecipients[] $messageRecipients
 * @property MessageTypes $messageType
 * @property Users $user
 */
abstract class BaseMessages extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'messages';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'Messages|Messages', $n);
	}

	public static function representingColumn() {
		return 'message_header';
	}

	public function rules() {
		return array(
			array('user_id, message_type_id, message_header', 'required'),
			array('update_date', 'required', 'on' => 'update'),
			array('user_id, message_type_id', 'length', 'max'=>10),
			array('message_header, message_path', 'length', 'max'=>255),
			array('message_body, delete_date', 'safe'),
			array('message_body, message_path, delete_date', 'default', 'setOnEmpty' => true, 'value' => null),
			array('id, user_id, message_type_id, message_header, message_body, message_path, create_date, update_date, delete_date', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'messageFavorites' => array(self::HAS_MANY, 'MessageFavorites', 'message_id'),
			'messageRecipients' => array(self::HAS_MANY, 'MessageRecipients', 'message_id'),
			'messageType' => array(self::BELONGS_TO, 'MessageTypes', 'message_type_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'person' => array(self::HAS_ONE, 'People', array('person_id'=>'id'),'through'=>'user'),
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
			'message_type_id' => null,
			'message_header' => Yii::t('app', 'Message Header'),
			'message_body' => Yii::t('app', 'Message Body'),
			'message_path' => Yii::t('app', 'Message Path'),
			'create_date' => Yii::t('app', 'Create Date'),
			'update_date' => Yii::t('app', 'Update Date'),
			'delete_date' => Yii::t('app', 'Delete Date'),
			'messageFavorites' => null,
			'messageRecipients' => null,
			'messageType' => null,
			'user' => null,
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('message_type_id', $this->message_type_id);
		$criteria->compare('message_header', $this->message_header, true);
		$criteria->compare('message_body', $this->message_body, true);
		$criteria->compare('message_path', $this->message_path, true);
		$criteria->compare('create_date', $this->create_date, true);
		$criteria->compare('update_date', $this->update_date, true);
		$criteria->compare('delete_date', $this->delete_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}