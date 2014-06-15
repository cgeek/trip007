<?php

/**
 * This is the model class for table "note".
 *
 * The followings are the available columns in table 'note':
 * @property integer $id
 * @property string $title
 * @property integer $place_id
 * @property string $desc
 * @property string $content
 * @property string $tags
 * @property string $cover_image
 * @property integer $user_id
 * @property integer $view_count
 * @property string $ctime
 * @property string $mtime
 * @property integer $status
 */
class Note extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Note the static model class
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
		return 'note';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('place_id, user_id, view_count, status', 'numerical', 'integerOnly'=>true),
			array('title, cover_image', 'length', 'max'=>255),
			array('desc, content, tags, ctime, mtime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, place_id, desc, content, tags, cover_image, user_id, view_count, ctime, mtime, status', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'place_id' => 'Place',
			'desc' => 'Desc',
			'content' => 'Content',
			'tags' => 'Tags',
			'cover_image' => 'Cover Image',
			'user_id' => 'User',
			'view_count' => 'View Count',
			'ctime' => 'Ctime',
			'mtime' => 'Mtime',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('place_id',$this->place_id);
		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('cover_image',$this->cover_image,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('view_count',$this->view_count);
		$criteria->compare('ctime',$this->ctime,true);
		$criteria->compare('mtime',$this->mtime,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}