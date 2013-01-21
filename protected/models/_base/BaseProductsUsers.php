<?php

/**
 * This is the model base class for the table "products_users".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "ProductsUsers".
 *
 * Columns in table "products_users" available as properties of the model,
 * and there are no model relations.
 *
 * @property string $user_id
 * @property string $product_id
 *
 */
abstract class BaseProductsUsers extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'products_users';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'ProductsUsers|ProductsUsers', $n);
	}

	public static function representingColumn() {
		return array(
			'user_id',
			'product_id',
		);
	}

	public function rules() {
		return array(
			array('user_id, product_id', 'required'),
			array('user_id, product_id', 'length', 'max'=>10),
			array('user_id, product_id', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'user_id' => null,
			'product_id' => null,
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('product_id', $this->product_id);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}