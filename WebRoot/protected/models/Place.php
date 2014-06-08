<?php

/**
 * This is the model class for table "place".
 *
 * The followings are the available columns in table 'place':
 * @property integer $id
 * @property integer $country_id
 * @property string $place_name
 * @property string $place_name_en
 * @property string $lat
 * @property string $lng
 * @property string $continent
 * @property string $continent_en
 * @property string $cover_image
 * @property integer $count
 * @property integer $flag
 * @property integer $is_country
 * @property integer $is_hot
 * @property integer $is_guide
 * @property string $overview_url
 * @property integer $photocount
 * @property string $photos
 */
class Place extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Place the static model class
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
		return 'place';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('place_name', 'required'),
			array('country_id, count, flag, is_country, is_hot, is_guide, photocount', 'numerical', 'integerOnly'=>true),
			array('place_name, place_name_en, lat, lng, continent, continent_en, cover_image', 'length', 'max'=>255),
			array('overview_url, photos', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, country_id, place_name, place_name_en, lat, lng, continent, continent_en, cover_image, count, flag, is_country, is_hot, is_guide, overview_url, photocount, photos', 'safe', 'on'=>'search'),
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
			'country_id' => 'Country',
			'place_name' => 'Place Name',
			'place_name_en' => 'Place Name En',
			'lat' => 'Lat',
			'lng' => 'Lng',
			'continent' => 'Continent',
			'continent_en' => 'Continent En',
			'cover_image' => 'Cover Image',
			'count' => 'Count',
			'flag' => 'Flag',
			'is_country' => 'Is Country',
			'is_hot' => 'Is Hot',
			'is_guide' => 'Is Guide',
			'overview_url' => 'Overview Url',
			'photocount' => 'Photocount',
			'photos' => 'Photos',
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
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('place_name',$this->place_name,true);
		$criteria->compare('place_name_en',$this->place_name_en,true);
		$criteria->compare('lat',$this->lat,true);
		$criteria->compare('lng',$this->lng,true);
		$criteria->compare('continent',$this->continent,true);
		$criteria->compare('continent_en',$this->continent_en,true);
		$criteria->compare('cover_image',$this->cover_image,true);
		$criteria->compare('count',$this->count);
		$criteria->compare('flag',$this->flag);
		$criteria->compare('is_country',$this->is_country);
		$criteria->compare('is_hot',$this->is_hot);
		$criteria->compare('is_guide',$this->is_guide);
		$criteria->compare('overview_url',$this->overview_url,true);
		$criteria->compare('photocount',$this->photocount);
		$criteria->compare('photos',$this->photos,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}