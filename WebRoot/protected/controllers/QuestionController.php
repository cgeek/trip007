<?php

class QuestionController extends Controller
{
	private $_data;

	private $EARTH_RADIUS = 6371; //地球半径，平均半径为6371km

	public function actionPost()
	{
		$data['title'] = htmlspecialchars(urldecode(trim($_GET['title'])));
		$data['content'] = htmlspecialchars(urldecode(trim($_GET['content'])));
		$data['lat'] = $_GET['lat'];
		$data['lon'] = $_GET['lon'];
		$data['user_id'] = $_GET['user_id'];

		if(empty($data['content']) || empty($data['user_id'])) {
			$this->ajax_response(404,'内容或者用户id不能为空');
		}
		$this->_save_question($data);
	}

	private function _save_question($data)
	{
		$new_question = new Question;
		$new_question->title = $data['title'];
		$new_question->content = $data['content'];
		$new_question->lat = $data['lat'];
		$new_question->lon = $data['lon'];
		$new_question->ctime = time();
		$new_question->mtime = time();
		$new_question->user_id = $data['user_id'];
		$new_question->status = 0;

		if($new_question->save())
		{
			$new_question_id = $new_question->question_id;
			// 更新用户发表数量
			$question_db = Question::model()->findByPk($new_question_id);
			$this->_data = $this->_format_question($question_db);
			$this->_share_weibo($question_db->attributes);
			$this->ajax_response(200,'',$this->_data);
		} else {
			//var_dump($new_question->getErrors());
			$this->ajax_response(500,'插入失败');
		}
	}

	private function _share_weibo($data)
	{
		Yii::import('ext.qqWeibo.QqWeibo',true);
		$user_db = User::model()->findByPk($data['user_id'])->attributes;

		$_SESSION['t_access_token'] = $user_db['access_token'];
		$_SESSION['t_openid'] = $user_db['out_uid'];
		$_SESSION['t_openkey'] = $user_db['t_openkey'];

		$content = $data['content'];
		OAuth::init('801288215', '6838887096887f3bbcb44fd13369d159');
		$params = array(
		 	'content' => $content
		 );
		$r = Tencent::api('t/add', $params, 'POST');
	}


	public function actionDetailJSON()
	{
		$question_id = intval($_GET['question_id']) > 1 ? intval($_GET['question_id']) : 1;
		if(empty($question_id) || $question_id <= 0) {
			$this->ajax_response(404,'参数不正确');
		}

		$question_db = Question::model()->findByPk($question_id);
		if(empty($question_db)) {
			$this->ajax_response(404,'参数不正确');
		}

		//update view_count
		Question::model()->updateByPk($question_id, array('view_count'=> $question_db['view_count'] +1));

		$this->_data = $this->_format_question($question_db);
		$answers = $this->_get_answerlist($question_id);
		$this->_data['answer_count']= $answers['count'];
		$this->ajax_response(200,'',$this->_data);
	}

	private function _get_answerlist($question_id)
	{
		$limit = 100;
		$criteria = new CDbCriteria;
		$criteria->addCondition("status=0");
		$criteria->addCondition("question_id=$question_id");
		$criteria->order = ' `ctime` DESC';
		$criteria->limit = $limit;
		$count = Answer::model()->count($criteria);
		$answer_list_db = Answer::model()->findAll($criteria);
		$answer_list = array();
		foreach($answer_list_db as $answer_db)
		{
			$answer = $answer_db->attributes;
			$user_db = User::model()->findByPk($answer['user_id']);
			if(!empty($user_db)) {
				$answer['user_name'] = $user_db['user_name'];
				$answer['user_avatar'] = $user_db['avatar'];
			}
			$answer['ctime'] = human_time($answer['ctime']);
			$answer_list[] = $answer;
		}
		return array('count'=> $count, 'answer_list' => $answer_list);
	}

