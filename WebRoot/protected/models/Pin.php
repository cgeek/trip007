<?php

/**
 * This is the model class for table "pin".
 *
 * The followings are the available columns in table 'pin':
 * @property integer $pin_id
 * @property integer $type
 * @property string $out_feed_id
 * @property string $title
 * @property string $desc
 * @property string $content
 * @property string $tags
 * @property string $cover_image
 * @property integer $cover_image_width
 * @property integer $cover_image_height
 * @property integer $user_id
 * @property integer $is_sync_weibo
 * @property string $source_data
 * @property string $source_url
 * @property integer $view_count
 * @property integer $ctime
 * @property string $mtime
 * @property integer $status
 */
class Pin extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Pin the static model class
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
		return 'pin';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, ctime', 'required'),
			array('type, cover_image_width, cover_image_height, user_id, is_sync_weibo, view_count, ctime, status', 'numerical', 'integerOnly'=>true),
			array('out_feed_id', 'length', 'max'=>64),
			array('title, tags, cover_image, source_url', 'length', 'max'=>255),
			array('desc, content, source_data, mtime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('pin_id, type, out_feed_id, title, desc, content, tags, cover_image, cover_image_width, cover_image_height, user_id, is_sync_weibo, source_data, source_url, view_count, ctime, mtime, status', 'safe', 'on'=>'search'),
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
			'pin_id' => 'Pin',
			'type' => 'Type',
			'out_feed_id' => 'Out Feed',
			'title' => 'Title',
			'desc' => 'Desc',
			'content' => 'Content',
			'tags' => 'Tags',
			'cover_image' => 'Cover Image',
			'cover_image_width' => 'Cover Image Width',
			'cover_image_height' => 'Cover Image Height',
			'user_id' => 'User',
			'is_sync_weibo' => 'Is Sync Weibo',
			'source_data' => 'Source Data',
			'source_url' => 'Source Url',
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

		$criteria->compare('pin_id',$this->pin_id);
		$criteria->compare('type',$this->type);
		$criteria->compare('out_feed_id',$this->out_feed_id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('cover_image',$this->cover_image,true);
		$criteria->compare('cover_image_width',$this->cover_image_width);
		$criteria->compare('cover_image_height',$this->cover_image_height);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('is_sync_weibo',$this->is_sync_weibo);
		$criteria->compare('source_data',$this->source_data,true);
		$criteria->compare('source_url',$this->source_url,true);
		$criteria->compare('view_count',$this->view_count);
		$criteria->compare('ctime',$this->ctime);
		$criteria->compare('mtime',$this->mtime,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}