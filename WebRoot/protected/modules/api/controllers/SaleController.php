<?php

class SaleController extends Controller
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

		$count = Sale::model()->count($criteria);
		$data = Sale::model()->findAll($criteria);
		$sale_list = array();
		foreach($data as $sale)
        {
            $sale_format = $sale->attributes;
            if(empty($sale_format['discount'])) {
                $sale_format['discount'] = '';
            } else {
                $sale_format['discount'] = $sale_format['discount'] . "折";
            }
            if(empty($sale_format['price'])) {
                $sale_format['price'] = '';
            } else {
                $sale_format['price'] = $sale_format['price'] . "元";
            }
			$sale_list[] = $sale_format;
		}
		$this->_data['sale_list'] = $sale_list;

        ajax_response(200, '', $this->_data);
	}

}
