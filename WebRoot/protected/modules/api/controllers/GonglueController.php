<?php

class GonglueController extends Controller
{
    private $_data;

	public function actionList()
    {
		$limit = 10;
		$criteria = new CDbCriteria;
		$criteria->addCondition("status=0");
		$criteria->addCondition("type=2");
		$criteria->order = ' `ctime` DESC';
		$criteria->limit = $limit;
		$count = Pin::model()->count($criteria);
		$data = Pin::model()->findAll($criteria);
		$pin_list = array();
		foreach($data as $pin)
		{
            $note = array('title' => $pin->title,
                'cover_image' => upimage($pin->cover_image, 'big'),
                'detail_html_url' => 'http://www.trip007.cn/pin/' . $pin->pin_id . "?m=true",
                'ctime' => '2014-05-22 12:11:00',
                'mtime' => '2014-05-22 12:11:00',
            );
			$pin_list[] = $note;
		}
		$this->_data['note_list'] = $pin_list;

        ajax_response(200, '', $this->_data);
	}
}
