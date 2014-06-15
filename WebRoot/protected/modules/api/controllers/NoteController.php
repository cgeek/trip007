<?php

class NoteController extends Controller
{
    private $_data;

	public function actionList()
    {
        $pageSize = Yii::app()->request->getParam('pageSize');
        $page = Yii::app()->request->getParam('p');

        $pageSize = (empty($pageSize) || $pageSize > 50) ? 20 : intval($pageSize);
        $page = empty($page) ? 1 : intval($page);
        $offset = ($page - 1) * $pageSize;

		$limit = $pageSize;
		$criteria = new CDbCriteria;
		$criteria->addCondition("status=0");
		$criteria->order = ' `ctime` DESC';
        $criteria->limit = $limit;
        $criteria->offset = $offset;

		$count = Note::model()->count($criteria);
		$data = Note::model()->findAll($criteria);
		$note_list = array();
		foreach($data as $note)
		{
            $note = array(
                'title' => $note->title,
                'cover_image' => upimage($note->cover_image, 'big'),
                'detail_html_url' => 'http://www.trip007.cn/note/' . $note->id. "?m=true",
                'ctime' => '2014-05-22 12:11:00',
                'mtime' => '2014-05-22 12:11:00',
            );
			$note_list[] = $note;
		}
		$this->_data['note_list'] = $note_list;

        ajax_response(200, '', $this->_data);
	}

}
