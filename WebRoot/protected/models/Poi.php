<?php

/**
 * This is the model class for table "poi".
 *
 * The followings are the available columns in table 'poi':
 * @property integer $id
 * @property integer $place_id
 * @property string $firstname
 * @property string $secnodname
 * @property string $chinesename
 * @property string $englishname
 * @property string $cover_image
 * @property integer $beennumber
 * @property integer $cateid
 * @property string $cate_name
 * @property string $localname
 * @property string $lat
 * @property string $lng
 * @property integer $mapstatus
 * @property integer $grade
 * @property double $gradescores
 * @property string $introduction
 * @property string $address
 * @property string $site
 * @property string $phone
 * @property string $wayto
 * @property string $opentime
 * @property string $price
 * @property string $tips
 * @property integer $updatetime
 * @property integer $commentcounts
 * @property double $duration
 * @property integer $img_count
 * @property integer $recommendnumber
 * @property string $recommendstr
 * @property integer $recommendscores
 * @property string $images
 * @property string $qyerurl
 * @property string $comment_list
 */
class Poi extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Poi the static model class
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
		return 'poi';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('place_id, beennumber, cateid, mapstatus, grade, updatetime, commentcounts, img_count, recommendnumber, recommendscores', 'numerical', 'integerOnly'=>true),
			array('gradescores, duration', 'numerical'),
			array('firstname, secnodname, chinesename, englishname, cover_image, cate_name, localname, lat, lng, site, phone, opentime, price, recommendstr, qyerurl', 'length', 'max'=>255),
			array('introduction, address, wayto, tips, images, comment_list', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, place_id, firstname, secnodname, chinesename, englishname, cover_image, beennumber, cateid, cate_name, localname, lat, lng, mapstatus, grade, gradescores, introduction, address, site, phone, wayto, opentime, price, tips, updatetime, commentcounts, duration, img_count, recommendnumber, recommendstr, recommendscores, images, qyerurl, comment_list', 'safe', 'on'=>'search'),
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
			'place_id' => 'Place',
			'firstname' => 'Firstname',
			'secnodname' => 'Secnodname',
			'chinesename' => 'Chinesename',
			'englishname' => 'Englishname',
			'cover_image' => 'Cover Image',
			'beennumber' => 'Beennumber',
			'cateid' => 'Cateid',
			'cate_name' => 'Cate Name',
			'localname' => 'Localname',
			'lat' => 'Lat',
			'lng' => 'Lng',
			'mapstatus' => 'Mapstatus',
			'grade' => 'Grade',
			'gradescores' => 'Gradescores',
			'introduction' => 'Introduction',
			'address' => 'Address',
			'site' => 'Site',
			'phone' => 'Phone',
			'wayto' => 'Wayto',
			'opentime' => 'Opentime',
			'price' => 'Price',
			'tips' => 'Tips',
			'updatetime' => 'Updatetime',
			'commentcounts' => 'Commentcounts',
			'duration' => 'Duration',
			'img_count' => 'Img Count',
			'recommendnumber' => 'Recommendnumber',
			'recommendstr' => 'Recommendstr',
			'recommendscores' => 'Recommendscores',
			'images' => 'Images',
			'qyerurl' => 'Qyerurl',
			'comment_list' => 'Comment List',
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
		$criteria->compare('place_id',$this->place_id);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('secnodname',$this->secnodname,true);
		$criteria->compare('chinesename',$this->chinesename,true);
		$criteria->compare('englishname',$this->englishname,true);
		$criteria->compare('cover_image',$this->cover_image,true);
		$criteria->compare('beennumber',$this->beennumber);
		$criteria->compare('cateid',$this->cateid);
		$criteria->compare('cate_name',$this->cate_name,true);
		$criteria->compare('localname',$this->localname,true);
		$criteria->compare('lat',$this->lat,true);
		$criteria->compare('lng',$this->lng,true);
		$criteria->compare('mapstatus',$this->mapstatus);
		$criteria->compare('grade',$this->grade);
		$criteria->compare('gradescores',$this->gradescores);
		$criteria->compare('introduction',$this->introduction,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('site',$this->site,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('wayto',$this->wayto,true);
		$criteria->compare('opentime',$this->opentime,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('tips',$this->tips,true);
		$criteria->compare('updatetime',$this->updatetime);
		$criteria->compare('commentcounts',$this->commentcounts);
		$criteria->compare('duration',$this->duration);
		$criteria->compare('img_count',$this->img_count);
		$criteria->compare('recommendnumber',$this->recommendnumber);
		$criteria->compare('recommendstr',$this->recommendstr,true);
		$criteria->compare('recommendscores',$this->recommendscores);
		$criteria->compare('images',$this->images,true);
		$criteria->compare('qyerurl',$this->qyerurl,true);
		$criteria->compare('comment_list',$this->comment_list,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}