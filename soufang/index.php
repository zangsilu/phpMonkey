<?php
/**
 * User: 张世路
 * Date: 2017/8/22
 * Time: 17:29
 */

use phpspider\core\db;

require_once './config.php';

//所有省直辖市一级
$pros = json_decode(file_get_contents('./city.json'),true);
shuffle($pros);
foreach ($pros as $kk => $citys){
    foreach ($citys as $city){
        $provinceInfo = getShProvince($city);
        shuffle($provinceInfo);
        spider($provinceInfo,$city);
    }
}

/**
 * 爬取数据
 * @param $province
 * @param $city
 */
function spider($province,$city)
{
    //区县
    foreach ($province as $k => $area) {

        $areaName = unicode_decode($area['name']);

        if (empty($area['area'])) {
            continue;
        }
        //乡镇街道
        foreach ($area['area'] as $z => $house) {

            $houseUrl = "http://esf.sh.fang.com/map/?mapmode=y&district={$area['id']}&x1=63&y1=10&x2=146&y2=60&subwayline=&subwaystation=&price=&room=&area=&towards=&floor=&hage=&equipment=&keyword=&comarea={$house['id']}&orderby=200&isyouhui=&newCode=&houseNum=&schoolDist=&schoolid=&ecshop=ecshophouse&PageNo=1&zoom=160&a=ajaxSearch&city={$city['code']}&searchtype=loupan";
            $houseData = json_decode(curl_get($houseUrl), true);

            if (empty($houseData['loupan']['hit'])) {
                continue;
            }

            //小区写字楼
            foreach ($houseData['loupan']['hit'] as $x => $houseDetail) {

                //如果表里已经存在了,则跳过
                $res = db::get_one("SELECT COUNT(id) total FROM " . TABLE_NAME . " WHERE name = '{$houseDetail['projname']}' AND coordinate_x = '{$houseDetail['y']}' AND coordinate_y = '{$houseDetail['x']}'");
                if ($res['total'] > 0) {
                    continue;
                }

                //获取小区的详细详细
                $houseInfo = curl_get($houseDetail['domain']);

                //物业类型
                preg_match("/<dd>物业类别：(.*?)<\/dd>/", $houseInfo, $type);

                //物业费用
                preg_match("/<dd>物 业 费：(.*?)<\/dd>/", $houseInfo, $property_management_fee_detail);

                //建筑面积
                preg_match("/<dd>建筑面积：([1-9]\d*)平方米<\/dd>/", $houseInfo, $building_area);

                //建造年代或竣工时间
                preg_match("/<li><strong>建筑年代<\/strong>(\d{4}-\d{2}-\d{2})<\/li>/", $houseInfo, $built_year);
                preg_match("/<dd>竣工时间：(.*?)<\/dd>/", $houseInfo, $built_year2);
                //物业公司
                preg_match("/<li class=\"whole\"><strong>物业公司<\/strong>(.*?)<\/li>/", $houseInfo, $property_company);
                preg_match("/<dt>物业公司：(.*?)<\/dt>/", $houseInfo, $property_company2);
                //开发商
                preg_match("/<li class=\"whole\"><strong>开发商<em><\/em><\/strong>(.*?)<\/li>/", $houseInfo,
                    $property_developer);
                //楼栋总数
                preg_match("/<li class=\"whole\"><strong>楼栋总数<\/strong>(.*?)<\/li>/", $houseInfo, $building_total);
                //房屋总数(户数)
                preg_match("/<li><strong>房屋总数<\/strong>(.*)<\/li>/", $houseInfo, $houses_total);

                $data = [
                    'name'                           => $houseDetail['projname'],
                    'type'                           => !empty($type[1]) ? (strpos($type[1],'楼') !== false ? '写字楼' : ((strpos($type[1],'商') !== false || strpos($type[1],'购') !== false) ? '商场' : $type[1])) : '小区',
                    //物业类型
                    'on_the_average'                 => $houseDetail['price'],
                    //挂牌均价
                    'coordinate_x'                   => $houseDetail['y'],
                    'coordinate_y'                   => $houseDetail['x'],
                    'location'                       => $houseDetail['address'],
                    'province_name'                  => $city['province_name'],
                    'province'                       => 310000,
                    'city_name'                      => $city['city_name'],
                    //县区
                    'area_name'                      => $areaName,
                    //街道
                    'street_name'                    => $house['name'] ?? '',
                    'location_detail'                => $city['province_name'].$city['city_name'] . $areaName . $houseDetail['address'],
                    'built_year'                     => $built_year[1] ?? $built_year2[1],
                    //建造年代或竣工时间
                    'property_company'               => !empty($property_company[1]) ? $property_company[1] : (!empty($property_company2[1]) ? $property_company2[1] : ''),
                    //物业公司
                    'property_developer'             => $property_developer[1] ?? '',
                    //开发商
                    'building_total'                 => $building_total[1] ?? 0,
                    //楼栋总数
                    'houses_total'                   => $houses_total[1] ?? 0,
                    //房屋总数(户数)
                    'property_management_fee_detail' => $property_management_fee_detail[1] ?? '',
                    //物业费
                    'building_area'                  => $building_area[1] ?? -1,
                    //建筑面积
                ];

                $rows = db::insert('system_gathering_place2', $data);
                if(!empty($rows)){
                    echo $city['city_name'] . $areaName . $house['name'] . $houseDetail['address'] . $houseDetail['projname'] . 'ok' . PHP_EOL;
                }else{
                    echo $city['city_name'] . $areaName . $house['name'] . $houseDetail['address'] . $houseDetail['projname'] . 'error' . PHP_EOL;
                }
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
    $shProvinceUrl = "http://esf.sh.fang.com/map/?a=getDistArea&city={$city['code']}";
    $shProvince = curl_get($shProvinceUrl);
    $shProvince = json_decode($shProvince, true);
    if (empty($shProvince)) {
        sleep(2);
        getShProvince($city);
    }
    return $shProvince;
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
    curl_setopt($curl, CURLOPT_REFERER, 'http://hebinchengrh.fang.com/');
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
