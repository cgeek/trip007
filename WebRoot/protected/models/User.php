<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $user_id
 * @property string $user_name
 * @property string $password
 * @property string $email
 * @property string $website
 * @property string $location
 * @property string $province
 * @property string $description
 * @property string $avatar
 * @property string $avatar_large
 * @property string $gender
 * @property string $out_source
 * @property string $out_uid
 * @property string $out_token
 * @property integer $ctime
 * @property string $mtime
 * @property string $last_login_time
 * @property integer $status
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ctime', 'required'),
			array('ctime, status', 'numerical', 'integerOnly'=>true),
			array('user_name, password, gender', 'length', 'max'=>64),
			array('email, website, location, province, description, avatar, avatar_large, out_source, out_uid, out_token', 'length', 'max'=>255),
			array('mtime, last_login_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, user_name, password, email, website, location, province, description, avatar, avatar_large, gender, out_source, out_uid, out_token, ctime, mtime, last_login_time, status', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'user_name' => 'User Name',
			'password' => 'Password',
			'email' => 'Email',
			'website' => 'Website',
			'location' => 'Location',
			'province' => 'Province',
			'description' => 'Description',
			'avatar' => 'Avatar',
			'avatar_large' => 'Avatar Large',
			'gender' => 'Gender',
			'out_source' => 'Out Source',
			'out_uid' => 'Out Uid',
			'out_token' => 'Out Token',
			'ctime' => 'Ctime',
			'mtime' => 'Mtime',
			'last_login_time' => 'Last Login Time',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('website',$this->website,true);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('province',$this->province,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('avatar',$this->avatar,true);
		$criteria->compare('avatar_large',$this->avatar_large,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('out_source',$this->out_source,true);
		$criteria->compare('out_uid',$this->out_uid,true);
		$criteria->compare('out_token',$this->out_token,true);
		$criteria->compare('ctime',$this->ctime);
		$criteria->compare('mtime',$this->mtime,true);
		$criteria->compare('last_login_time',$this->last_login_time,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}