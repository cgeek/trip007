<?php

class NoteController extends Controller
{
    private $_data;

	public function actionList()
	{
        //$this->render('list');

        $note = array('title' => 'RABI全制霸九天八夜在红旗下宣誓要睡午觉的自由行',
            'cover_image' => 'http://file26.mafengwo.net/M00/94/63/wKgB4lMQoaGAcK8UACgO7DpfklA76.groupinfo.w600.jpeg',
            'detail_html_url' => 'http://www.mafengwo.cn/i/3055428.html',
            'ctime' => '2014-05-22 12:11:00',
            'mtime' => '2014-05-22 12:11:00',
        );
        $note1 = array('title' => 'RABI全制霸九天八夜在红旗下宣誓要睡午觉的自由行',
            'cover_image' => 'http://file26.mafengwo.net/M00/15/4B/wKgB4lNzP3uAZEgZAAJ8ckTTV3490.jpeg',
            'detail_html_url' => 'http://www.mafengwo.cn/i/3055428.html',
            'ctime' => '2014-05-22 12:11:00',
            'mtime' => '2014-05-22 12:11:00',
        );
        $this->_data['note_list'] = array($note, $note1);
        ajax_response(200, '', $this->_data);
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
