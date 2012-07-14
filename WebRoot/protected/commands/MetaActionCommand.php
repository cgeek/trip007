<?php
Yii::import('ext.sinaWeibo.SinaWeibo',true);
class MetaActionCommand extends CConsoleCommand
{

	public function actionCrontab()
	{
		$sql = "SELECT * FROM meta_job WHERE status=0;";
		$sql_command = Yii::app()->db->createCommand($sql);
		$job_queue = $sql_command->queryAll();
		if(empty($job_queue))
		{
			return ;
		}
		foreach($job_queue as $job)
		{
			$cron_time = strtotime($job['cron_time']);
			$now = time();
			if($cron_time < $now) {
				$action_name = $job['meta_action'];
				$r = $this->$action_name($job);
				$status = $r ? '2' : '-1';
				$update = "UPDATE `meta_job` SET `status`={$status} WHERE `id`= {$job['id']}";
				$update_command = Yii::app()->db->createCommand($update);
				$update_command->execute();
			}
		}
	}

	private function process_cron_pub_pin($job)
	{
		$params = json_decode($job['params'], true);
		if(! isset($params['pin_id'])  || $params['pin_id'] <= 0) {
			Yii::log("job params error! job id is {$job['id']}",'error');
			return false;
		}
		$sql = "SELECT * FROM `pin` WHERE pin_id = {$params['pin_id']} AND status=1;";
		$sql_command = Yii::app()->db->createCommand($sql);
		$pin = $sql_command->queryRow();
		if(!empty($pin)) {
			$sql = "UPDATE `pin` SET `status`=0 WHERE `pin_id`= {$params['pin_id']}";
			$sql_command = Yii::app()->db->createCommand($sql);
			$sql_command->execute();

			if($pin['is_sync_weibo'] == 1) {
				$this->_sync_weibo($pin);
			}
			return true;
		} else {
			Yii::log("can not get pin by job! job id is {$job['id']}",'error');
			return false;
		}
	}

	private function _sync_weibo($pin)
	{
		$sql = "SELECT * FROM `user` WHERE `user_id`={$pin['user_id']};";
		$sql_command = Yii::app()->db->createCommand($sql);
		$user = $sql_command->queryRow();
		if(empty($user)) {
			Yii::log("sync_weibo error, user not exist,{$pin['user_id']}",'error');
			return false;
		}
		$text = cut_str($pin['desc'],120);
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $user['out_token']);
		if(!empty($pin['cover_image']))
		{
			$r = $c->upload($text, upimage($pin['cover_image'],'big'));
		} else {
			$r = $c->update($text);
		}
		if(isset($r['id'])) {
			Yii::log("sync weibo $pin[pin_id] ==> $r[id]",'error');
		} else {
			Yii::log("sync weibo error $pin[pin_id]",'error');
		}
	}

}
