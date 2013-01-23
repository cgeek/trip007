<?php

class FullbuildCommand extends CConsoleCommand
{
	private $_tag_array = array();

	public function actionTest()
	{
		echo 'test';
	}

	public function actionTagToQuestion()
	{
		$sql = "SELECT tag_id, tag_name FROM tag";
		$sql_command = Yii::app()->db->createCommand($sql);
		$tags = $sql_command->queryAll();
		foreach($tags as $tag) {
			$this->_tag_array[$tag['tag_id']] = $tag['tag_name']; 
		}

		$sql = "SELECT count(*) as total FROM question";
		$sql_command = Yii::app()->db->createCommand($sql);
		$count_query = $sql_command->queryRow();
		$total = $count_query['total'];
		//分页
		$pageSize = 10;
		for($i=0; $i<(int)($total/$pageSize + 1); $i++) {

			$offset = $i * $pageSize;
			$this->_get_questions($pageSize, $offset);
		}
	}

	private function _get_questions($limit, $offset)
	{
		$questions = Yii::app()->db->createCommand()
			->select('question_id')
			->from('question')
			->limit($limit, $offset)
			->queryAll();
		foreach($questions as $question) {
			$question_id = $question['question_id'];
			$tag_ids = Yii::app()->db->createCommand()->select('tag_id')->from('tag_meta')->where('question_id=:id', array(':id'=>$question_id))->queryAll();
			$tags = array();
			foreach($tag_ids as $tag) {
				if(isset($this->_tag_array[$tag['tag_id']])) {
					$tags[] = trim($this->_tag_array[$tag['tag_id']]);
				}
			}
			$tags = implode( ';', $tags);
			echo "question_id: $question_id : $tags  \n";
			$sql = "UPDATE  `question` SET  `tags` =  '{$tags}' WHERE  `question_id` =$question_id;";
			$sql_command = Yii::app()->db->createCommand($sql);
			$count_query = $sql_command->query();
		}
	}
}
