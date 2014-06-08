<?php

class PlaceController extends Controller
{
    private $_data;

	public function actionDiscoveryList()
    {
        $section = array(
            'id' => '1',
            'title' => '一生必去的全球目的地',
            'desc' => '叹为观止的40处世界风光',
            'places' => array(
                array(
                    'place_id' => 1,
                    'place_name' => '仙本那',
                    'country_name' => '马来西亚',
                    'cover_image' => 'http://pic.qyer.com/album/1a4/2d/2086499/index/300_200'),
                array(
                    'place_id' => 1,
                    'place_name' => '仙本那',
                    'country_name' => '马来西亚',
                    'cover_image' => 'http://pic.qyer.com/album/1a4/2d/2086499/index/300_200'),
                array(
                    'place_id' => 1,
                    'place_name' => '仙本那',
                    'country_name' => '马来西亚',
                    'cover_image' => 'http://pic.qyer.com/album/1a4/2d/2086499/index/300_200'),

                array(
                    'place_id' => 1,
                    'place_name' => '仙本那',
                    'country_name' => '马来西亚',
                    'cover_image' => 'http://pic.qyer.com/album/1a4/2d/2086499/index/300_200'),

            )
        );
        
        $this->_data['section_list'] = array($section, $section);
        ajax_response(200, '', $this->_data);
	}

}
