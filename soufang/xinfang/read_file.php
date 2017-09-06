<?php
/**
 * User: 张世路
 * Date: 2017/8/22
 * Time: 17:29
 */

use phpspider\core\db;

require_once '../config.php';
require_once dirname(dirname(__DIR__)) . '/vendor/owner888/phpspider/library/cls_redis.php';

$provinceList = file_get_contents('../city.json');
$provinceList = json_decode($provinceList, true);

/*foreach ($provinceList as $provinceInfo){
    foreach ($provinceInfo as $cityInfo){
        spider($cityInfo);
    }
}*/

$path = SOUFANG_DATA . 'xinfang';

//var_dump($path);die;

/**
 * 获取省
 */
//$provinceList = getFile($path);
//$provinceList = json_encode($provinceList,JSON_UNESCAPED_UNICODE);
//file_put_contents(SOUFANG_DATA.'provinceList.json',$provinceList);

$provinceList = json_decode(file_get_contents(SOUFANG_DATA . 'provinceList.json'), true);

foreach ($provinceList as $proKey=>$proVal){
    $cityList = getFile($proVal);
    $provinceName = $proKey;
    saveJson($cityList,$provinceName);
}
die('全部城市入库完毕!');
/*$cityList = getFile($provinceList['广东']);
saveJson($cityList,'广东');*/



function saveJson($cityList,$provinceName){
//echo "<pre>";
//print_r($cityList);
//echo "</pre>";die;
    foreach ($cityList as $m => $n) {
        $m = iconv('gbk','utf-8',$m);

        /*if($m != '罗定市'){
            continue;
        }*/
        $areaList = getFile2($n);

        /**
         * 要入库的数据
         */
        $sqlData = [];
        foreach ($areaList as $k => $v) {
            $street = [];
            if (is_dir($v)) {
                $areaList = getFile3($v);

                $street[] = $areaList;
            } else {
                $street[] = [$v];
            }

            foreach ($street as $jsonFileList) {
                foreach ($jsonFileList as $jsonFile) {
                    $dataList = json_decode(file_get_contents($jsonFile), true);
                    foreach ($dataList as $data) {

                        //街道
                        $streetInfo = explode('.', substr($jsonFile, strripos($jsonFile, '\\') + 1))[0] ?? '';
                        //交房时间
                        @preg_match('/\d{4}/', $data['交房时间'], $built_year);
                        //楼栋总数
                        @preg_match('/\d+/', $data['楼栋总数'], $building_total);
                        //户数
                        @preg_match('/\d+/', $data['总户数'], $houses_total);
                        //物业费
                        @preg_match('/\d+\.?\d+/', $data['物业费'], $property_management_fee);
                        //地址
                        $location = @str_replace('[查看地图]', '', $data['楼盘地址']);
                        //均价(多少万/套,待定 的都不收录)
                        if (strpos($data['on_the_average'], '套') !== false){
//                        continue;
                            $data['on_the_average'] = '';
                        }
                        preg_match('/\d+/', $data['on_the_average'], $on_the_average);

                        $data['物业类别'] = empty($data['物业类别']) ? $data['purpose'] : $data['物业类别'];

                        $sqlData[] = [
                            'name'                           => $data['title'],
                            'type'                           => !empty($data['物业类别']) ? (strpos($data['物业类别'],
                                '楼') !== false || strpos($data['物业类别'],
                                '园') !== false || strpos($data['物业类别'],
                                '企') !== false ? '写字楼' : ((strpos($data['物业类别'],
                                    '商') !== false || strpos($data['物业类别'],
                                    '购') !== false) ? '商场' : (strpos($data['物业类别'],
                                '宅') !== false || strpos($data['物业类别'],
                                '寓') !== false || strpos($data['物业类别'],
                                '墅') !== false || strpos($data['物业类别'],
                                '房') !== false ? '小区' : $data['物业类别']))) : '小区',
                            //物业类型
                            'on_the_average'                 => $on_the_average[0] ?? 0,
                            //挂牌均价
                            'coordinate_x'                   => $data['y'] ?? '',
                            'coordinate_y'                   => $data['x'] ?? '',
                            'location'                       => $location,
                            'province'                       => '',
                            'province_name'                  => $provinceName,
                            'city'                           => '',
                            'city_name'                      => $m,
                            //县区
                            'area'                           => '',
                            'area_name'                      => iconv('gbk', 'utf-8', $k),
                            //街道
                            'street'                         => '',
                            'street_name'                    => iconv('gbk', 'utf-8', $streetInfo),
                            'location_detail'                => $location,
                            'population'                     => '',
                            'per_customer_transaction'       => '',
                            //建造年代或竣工时间
                            'built_year'                     => $built_year[0] ?? null,
                            'property_management_fee'        => $property_management_fee[0] ?? null,
                            //物业公司
                            'property_company'               => $data['物业公司'] ?? '',
                            //开发商
                            'property_developer'             => $data['开发商'] ?? '',
                            //楼栋总数
                            'building_total'                 => $building_total[0] ?? 0,
                            //房屋总数(户数)
                            'houses_total'                   => $houses_total[0] ?? 0,
                            //物业费详情
                            'property_management_fee_detail' => $data['物业费'] ?? '',
                            //建筑面积
                            'building_area'                  => $data['建筑面积'] ?? -1,
                            'remark'                         => key(array_slice($data, '-3', 1)) ?? '',
                            'created_at'                     => time(),
                            'updated_at'                     => time(),
                            'contain'                        => $data['建筑类别'] ?? '',
                            'house_type'                     => 2,
                        ];
                    }
                }
            }
        }

        $result = db::insert_batch('system_gathering_place2',$sqlData);
        if($result){
            file_put_contents('./saveLog.log',$provinceName.' '.$m.'入库完毕!,共'.count($sqlData).'条.'.PHP_EOL,FILE_APPEND);
        }else{
            $msg = $provinceName.' '.$m.'入库失败!'.PHP_EOL;
            file_put_contents('./saveLog.log',$msg,FILE_APPEND);
            echo $msg;
        }
    }
}






