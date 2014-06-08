<?php

class QyerCommand extends CConsoleCommand
{
    private $page = 1;
    private $post = array(
            'client_id' => 'qyer_android',
            'client_secret' => '9fcaae8aefc4f9ac4915',
        );       

	public function actionTest()
	{
		echo 'test';
    }

    public function actionGetAllCountry()
    {
        $url = 'http://open.qyer.com/place/common/get_all_country';
        $r = http($url, $this->post, 'POST');
        if(empty($r)) {
            die('http error!');
        }

        $r = json_decode($r, true);
        if(empty($r) || empty($r['data'])) {
            die('no data error!');
        }

        $place_list = array();
        foreach($r['data'] as $k => $section) {
            if($k ==0) {
                continue;
            }
            foreach($section['hotcountrylist'] as $country) {
                $country['is_hot'] = 1;
                $country['continent'] = $section['catename'];
                $country['continent_en'] = $section['catename_en'];
                $place_list[$country['pid']] = $country;
            }
            foreach($section['countrylist'] as $country) {
                $country['continent'] = $section['catename'];
                $country['continent_en'] = $section['catename_en'];
                $place_list[$country['pid']] = $country;
            }
        }

        foreach($place_list as $place) {
            //先查询是否已经存在
            $place_db = Place::model()->findByPk($place['pid']);
            if(!empty($place_db)) {
                continue;
            }
            $new_place = new Place;
            $new_place->id = $place['pid'];
            $new_place->country_id = 0;
            $new_place->is_country = 1;
            $new_place->place_name = $place['catename'];
            if(!empty($place['catename_en'])) {
                $new_place->place_name_en = $place['catename_en'];
            }
            if(!empty($place['continent'])) {
                $new_place->continent = $place['continent'];
            }
            if(!empty($place['continent_en'])) {
                $new_place->continent_en = $place['continent_en'];
            }
            if(!empty($place['is_hot'])) {
                $new_place->is_hot = $place['is_hot'];
            }
            if(!empty($place['count'])) {
                $new_place->count = $place['count'];
            }
            if(!empty($place['flag'])) {
                $new_place->flag = $place['flag'];
            }

            if($new_place->save()) {
                echo "插入成功：{$place['catename']} \n";
            }
        }
    }

    public function actionUpdateCountryInfo()
    {
        $url = 'http://open.qyer.com/place/country/get_country_info';

		$criteria = new CDbCriteria;
		$criteria->addCondition("is_country=1");
		$count = Place::model()->count($criteria);
		$place_list = Place::model()->findAll($criteria);
        if(!empty($place_list)) {
            foreach($place_list as $place) {
                if($place->flag != 1) {
                    continue;
                }
                $this->post['countryid'] = $place->id;
                $r = http($url, $this->post, 'POST');
                if(empty($r)) {
                    echo "http error! $place->id\n";
                    continue;
                }
                $r = json_decode($r, true);
                if(empty($r) || empty($r['data'])) {
                    echo "json error! $place->id\n";
                    continue;
                }
                $r = $r['data'];
                if(!empty($r['catename_en'])) {
                    $place->place_name_en = $r['catename_en'];
                }
                if(!empty($r['photo'])) {
                    $place->photos = json_encode($r['photo']);
                }
                if(!empty($r['photocount'])) {
                    $place->photocount = $r['photocount'];
                }
                if(!empty($r['overview_url'])) {
                    $place->overview_url = $r['overview_url'];
                }
                if(!empty($r['isguide'])) {
                    $place->is_guide= intval($r['is_guide']);
                }

                if($place->save()) {
                    echo "更新成功： $place->id , $place->place_name \n";
                } else {
                    echo "更新失败： $place->id , $place->place_name \n";
                }
            }
        }

    }

    public function actionGetCityList()
    {
        $url = 'http://open.qyer.com/place/city/get_city_list';

		$criteria = new CDbCriteria;
        $criteria->addCondition("flag=1");
        //$criteria->addInCondition('id', array(11));
		$count = Place::model()->count($criteria);
		$place_list = Place::model()->findAll($criteria);
        if(empty($place_list)) {
            echo "No place to get city! \n";
            die();
        }
        foreach($place_list as $country) {
            if($country->flag != 1) {
                continue;
            }
            $page = 1;
            $this->post['countryid'] = $country->id;
            while($page > 0) {
                $this->post['page'] = $page;
                $r = http($url, $this->post, 'POST');
                if(empty($r)) {
                    echo "http error!\n";
                    continue;
                }

                $r = json_decode($r, true);
                if(empty($r) ) {
                    echo "json error!\n";
                    continue;
                }
                if(empty($r['data'])) {
                    $page = -1;
                    continue;
                }
                foreach($r['data'] as $place) {
                    //先查询是否已经存在
                    $place_db = Place::model()->findByPk($place['id']);
                    if(!empty($place_db)) {
                        echo "已经存在：$place_db->id ; $place_db->place_name\n";
                        continue;
                    }
                    $new_place = new Place;
                    $new_place->id = $place['id'];
                    $new_place->country_id = $country->id;
                    $new_place->is_country = 0;
                    $new_place->place_name = $place['catename'];
                    if(!empty($place['catename_en'])) {
                        $new_place->place_name_en = $place['catename_en'];
                    }
                    $new_place->cover_image = $place['photo'];
                    $new_place->flag = 2;
                    if($place['ishot']) {
                        $new_place->is_hot = 1;
                    }
                    if($place['isguide']) {
                        $new_place->is_guide = 1;
                    }
                    if(!empty($place['lat'])) {
                        $new_place->lat = $place['lat'];
                    }
                    if(!empty($place['lng'])) {
                        $new_place->lng = $place['lng'];
                    }
                    if(!empty($place['representative'])) {
                        $new_place->representative = $place['representative'];
                    }
                    if(!empty($place['beennumber'])) {
                        $new_place->beennumber= $place['beennumber'];
                    }

                    if($new_place->save()) {
                        echo "插入成功：{$place['catename']} \n";
                    }
                }
                $page++;
                if(count($r['data']) < 20) {
                    $page = -1;
                }
            }
        }

    }

