<?php

/**
 * This is the model class for table "meta_job".
 *
 * The followings are the available columns in table 'meta_job':
 * @property integer $id
 * @property string $meta_action
 * @property string $params
 * @property integer $status
 * @property integer $ctime
 * @property string $cron_time
 */
class MetaJob extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MetaJob the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'meta_job';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('meta_action, cron_time', 'required'),
			array('status, ctime', 'numerical', 'integerOnly'=>true),
			array('meta_action, params', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, meta_action, params, status, ctime, cron_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'meta_action' => 'Meta Action',
			'params' => 'Params',
			'status' => 'Status',
			'ctime' => 'Ctime',
			'cron_time' => 'Cron Time',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('meta_action',$this->meta_action,true);
		$criteria->compare('params',$this->params,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('ctime',$this->ctime);
		$criteria->compare('cron_time',$this->cron_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}