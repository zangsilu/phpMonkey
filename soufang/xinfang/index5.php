<?php
/**
 * User: 张世路
 * Date: 2017/8/22
 * Time: 17:29
 */

require_once '../config.php';
require_once dirname(dirname(__DIR__)).'/vendor/owner888/phpspider/library/cls_redis.php';

$provinceList = file_get_contents('../city.json');
$provinceList = json_decode($provinceList,true);

/*foreach ($provinceList as $provinceInfo){
    foreach ($provinceInfo as $cityInfo){
        spider($cityInfo);
    }
}*/

foreach ($provinceList['云南'] as $cityInfo){
    spider($cityInfo);
}


function spider($cityInfo){


//
        //所有区县一级
        $areaList = getShProvince($cityInfo);

        foreach ($areaList['distArea'] as $area){
            /**
             * 如果没有街道一级,那么直接获取县区一级
             */
            if(empty($area['area'])){

                    $data = [];
                    for ($i=1;$i<=10;$i++){

                        /**
                         * 没有街道一级,所以直接获取该县区下所有的新开楼盘
                         */
                        $url = "http://newhouse.fang.com/house/s/list/?mapmode=&strDistrict={$area['name']}&railway=&railway_station=&strPrice=&strRoom=&strPurpose=&housetag=&saling=&strStartDate=&strKeyword=&strComarea=&strSort=mobileyhnew&isyouhui=&x1=&y1=&x2=&y2=&newCode=&houseNum=&schoolDist=&schoolid=&PageNo={$i}&zoom=14&city={$cityInfo['code']}&a=ajaxSearch";
                        $house_list = longUrl($url);

                        $house_list = ajax_decode($house_list);

                        if(!empty($house_list)){

                            foreach ($house_list as $house_info){
                                if($house_info['title'] == '中惠金钻天地'){
                                    continue;
                                }

                                /**
                                 * 获取详情页数据
                                 */
                                $house_intro_html = longUrl($house_info['houseurl']);
                                $house_detail_url_arr = html5qp($house_intro_html,'#orginalNaviBox')->children();
                                 foreach ($house_detail_url_arr as $house_detail_url_obj){
                                    if($house_detail_url_obj->text() == '楼盘详情'){
                                        $house_detail_url = $house_detail_url_obj->attr('href');
                                    }
                                }
                                if(empty($house_detail_url)){
                                    $msg = date('Y-m-d H:i:s').' '.$cityInfo['province_name'].$cityInfo['city_name'].$area['name'].$house_info['title'].'详情页地址获取失败'.PHP_EOL;
                                    file_put_contents('./runtime.log',$msg,FILE_APPEND);
                                    continue;
                                }

                                $house_detail_html = longUrl($house_detail_url);
                                $attr_list = html5qp($house_detail_html,'body > div.main_1200>div.main-cont>div.main-left>div.main-item')->find('li,p')->toArray();

                                foreach ($attr_list as $k=>$v){
                                    $attr_info = explode('：',$v->textContent);
                                    $house_info[@preg_replace('/\s/','',$attr_info[0])] = @preg_replace('/\s/','',$attr_info[1]);
                                }


                                //均价
                                $on_the_average = html5qp($house_detail_html,'body > div.main_1200>div.main-cont>div.main-left>div')->find('span.tit')->next('em')->text();
                                $house_info['on_the_average'] = preg_replace('/\s/','',$on_the_average);
                                //新房
                                $house_info['house_type'] = 1;
                                unset($house_info['housetagarr']);

                                $data[] = $house_info;

                                $msg = date('Y-m-d H:i:s').' '.$cityInfo['province_name'].$cityInfo['city_name'].$area['name'].' '.$house_info['楼盘地址'].$house_info['title'].'ok'.PHP_EOL;
                                file_put_contents('./runtime.log',$msg,FILE_APPEND);
                                echo $msg;
                            }
                        }
                    }

                    $path = SOUFANG_DATA."xinfang/{$cityInfo['province_name']}/{$cityInfo['city_name']}/";
                    mkdirs(iconv('utf-8', 'gbk', $path));
                    $file_name = iconv('utf-8', 'gbk', $path."{$area['name']}.json");
                    file_put_contents($file_name,json_encode($data,JSON_UNESCAPED_UNICODE));

            }else{
                foreach ($area['area'] as $k=>$street){

                    $data = [];
                    for ($i=1;$i<=3;$i++){

                        /**
                         * 获取该乡镇街道下所有的新开楼盘
                         */
                        $url = "http://newhouse.fang.com/house/s/list/?mapmode=&strDistrict={$area['name']}&railway=&railway_station=&strPrice=&strRoom=&strPurpose=&housetag=&saling=&strStartDate=&strKeyword=&strComarea={$street['name']}&strSort=mobileyhnew&isyouhui=&x1=&y1=&x2=&y2=&newCode=&houseNum=&schoolDist=&schoolid=&PageNo={$i}&zoom=14&city={$cityInfo['code']}&a=ajaxSearch";
                        $house_list = longUrl($url);
                        $house_list = ajax_decode($house_list);

                        if(!empty($house_list)){

                            foreach ($house_list as $house_info){
                                if($house_info['title'] == '中惠金钻天地'){
                                    continue;
                                }
//              if($house_info['title'] != '绿地海岸城'){
//                    continue;
//                }

                                /**
                                 * 获取详情页数据
                                 */
                                $house_intro_html = longUrl($house_info['houseurl']);
                                $house_detail_url_arr = html5qp($house_intro_html,'#orginalNaviBox')->children();
                                foreach ($house_detail_url_arr as $house_detail_url_obj){
                                    if($house_detail_url_obj->text() == '楼盘详情'){
                                        $house_detail_url = $house_detail_url_obj->attr('href');
                                    }
                                }
                                if(empty($house_detail_url)){
                                    $msg = date('Y-m-d H:i:s').' '.$cityInfo['province_name'].$cityInfo['city_name'].$area['name'].$street['name'].$house_info['title'].'详情页地址获取失败'.PHP_EOL;
                                    file_put_contents('./runtime.log',$msg,FILE_APPEND);
                                    continue;
                                }
                                $house_detail_html = longUrl($house_detail_url);
                                $attr_list = html5qp($house_detail_html,'body > div.main_1200>div.main-cont>div.main-left>div.main-item')->find('li,p')->toArray();

                                foreach ($attr_list as $k=>$v){
                                    $attr_info = explode('：',$v->textContent);
                                    $house_info[@preg_replace('/\s/','',$attr_info[0])] = @preg_replace('/\s/','',$attr_info[1]);
                                }


                                //均价
                                $on_the_average = html5qp($house_detail_html,'body > div.main_1200>div.main-cont>div.main-left>div')->find('span.tit')->next('em')->text();
                                $house_info['on_the_average'] = preg_replace('/\s/','',$on_the_average);
                                //新房
                                $house_info['house_type'] = 1;
                                unset($house_info['housetagarr']);

                                $data[] = $house_info;

                                $msg = date('Y-m-d H:i:s').' '.$cityInfo['province_name'].$cityInfo['city_name'].$area['name'].$street['name'].' '.$house_info['楼盘地址'].$house_info['title'].'ok'.PHP_EOL;
                                file_put_contents('./runtime.log',$msg,FILE_APPEND);
                                echo $msg;
                            }
                        }
                    }

                    $path = SOUFANG_DATA."xinfang/{$cityInfo['province_name']}/{$cityInfo['city_name']}/{$area['name']}/";
                    mkdirs(iconv('utf-8', 'gbk', $path));
                    $file_name = iconv('utf-8', 'gbk', $path."{$street['name']}.json");
                    file_put_contents($file_name,json_encode($data,JSON_UNESCAPED_UNICODE));
                }
            }


        }
}






