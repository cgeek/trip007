<?php

/**
 * This is the model class for table "question".
 *
 * The followings are the available columns in table 'question':
 * @property integer $question_id
 * @property string $title
 * @property string $content
 * @property integer $user_id
 * @property integer $ctime
 * @property integer $mtime
 * @property integer $view_count
 * @property integer $answer_count
 * @property string $lat
 * @property string $lon
 * @property integer $status
 */
class Question extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Question the static model class
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
		return 'question';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content, user_id, ctime, mtime', 'required'),
			array('user_id, ctime, mtime, view_count, answer_count, status', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('lat, lon', 'length', 'max'=>16),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('question_id, title, content, user_id, ctime, mtime, view_count, answer_count, lat, lon, status', 'safe', 'on'=>'search'),
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
			'question_id' => 'Question',
			'title' => 'Title',
			'content' => 'Content',
			'user_id' => 'User',
			'ctime' => 'Ctime',
			'mtime' => 'Mtime',
			'view_count' => 'View Count',
			'answer_count' => 'Answer Count',
			'lat' => 'Lat',
			'lon' => 'Lon',
			'status' => 'Status',
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

		$criteria->compare('question_id',$this->question_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('ctime',$this->ctime);
		$criteria->compare('mtime',$this->mtime);
		$criteria->compare('view_count',$this->view_count);
		$criteria->compare('answer_count',$this->answer_count);
		$criteria->compare('lat',$this->lat,true);
		$criteria->compare('lon',$this->lon,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}