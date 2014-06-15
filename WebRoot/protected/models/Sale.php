<?php

/**
 * This is the model class for table "sale".
 *
 * The followings are the available columns in table 'sale':
 * @property integer $id
 * @property string $type
 * @property integer $price
 * @property double $discount
 * @property integer $original_price
 * @property string $title
 * @property string $desc
 * @property string $content
 * @property string $end_time
 * @property string $tags
 * @property string $cover_image
 * @property integer $cover_image_width
 * @property integer $cover_image_height
 * @property string $booking_url
 * @property integer $user_id
 * @property integer $is_sync_weibo
 * @property string $source_data
 * @property string $source_url
 * @property integer $view_count
 * @property integer $booking_count
 * @property string $ctime
 * @property string $mtime
 * @property integer $status
 */
class Sale extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Sale the static model class
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
		return 'sale';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, user_id', 'required'),
			array('price, original_price, cover_image_width, cover_image_height, user_id, is_sync_weibo, view_count, booking_count, status', 'numerical', 'integerOnly'=>true),
			array('discount', 'numerical'),
			array('type', 'length', 'max'=>12),
			array('title, tags, cover_image, source_url', 'length', 'max'=>255),
			array('desc, content, end_time, booking_url, source_data, ctime, mtime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, price, discount, original_price, title, desc, content, end_time, tags, cover_image, cover_image_width, cover_image_height, booking_url, user_id, is_sync_weibo, source_data, source_url, view_count, booking_count, ctime, mtime, status', 'safe', 'on'=>'search'),
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
			'type' => 'Type',
			'price' => 'Price',
			'discount' => 'Discount',
			'original_price' => 'Original Price',
			'title' => 'Title',
			'desc' => 'Desc',
			'content' => 'Content',
			'end_time' => 'End Time',
			'tags' => 'Tags',
			'cover_image' => 'Cover Image',
			'cover_image_width' => 'Cover Image Width',
			'cover_image_height' => 'Cover Image Height',
			'booking_url' => 'Booking Url',
			'user_id' => 'User',
			'is_sync_weibo' => 'Is Sync Weibo',
			'source_data' => 'Source Data',
			'source_url' => 'Source Url',
			'view_count' => 'View Count',
			'booking_count' => 'Booking Count',
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('original_price',$this->original_price);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('cover_image',$this->cover_image,true);
		$criteria->compare('cover_image_width',$this->cover_image_width);
		$criteria->compare('cover_image_height',$this->cover_image_height);
		$criteria->compare('booking_url',$this->booking_url,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('is_sync_weibo',$this->is_sync_weibo);
		$criteria->compare('source_data',$this->source_data,true);
		$criteria->compare('source_url',$this->source_url,true);
		$criteria->compare('view_count',$this->view_count);
		$criteria->compare('booking_count',$this->booking_count);
		$criteria->compare('ctime',$this->ctime,true);
		$criteria->compare('mtime',$this->mtime,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}