/**
 * 获取区县级数据
 * @param $city
 *
 * @return mixed|string
 */
function getShProvince($city)
{
    $getAreaUrl = "http://newhouse.fang.com/house/s/list/?a=getDistAreaTag&city={$city['code']}";
    $area = longUrl($getAreaUrl);
    $area = json_decode($area, true);
    if (empty($area)) {
        sleep(2);
        getShProvince($city);
    }
    return $area;
}

/**
 * 发送请求
 * @param      $url
 *
 * @return string
 */
function curl_get($url)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
    curl_setopt($curl, CURLOPT_USERAGENT,
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36');
    curl_setopt($curl, CURLOPT_REFERER, 'http://newhouse.sh.fang.com/house/s/list/');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);//处理301重定向问题
    curl_setopt($curl, CURLOPT_COOKIE,'global_cookie=khu6e56zxajk5lnm5kjwt26sr92j6ncpld9; newhouse_chat_guid=BC468A8E-3842-01C5-DBF4-A6B8AC83B942; Integrateactivity=notincludemc; new_search_uid=1fe390e367ccb8e3f498bbb65181bfd6; searchLabelN=3_1503547483_5891%5B%3A%7C%40%7C%3A%5Decaf0833e8eb1b29051f1a3906bb75b7; searchConN=3_1503547483_6362%5B%3A%7C%40%7C%3A%5Dbcdf13c0cb25bf4bf01ffcc636402a31; SoufunSessionID_Office=; SoufunSessionID_Esf=3_1503638209_12865; __utma=147393320.1211254579.1503391884.1504157795.1504163718.19; __utmc=147393320; __utmz=147393320.1504157795.18.8.utmcsr=bojingwanzj.fang.com|utmccn=(referral)|utmcmd=referral|utmcct=//; vh_newhouse=3_1504152757_138%5B%3A%7C%40%7C%3A%5De5e079f3e5e1dca8335d8bc57a37d85f; token=59c66a51681142018630f1745e1e739f; showAdsh=1; bdshare_firstime=1504168171598; polling_imei=a5bba5db3768e723; unique_cookie=U_khu6e56zxajk5lnm5kjwt26sr92j6ncpld9*100; sf_source=; s=; city=sh; indexAdvLunbo=; Captcha=2B6E3779642F6472757778646E423446562B727961574D36325671593543554158646C4471355A5344524563505846742F6E46686E4C363275314B4B457857336470474F736139673867453D; newhouse_user_guid=E11D0A41-1DDD-6D01-51E6-06C518B73887');
    $content = curl_exec($curl);
    curl_close($curl);
    return mb_convert_encoding($content, 'utf-8', 'GBK,UTF-8,ASCII');
}

