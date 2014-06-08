<?php

class NoteController extends Controller
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
                'detail_html_url' => 'http://www.trip007.cn/pin/' . $pin->pin_id,
                'ctime' => '2014-05-22 12:11:00',
                'mtime' => '2014-05-22 12:11:00',
            );
			$pin_list[] = $note;
		}
		$this->_data['note_list'] = $pin_list;

        ajax_response(200, '', $this->_data);
	}

	private function _format_pin($pin)
	{
		$format_pin = array();
		$user = User::model()->findByPk($pin->user_id);

		$format_pin['pin_id'] = $pin->pin_id;
		$format_pin['title'] = $pin->title;
		$format_pin['desc'] = empty($pin->desc) ? mb_substr(strip_tags(htmlspecialchars_decode($pin->content)),0,200,'utf-8') : htmlspecialchars_decode($pin->desc);
		$format_pin['cover_image_b'] = upimage($pin->cover_image,'big');
		$format_pin['cover_image_m'] = upimage($pin->cover_image,'medium');
		$format_pin['cover_image_mw'] = upimage($pin->cover_image,'mw');
		$fixed_width = fixed_pin_height($pin->cover_image_width, $pin->cover_image_height);
		$format_pin['cover_image_width'] = $fixed_width['width'];
		$format_pin['cover_image_height'] = $fixed_width['height'];
		$format_pin['user'] = array('user_id'=> $user->user_id,'user_name'=>$user->user_name,'avatar'=>$user->avatar);
		$format_pin['ctime'] = date("Y-m-d H:i",$pin->ctime);
		return $format_pin;
    }

}
