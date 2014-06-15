<?php

class PlaceController extends Controller
{
    private $_data;

	public function actionDiscoveryList()
    {
        $sections = array(
            array(
                'id'=>1,
                'title' => '屌丝也能出国旅行',
                'desc' => '谁说没钱就不能出国',
                'place_ids' => array(54,56,52,7440)
            ),
            array(
                'id'=>2,
                'title' => '购物旅行两不误',
                'desc' => '疯狂的血拼吧',
                'place_ids' => array(50,67,53,55)
            ),
            array(
                'id'=>3,
                'title' => '最美海岛推荐',
                'desc' => '夕阳，沙滩，海龟',
                'place_ids' => array(7837,9826,8845,6890)
            ),
        
        );

        foreach($sections as $key=>$section) {
            $criteria = new CDbCriteria;
            $criteria->addInCondition('id',$section['place_ids']);
		    $data = Place::model()->findAll($criteria);
		    $place_list = array();
		    foreach($data as $place)
            {
                $place = array(
                    'place_id' => $place->id,
                    'place_name' => $place->place_name,
                    'place_name_en' => $place->place_name_en,
                    'cover_image' => $place->cover_image
                );
                $place_list[] = $place;
            }
            $section['places'] = $place_list;
            $sections[$key] = $section;
        }
        $this->_data['section_list'] = $sections;
        ajax_response(200, '', $this->_data);
	}

}