	public function actionListJSON()
	{
		$lat = $_GET['lat'];
		$lon = $_GET['lon'];
		$distance = isset($_GET['distance']) ? $_GET['distance'] : 2;
		$tags = isset($_GET['tags']) ? $_GET['tags'] : '';
		if(!empty($lat) && $lat > 0 && !empty($lon) && $lon > 0) {
			$squares = $this->_returnSquarePoint($lat, $lon, $distance);
		}
		$p = isset($_GET['page'])  ? intval($_GET['page']) : 1;
		$per_page = 10;
		$offset = ($p - 1) * $per_page;
		$limit = $per_page; 
		$criteria = new CDbCriteria;
		$criteria->addCondition("status=0");
		//显示附近
		if(isset($squares) && !empty($squares)) {
			$criteria->addCondition("lat<>0");
			$criteria->addCondition("lat>{$squares['right-bottom']['lat']}");
			$criteria->addCondition("lat>{$squares['right-bottom']['lat']}");
			$criteria->addCondition("lon>{$squares['left-top']['lon']}");
			$criteria->addCondition("lon<{$squares['right-bottom']['lon']}");
		}
		if(!empty($tags)) {
			$criteria->addSearchCondition('tags', $tags);
		}
		$criteria->order = ' `view_count` DESC';
		$criteria->order = ' `ctime` DESC';
		$criteria->limit = $limit;
		$criteria->offset = $offset;
		$count = Question::model()->count($criteria);
		$data = Question::model()->findAll($criteria);
		$question_list = array();
		foreach($data as $question)
		{
			$question_list[] = $this->_format_question($question->attributes, $lat, $lon);
		}
		$this->_data['total'] = $count;
		$this->_data['items'] = $question_list;
		$this->ajax_response(200,'',$this->_data);
	}

	private function _format_question($question_db , $lat =0, $lon = 0)
	{
		$user = User::model()->findByPk($question_db['user_id']);
		if($lat > 0 && $lon > 0 && $question_db['lat'] > 0 && $question_db['lon'] > 0) {
			$distance = GetDistance($lat, $lon, $question_db['lat'], $question_db['lon']);
			if($distance <= 0) {
				$distance = '';	
			} else if($distance < 1000) {
				$distance = intval($distance * 1000) . "米";
			} else {
				$distance = round($distance, 2) . "千米";
			}
		} else {
			$distance = '';
		}
		$data = array(
			'question_id' => $question_db['question_id'],
			'title' => $question_db['title'],
			'content' => $question_db['content'],
			'tags' => empty($question_db['tags']) ? '': $question_db['tags'],
			'answer_count' => $question_db['answer_count'],
			'view_count' => $question_db['view_count'],
			'distance' => $distance,
			'ctime' =>  human_time($question_db['ctime']),
			'user' => array(
				'user_id' => $question_db['user_id'],
				'user_avatar' => empty($user['avatar']) ? '' : $user['avatar'],
				'user_name' => empty($user['user_name']) ? '' : $user['user_name']
			)
		);
		return $data;
	}


	private function _returnSquarePoint($lat, $lon, $distance = 0.5)
	{
		$dlon =  2 * asin(sin($distance / (2 * $this->EARTH_RADIUS)) / cos(deg2rad($lat)));
		$dlon = rad2deg($dlon);

		$dlat = $distance/$this->EARTH_RADIUS;
		$dlat = rad2deg($dlat);

		return array(
			'left-top'=>array('lat'=>$lat + $dlat,'lon'=>$lon-$dlon),
			'right-top'=>array('lat'=>$lat + $dlat, 'lon'=>$lon + $dlon),
			'left-bottom'=>array('lat'=>$lat - $dlat, 'lon'=>$lon - $dlon),
			'right-bottom'=>array('lat'=>$lat - $dlat, 'lon'=>$lon + $dlon)
			);
	}