/**
 * unicode字符解码
 * @param $name
 *
 * @return string
 */
function unicode_decode($name)
{

    $json = '{"str":"' . $name . '"}';
    $arr = json_decode($json, true);
    if (empty($arr)) {
        return '';
    }
    return $arr['str'];
}

/**
 * 解析搜房网ajax返回的字符串json
 * @param $str
 *
 * @return mixed
 */
function ajax_decode($str)
{
    $arr = explode('"list"',$str);
    $str = $arr[0];

    $str = str_replace(['\"'],'"',$str);
    $str = str_replace(['{"data":"'],'',$str);
    $str = rtrim($str,'",');
    $arr = json_decode($str,true);

    if(is_array($arr['hit'])){
        foreach ($arr['hit'] as $k=>$v){
            foreach ($v as $m=>$n){
                if(is_string($n)){
                    $arr['hit'][$k][$m] = unicode_decode($n);
                }
            }
        }
    }
    return $arr['hit'] ?? [];
}

/**
 * 循环爬取某个连接,直到返回值
 * @param $url
 *
 * @return string
 */
function longUrl($url)
{
    $res = curl_get($url);
    if(empty($res)){
        for($i=1;$i<=20;$i++){
            sleep(1);
            longUrl($url);
        }
    }
    return $res;
}

function mkdirs($dir, $mode = 0777)
{
    if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
    if (!mkdirs(dirname($dir), $mode)) return FALSE;
    return @mkdir($dir, $mode);
}