//$data = file_get_contents();


function traverse($path = '.')
{
    $path = iconv("utf-8", "gbk", $path);
    $current_dir = opendir($path);    //opendir()返回一个目录句柄,失败返回false
    while (($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目

        $sub_dir = $path . DIRECTORY_SEPARATOR . $file;    //构建子目录路径
        if ($file == '.' || $file == '..') {
            continue;
        } else {
            if (is_dir($sub_dir)) {    //如果是目录,进行递归
                $file = iconv("gbk", "utf-8", $file);
                echo 'Directory ' . $file . ':<br>';
                traverse($sub_dir);
            } else {    //如果是文件,直接输出
                $file = iconv("gbk", "utf-8", $file);
                echo 'File in Directory ' . $path . ': ' . $file . '<br>';
            }
        }
    }
}


function getFile($path)
{
    $path = iconv("utf-8", "gb2312", $path);

    $current_dir = opendir($path);
    $sub_dir = [];
    while (($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目
        if ($file == '.' || $file == '..') {
            continue;
        }
//            $path =  iconv("gbk","utf-8",$path . DIRECTORY_SEPARATOR . $file);
        $sub_dir[$file] = $path . DIRECTORY_SEPARATOR . $file;    //构建子目录路径
    }
    return $sub_dir;
}

function getFile2($path)
{
//        $path =  iconv("utf-8","gb2312",$path);
    $current_dir = opendir($path);
    $sub_dir = [];
    while (($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目
        if ($file == '.' || $file == '..') {
            continue;
        }
//            $file =  iconv("gbk","utf-8",$file);

        if(strpos($file,'.')){
            $key = explode('.',$file)[0];
        }else{
            $key = $file;
        }
        $sub_dir[$key] = $path . DIRECTORY_SEPARATOR . $file;    //构建子目录路径
    }
    return $sub_dir;
}

function getFile3($path)
{
//        $path =  iconv("utf-8","gb2312",$path);
    $current_dir = opendir($path);
    $sub_dir = [];
    while (($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目
        if ($file == '.' || $file == '..') {
            continue;
        }
//            $file =  iconv("gbk","utf-8",$file);
        $sub_dir[] = $path . DIRECTORY_SEPARATOR . $file;    //构建子目录路径
    }
    return $sub_dir;
}