	public function actionLocoySpider()
	{
		$postData = array(
			'title' => trim($_POST['title']),
			'content' => trim($_POST['content']),
			'user_name' => $_POST['username'],
			'ctime' => $_POST['ctime'],
			'answers' => $_POST['answers'],
			'qyer_question_id' => $_POST['questionId'],
			'tags' => $_POST['tags'],
			'user_id'=> $_POST['user_id'],
			'source_url' => $_POST['采集页网址'],
		);
		
		//检查是否重复

		if(empty($postData['user_name'])) {
			$postData['user_name'] = 'miaomiao';
		}
		//判断用户是否已经存在
		$user_id = $this->_get_user_id_by_username($postData['user_name'], $postData['user_id']);
		if(empty($user_id)) {
			error_log("=====ask====>: empty user_id");
			return NULL;
		}

		//插入问题
		$ctime = !empty($postData['ctime']) ? strtotime($postData['ctime']) : time();
		$new_question = new Question;
		$new_question->title = $postData['title'];
		$new_question->content = $postData['content'];
		$new_question->user_id = $user_id;
		$new_question->ctime = $ctime;
		$new_question->mtime = date('Y-m-d h:m:s', $ctime);
		$new_question->source_type = 'spider';
		$new_question->source_url = $postData['source_url'];
		$new_question->source_meta_info =  json_encode(array( 'qyer_question_id' => $postData['qyer_question_id']));
		$new_question->source_title_md5 = md5($postData['source_url']);
		$new_question->status = 0;

		if($new_question->save())
		{
			$question_id = $new_question->question_id;
			// 更新用户发表数量
			//$question_db = Question::model()->findByPk($new_question_id);
		} else {
			error_log("=====ask====>: insert question error");
			var_dump($new_question->getErrors());
			return NULL;
		}
		
		$tag_ids = array();
		//判断tag是否已经存在
		if(!empty($postData['tags'])) {
			$tag_array = explode("\r", $postData['tags']);
			foreach($tag_array as $tag) {
				$tag_db = Tag::model()->find("tag_name=:tag_name", array(":tag_name"=>$tag));
				if(!empty($tag_db)) {
					$tag_db = $tag_db->attributes;
					$tag_id = $tag_db['tag_id'];
				} else {
					$tag_new = new Tag;
					$tag_new->tag_name = trim($tag);
					$tag_new->tag_meta = 'question';
					$tag_new->ctime = time();
					$tag_new->status = 0;
					if($tag_new->save()) {
						$tag_id = $tag_new->tag_id;
					}
				}
				if(isset($tag_id) || $tag_id > 0 ) {
					$tag_ids[] = $tag_id;
				}
			}
		}

		if(!empty($tag_ids)) {
			foreach($tag_ids as $tag_id) {
				$tag_question_db = TagMeta::model()->find("tag_id=:tag_id AND question_id=:question_id", array(":tag_id"=> $tag_id, ":question_id"=>$question_id));
				if(empty($tag_question_db)) {
					$tag_meta_new = new TagMeta;
					$tag_meta_new->tag_id = $tag_id;
					$tag_meta_new->question_id = $question_id;
					$tag_meta_new->save();
				}
			}
		}

		if(!empty($postData['answers'])) {
			$answer_list = explode("\r", $postData['answers']);
			if (!empty($answer_list)) {
				foreach($answer_list as $answer) {
					$answer_array = explode("||", $answer);
					if(count($answer_array) == 4) {
						$answer_data['user_id'] = $answer_array[0];
						$answer_data['user_name'] = $answer_array[1];
						$answer_data['ctime'] = $answer_array[2];
						$answer_data['content'] = $answer_array[3];
						$answer_data['question_id'] = $question_id;
						$this->_insert_answer($answer_data);
					}
				}
			}
		}
		echo 'success';
	}

	private function _get_user_id_by_username($user_name, $qyer_user_id = 0)
	{
		$user_id = '';
		$user_db = User::model()->find("user_name=:username",array(":username"=>$user_name));
		if (!empty($user_db)) {
			$user_db = $user_db->attributes;
			$user_id = $user_db['user_id'];
		} else {
			$user_model = new User;
			$user_model->password =  md5('miaomiao123');
			$user_model->user_name  = $user_name;
			if(!empty($postData['user_id'])) {
				$user_model->avatar = 'http://qycenter.qyer.com/avatar.php?uid=' . $qyer_user_id . '&size=small&random=1';
				$user_model->qyer_user_id = $qyer_user_id;
			}
			$user_model->ctime = time();
			if($user_model->save()) {
				$user_id = $user_model->user_id;
			}
		}
		
		return $user_id;

	}

	private function _insert_answer($answer_data)
	{
		if(empty($answer_data)) {
			error_log("=====ask====>: empty answer_data");
			return ;
		}
		$user_id = $this->_get_user_id_by_username($answer_data['user_name'], $answer_data['user_id']);
		if(empty($user_id)) {
			error_log("=====ask====>: insert answer error no user_id");
			return ;
		}
		//判断用户
		$ctime = !empty($answer_data['ctime']) ? strtotime($answer_data['ctime']) : time();
		$answer_new = new Answer;
		$answer_new->question_id = $answer_data['question_id'];
		$answer_new->content = $answer_data['content'];
		$answer_new->user_id = $user_id;
		$answer_new->ctime = $ctime;
		$answer_new->mtime = date('Y-m-d h:m:s', $ctime);

		if(!$answer_new->save()) {
			error_log("=====ask====>: insert answer error no user_id");
			var_dump($answer_new->getErrors());
		}
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}
