<?php

class NoteController extends Controller
{
	private $_data;
	public function actionAdd()
    {

		$this->render('edit');
    }

	public function actionEdit()
	{
		$this->render('edit');
	}

    public function actionDetail()
    {
        $note_id =  $_GET['id'];
		if(empty($note_id) || $note_id <= 0)
			throw new CHttpException(404,'Not found');

		$note = Note::model()->findByPk($note_id);
		
		//update view_count
		Note::model()->updateByPk($note_id, array('view_count'=> $note['view_count'] +1));

		$this->_data['note'] = $note;
        $this->pageTitle = $note['title'] . " - 旅游特价信息网";

        if(isset($_GET['m']) && $_GET['m'] = 'true') {
		    $this->renderPartial('m_detail',$this->_data);
        } else {
		    $this->render('detail',$this->_data);
        }

    }
}
