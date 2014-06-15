<?php

class SaleController extends Controller
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
        $sale_id =  $_GET['id'];
		if(empty($sale_id) || $sale_id <= 0)
			throw new CHttpException(404,'Not found');

		$sale = Sale::model()->findByPk($sale_id);
		
		//update view_count
		Sale::model()->updateByPk($sale_id, array('view_count'=> $sale['view_count'] +1));

		$this->_data['sale'] = $sale;
        $this->pageTitle = $sale['title'] . " - 旅游特价信息网";

        if(isset($_GET['m']) && $_GET['m'] = 'true') {
		    $this->renderPartial('m_detail',$this->_data);
        } else {
		    $this->render('detail',$this->_data);
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
