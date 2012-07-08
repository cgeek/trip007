<?php
Yii::import('ext.sinaWeibo.SinaWeibo',true);
class CrawlWeiboCommand extends CConsoleCommand
{

	public function actionCrawl()
	{
		echo "start to crawl...\n";
		echo "start to get list...\n";
		$sql = "SELECT user_id,out_uid FROM offer WHERE status=0;";
		$sql_command = Yii::app()->db->createCommand($sql);
		$offer_queue = $sql_command->queryAll();
		if(empty($offer_queue))
		{
			return NULL;
		}
		foreach($offer_queue as $offer)
		{
			$url = "http://api.t.sina.com.cn/statuses/user_timeline/$offer[out_uid].json?source=2437693526&feature=1";
			$json_data = file_get_contents($url);
			$feed_list = json_decode($json_data,TRUE);
			$data = array();
			foreach($feed_list as $feed) {
				$sql = "INSERT INTO `pin` (`content`,`cover_image`,`user_id`) VALUES('{$feed['text']}','{$feed['bmiddle_pic']}','{$offer['user_id']}');";
				$sql_command = Yii::app()->db->createCommand($sql);
				$sql_command->execute();
			}
		}
		/*
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , '2.00YzLkGDB4UgkB8e9281a986nacW6E');
		$home_timeline = $c->user_timeline_by_id(2848065700, 1, 50, 0, 0, 1);
		var_dump($home_timeline);
		$data = array();
		foreach($home_timeline['statuses'] as $feed) {
			$feed['text'] = $this->_process_content($feed['text']);
		    $feed['created_at'] = date('Y-m-d H:i:s',strtotime($feed['created_at']));
		    $data[] = $feed;
		}
		$this->_data['pin_list'] = $data;
		$this->render('timeline',$this->_data);
		 */


	}

}
