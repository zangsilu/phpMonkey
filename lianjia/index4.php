<?php
/**
 * User: 张世路
 * Date: 2017/8/22
 * Time: 17:29
 */

use phpspider\core\db;
use phpspider\core\log;
use phpspider\core\requests;
use phpspider\core\selector;

/* Do NOT delete this comment */
/* 不要删除这段注释 */

require_once './config.php';

//数据库配置
$GLOBALS['config']['db'] = [
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'root',
    'pass' => 'LaiDian20160808',
    'name' => 'laidian2',
];

requests::set_header('Referer', 'https://bj.lianjia.com/xiaoqu/1111027377061/');
requests::set_header('User-Agent',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36');
requests::set_header('Cookie',
    'lianjia_uuid=34ae23c4-40e6-49e9-83e1-8ba7cc5a2576; gr_user_id=e07ee771-a057-4f91-80d6-df980edd54fa; UM_distinctid=15e26ec6c3123-03426e79f19c7d-8383667-144000-15e26ec6c3279; all-lj=406fadba61ceb7b8160b979dadec8dfa; __xsptplus696=696.6.1503914465.1503914637.11%234%7C%7C%7C%7C%7C%23%23n76hM8sPjI7Gj3BqHaZj_SdEAHLVtk33%23; ubt_load_interval_b=1503914638022; ubta=2299869246.1399233093.1503890623455.1503914636631.1503914638272.42; ubtc=2299869246.1399233093.1503914638274.F5C51227E67C68FCCA6F026546CD5FAC; ubtd=42; cityCode=sh; select_city=110000; _jzqx=1.1503914556.1504059270.2.jzqsr=sh%2Elianjia%2Ecom|jzqct=/.jzqsr=bj%2Elianjia%2Ecom|jzqct=/; _jzqckmp=1; Hm_lvt_9152f8221cb6243a53c83b956842be8a=1503891582; Hm_lpvt_9152f8221cb6243a53c83b956842be8a=1504059294; _smt_uid=59a3907d.1f7a2d8b; CNZZDATA1253477573=1539306532-1503888352-%7C1504056132; CNZZDATA1254525948=2011308993-1503886590-%7C1504053994; CNZZDATA1255633284=2077353754-1503886793-%7C1504054272; CNZZDATA1255604082=830545643-1503887891-%7C1504055300; _qzja=1.422730346.1503891899775.1503916526151.1504059269969.1504059269969.1504059294367.0.0.0.30.4; _qzjb=1.1504059269968.2.0.0.0; _qzjc=1; _qzjto=2.1.0; _jzqa=1.121720908562767600.1503891582.1503916526.1504059270.4; _jzqc=1; _jzqb=1.2.10.1504059270.1; _ga=GA1.2.437578624.1503890623; _gid=GA1.2.1891327460.1504059272; lianjia_ssid=180b4755-7b7a-44b0-ab8b-6be3ef3d2646');
requests::set_header('Accept',
    'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8');
requests::set_header('Accept-Encoding', 'gzip, deflate');
requests::set_header('Accept-Language', 'zh-CN,zh;q=0.8');
requests::set_header('Cache-Control', 'no-cache');
requests::set_header('Connection', 'keep-alive');
requests::set_header('Pragma', 'no-cache');
requests::set_header('Upgrade-Insecure-Requests', 1);

$proxies = [
    'http://HF6S1985JM4568MD:F7774E9533F2ED9A@http-dyn.abuyun.com:9020',
];
requests::set_proxies($proxies);

//$res = getRes("http://test.abuyun.com/proxy.php");
//var_dump($res);die;


$cityInfo = [
    "province_name" => "北京市",
    "name"          => "北京",
    "code"          => "bj",
];
lianjiaSpider($cityInfo);




function lianjiaSpider($cityInfo)
{
    /**
     * 获取总页数
     */
    $html = getRes("https://{$cityInfo['code']}.lianjia.com/xiaoqu/pg1/");

    $total = selector::select($html, '/html/body/div[4]/div[1]/div[2]/h2/span');
    $total_page = ceil($total / 30);

    /**
     * 循环爬取每页的详情
     */
    $page = @file_get_contents("./data/{$cityInfo['code']}_page.txt") ?: 1;

    for ($i = $page; $i <= $total_page; $i++) {
        echo "开始爬取第{$i}页";
        /**
         * 每个列表页间隔60秒
         */
        usleep(10000000);
        $html = getRes("https://{$cityInfo['code']}.lianjia.com/xiaoqu/pg$i/");
        $html_details = selector::select($html, '/html/body/div[4]/div[1]/ul/li/div[1]/div[1]/a//@href');

        /**
         * 获取每页的详情
         */
        if (!is_array($html_details)) {
            file_put_contents("./data/{$cityInfo['code']}_page.txt", $i);
            $msg = "{$cityInfo['name']}第{$i}页被封杀了.";
            error_log($msg);
            echo $msg;
            lianjiaSpider($cityInfo);
        }
        foreach ($html_details as $kk => $html_detail_url) {
            echo "开始爬取{$html_detail_url}" . PHP_EOL;
            /**
             * 每个详情页间隔0.5秒
             */
//        usleep(10000000);
            $detail = getRes($html_detail_url);

            /**
             * 名字
             */
            $name = html5qp($detail, 'body > div.xiaoquDetailHeader')->find('h1.detailTitle')->text();
            echo "获取内容{$name}" . PHP_EOL;

            /**
             * 经纬度
             */
            /* $coordinate = html5qp($detail,'body > div.xiaoquOverview > div.xiaoquDescribe.fr > div.xiaoquInfo > div:nth-child(8) > span.xiaoquInfoContent > span')->attr('xiaoqu');
             $coordinate = explode(',',trim($coordinate,'[]'));*/
            preg_match("/resblockPosition:'(.*?),(.*?)'/", $detail, $coordinate);

            /**
             * 城市
             */
            $city = html5qp($detail, 'body > div.xiaoquDetailbreadCrumbs > div.fl.l-txt > a:nth-child(3)')->text();
            $city = str_replace('小区', '', $city);

            /**
             * 区县
             */
            $area_name = html5qp($detail, 'body > div.xiaoquDetailbreadCrumbs > div.fl.l-txt > a:nth-child(5)')->text();
            $area_name = str_replace('小区', '', $area_name);
            /**
             * 街道
             */
            $street_name = html5qp($detail,
                'body > div.xiaoquDetailbreadCrumbs > div.fl.l-txt > a:nth-child(7)')->text();
            $street_name = str_replace('小区', '', $street_name);
            /**
             * 地址
             */
            $location = html5qp($detail, 'body > div.xiaoquDetailHeader')->find('div.detailDesc')->text();

            /**
             * 入住年代
             */
            $built_year = html5qp($detail,
                'body > div.xiaoquOverview > div.xiaoquDescribe.fr > div.xiaoquInfo > div:nth-child(1) > span.xiaoquInfoContent')->text();
            preg_match('/\d+/', $built_year, $built_year);
            /**
             * 房屋栋数
             */
            $building_total = html5qp($detail,
                'body > div.xiaoquOverview > div.xiaoquDescribe.fr > div.xiaoquInfo > div:nth-child(6) > span.xiaoquInfoContent')->text();
            preg_match('/\d+/', $building_total, $building_total);
            /**
             * 房屋总数
             */
            $houses_total = html5qp($detail,
                'body > div.xiaoquOverview > div.xiaoquDescribe.fr > div.xiaoquInfo > div:nth-child(7) > span.xiaoquInfoContent')->text();
            preg_match('/\d+/', $houses_total, $houses_total);

            //如果表里已经存在了,则跳过
            $coordinate_x = $coordinate[2] ?? -1;
            $coordinate_y = $coordinate[1] ?? -1;
            $res = db::get_one("SELECT COUNT(id) total FROM " . TABLE_NAME . " WHERE name = '{$name}' AND coordinate_x = '{$coordinate_x}' AND coordinate_y = '{$coordinate_y}'");
            if ($res['total'] > 0) {
                echo "小区{$name}已存在,跳出" . PHP_EOL;
                continue;
            }

            $data = [
                'name'                           => $name,
                //物业类型
                'type'                           => '小区',
                //挂牌均价
                'on_the_average'                 => html5qp($detail,
                    'body > div.xiaoquOverview > div.xiaoquDescribe.fr > div.xiaoquPrice.clear > div > span.xiaoquUnitPrice')->text(),
                'coordinate_x'                   => $coordinate_x,
                'coordinate_y'                   => $coordinate_y,
                'location'                       => $location,
                'province_name'                  => $cityInfo['province_name'],
                'province'                       => '',
                'city_name'                      => $city,
                //县区
                'area_name'                      => $area_name,
                //街道
                'street_name'                    => $street_name,
                'location_detail'                => $location,
                //建造年代或竣工时间
                'built_year'                     => $built_year[0] ?? '',
                //物业公司
                'property_company'               => html5qp($detail,
                    'body > div.xiaoquOverview > div.xiaoquDescribe.fr > div.xiaoquInfo > div:nth-child(4) > span.xiaoquInfoContent')->text(),
                //开发商
                'property_developer'             => html5qp($detail,
                    'body > div.xiaoquOverview > div.xiaoquDescribe.fr > div.xiaoquInfo > div:nth-child(5) > span.xiaoquInfoContent')->text(),
                //楼栋总数
                'building_total'                 => $building_total[0] ?? 0,
                //房屋总数(户数)
                'houses_total'                   => $houses_total[0] ?? 0,
                //物业费
                'property_management_fee_detail' => html5qp($detail,
                        'body > div.xiaoquOverview > div.xiaoquDescribe.fr > div.xiaoquInfo > div:nth-child(3) > span.xiaoquInfoContent')->text() ?? '',
                'created_at'                     => time(),
                'updated_at'                     => time(),
            ];

            $rows = db::insert('system_gathering_place2', $data);
            echo "数据{$name}保存完毕" . PHP_EOL;
            if (!empty($rows)) {
                $msg = $city . $area_name . $street_name . $location . $name . 'ok' . PHP_EOL;
                log::info($msg);
                echo $msg;
            } else {
                $msg = $city . $area_name . $street_name . $location . $name . 'error' . PHP_EOL;
                log::error($msg);
                echo $msg;
            }
        }
    }
    $msg = "{$cityInfo['name']}爬取完毕!". PHP_EOL;
    log::info($msg);
    echo ($msg);
}


/**
 * 获取匹配到到结果
 *
 * @param $url
 *
 * @return string
 */
function getRes($url)
{
    requests::get($url);
    return requests::$content;
}