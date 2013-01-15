<?php

class AnswerController extends Controller
{
	private $_data;

	public function actionListJSON($question_id)
	{
		$question_id = $_GET['question_id'];
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
				$answer['user'] = array(
					'user_id' => $user_db['user_id'],
					'user_name' => $user_db['user_name'],
					'user_avatar' => $user_db['avatar']
				);
			}
			$answer['content'] = strtr($answer['content'], '&nbsp;','');
			$answer['content'] = strip_tags($answer['content']);
			$answer['ctime'] = human_time($answer['ctime']);
			$answer['lat'] = empty($answer['lat']) ? '' : $answer['lat'];
			$answer['lon'] = empty($answer['lon']) ? '' : $answer['lon'];
			$answer_list[] = $answer;
		}
		$data =	array('total'=> $count, 'items' => $answer_list);
		$this->ajax_response(200, '', $data);
	}

	public function actionPost()
	{
		$data['content'] = htmlspecialchars(addslashes(trim($_GET['content'])));
		$data['question_id'] = $_GET['question_id'];
		$data['lat'] = $_GET['lat'];
		$data['lon'] = $_GET['lon'];
		$data['user_id'] = $_GET['user_id'];

		if(empty($data['question_id']) || empty($data['content']) || empty($data['user_id'])) {
			$this->ajax_response(404,'内容或者用户id不能为空');
		}
		//echo json_encode($data);die();
		$this->_save_answer($data);
	}

	private function _save_answer($data)
	{
		$new_answer = new Answer;
		$new_answer->content = $data['content'];
		$new_answer->question_id = $data['question_id'];
		$new_answer->lat = $data['lat'];
		$new_answer->lon = $data['lon'];
		$new_answer->ctime = time();
		$new_answer->mtime = time();
		$new_answer->user_id = $data['user_id'];
		$new_answer->status = 0;

		if($new_answer->save())
		{
			$new_answer_id = $new_answer->answer_id;
			// 更新用户发表数量
			$this->_data = $data;
			$this->_data['answer_id'] = $new_answer_id;
			$this->_data['ctime'] = human_time(time());
			$user_db = User::model()->findByPk($data['user_id']);
			if(!empty($user_db)) {
				$this->_data['user_name'] = $user_db['user_name'];
				$this->_data['user_avatar'] = $user_db['avatar'];
			}
			$this->_update_answer_count($data['question_id']);
			$this->_share_weibo($data);
			$this->ajax_response(200,'',$this->_data);
		} else {
			//var_dump($new_answer->getErrors());
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

		$question_db = Question::model()->findByPk($data['question_id'])->attributes;
		$question_user = User::model()->findByPk($question_db['user_id'])->attributes;

		$content = $data['content'] . "//回复@" .$question_user['user_name'] . "：" . $question_db['content'];
		OAuth::init('801288215', '6838887096887f3bbcb44fd13369d159');
		$params = array(
		 	'content' => $content
		 );
		$r = Tencent::api('t/add', $params, 'POST');
	}

	public function actionDelete($id = NULL)
	{
		if(!empty($id)) {
			$answer_id = $id;
		} else {
			$answer_id = $_GET['answer_id'];
		}
		if(empty($answer_id) || $answer_id <=0)
			$this->ajax_response(500, '参数不正确');
		$answer_db = Answer::model()->findByPk($answer_id);
		if(empty($answer_db))
			$this->ajax_response(404,'该信息不存在');

		$data = array('status'=> -1);
		Answer::model()->updateByPk($answer_id, $data);
		// 更新用户发表数量

		$this->_update_answer_count($answer_db['question_id']);
		$this->ajax_response(200,'删除成功');
	}

	private function _update_answer_count($question_id)
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition("status=0");
		$criteria->addCondition("question_id=$question_id");
		$count = Answer::model()->count($criteria);
		Question::model()->updateByPk($question_id, array('answer_count'=> $count));
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