    public function actionUpdateCityInfo()
    {
        echo "todo \n";
    }

    public function  actionGetPoiList()
    {
        $url = 'http://open.qyer.com/place/poi/get_poi_list';

		$criteria = new CDbCriteria;
        $criteria->addCondition("flag=2");
        $criteria->addCondition("poi_crawled=0");
        //$criteria->addInCondition('id', array(50));
		$count = Place::model()->count($criteria);
		$place_list = Place::model()->findAll($criteria);
        if(empty($place_list)) {
            echo "No place to get city! \n";
            die();
        }
        foreach($place_list as $place) {
            if($place->flag != 2) {
                continue;
            }
            $page = 1;
            $this->post['cityid'] = $place->id;
            while($page > 0) {
                $this->post['page'] = $page;
                $r = http($url, $this->post, 'POST');
                if(empty($r)) {
                    echo "http error!\n";
                    continue;
                }

                $r = json_decode($r, true);
                if(empty($r) ) {
                    echo "json error!\n";
                    continue;
                }
                if(empty($r['data'])) {
                    $page = -1;
                    continue;
                }
                foreach($r['data'] as $poi) {
                    //先查询是否已经存在
                    $poi_db = Poi::model()->findByPk($poi['id']);
                    if(!empty($poi_db)) {
                        echo "已经存在：$poi_db->id ; $poi_db->firstname \n";
                        continue;
                    }
                    $new_poi = new Poi;
                    $new_poi->id = $poi['id'];
                    $new_poi->place_id = $place->id;
                    $new_poi->firstname = $poi['firstname'];
                    $new_poi->secnodname = $poi['secnodname'];
                    $new_poi->chinesename = $poi['chinesename'];
                    $new_poi->englishname = $poi['englishname'];
                    $new_poi->localname = $poi['localname'];
                    $new_poi->lat = $poi['lat'];
                    $new_poi->lng = $poi['lng'];
                    $new_poi->grade = $poi['grade'];
                    $new_poi->gradescores = $poi['gradescores'];
                    $new_poi->beennumber = $poi['beennumber'];
                    $new_poi->recommendnumber = $poi['recommendnumber'];
                    $new_poi->recommendstr = $poi['recommendstr'];
                    $new_poi->cover_image = $poi['photo'];

                    if($new_poi->save()) {
                        echo "插入成功：{$poi['firstname']} \n";
                    } else {
                        echo "插入失败：{$poi['firstname']} \n";
                    }

                }

                $page++;
                if(count($r['data']) < 20) {
                    $page = -1;
                }
                usleep('2000');
            }

            usleep('3000');
            $place->poi_crawled = 1;
            $place->save();
        }
    }
    
    public function actionGetPoiDetail()
    {
        $url = 'http://open.qyer.com/poi/get_one';

		$criteria = new CDbCriteria;
        $criteria->addCondition("is_update=0");
        //$criteria->addInCondition('id', array(50));
        $count = Poi::model()->count($criteria);

        //分页
        $pageSize = 100;
        $totalPage = intval($count / $pageSize) + 1;

        for($i = 0; $i < $totalPage; $i++) {
            $limit = $pageSize;
            $offset = $i * $limit;

		    $criteria = new CDbCriteria;
            $criteria->addCondition("is_update=0");
            $criteria->offset = $offset;
            $criteria->limit = $limit;
            //$criteria->addInCondition('id', array(50));
            $poi_list = Poi::model()->findAll($criteria);
            if(empty($poi_list)) {
                continue;
            }
            foreach($poi_list as $poi) {
                echo $poi->id . "\n"; 
                $this->post['poi_id'] = $poi->id;
                $r = http($url, $this->post, 'POST');
                if(empty($r)) {
                    echo "http error!\n";
                    continue;
                }

                $r = json_decode($r, true);
                if(empty($r) ) {
                    echo "json error!\n";
                    continue;
                }
                if(empty($r['data'])) {
                    continue;
                }
                foreach($r['data'] as $poi) {
                }
                    //先查询是否已经存在


            }
            echo $offset . "\n"; 
        }

    }

}
