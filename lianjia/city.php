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

$configs = [
    'name'            => '链家网小区数据',
    'log_show'        => true,
    'log_file'        => './data/lianjia_log_file.txt',
    'output_encoding' => 'utf8-mb4',
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
    'http://218.89.77.190:1080',
    'http://66.172.100.204:31162',
//    'http://120.27.151.13:8889',
//    'http://user2:pass2@host:port',
];
//requests::set_proxies($proxies);
/**
 * 获取总页数
 */
$html = '
<div class="fc-main clear"><div class="fl citys-l"><ul><li class="clear"><span class="code-title fl">B</span><div class="city-enum fl"><a href="https://bj.lianjia.com/" title="北京房产网">北京</a></div></li><li class="clear"><span class="code-title fl">C</span><div class="city-enum fl"><a href="https://cd.lianjia.com/" title="成都房产网">成都</a><a href="https://cq.lianjia.com/" title="重庆房产网">重庆</a><a href="https://cs.lianjia.com/" title="长沙房产网">长沙</a></div></li><li class="clear"><span class="code-title fl">D</span><div class="city-enum fl"><a href="https://dl.lianjia.com/" title="大连房产网">大连</a><a href="https://dg.lianjia.com/" title="东莞房产网">东莞</a></div></li><li class="clear"><span class="code-title fl">F</span><div class="city-enum fl"><a href="https://fs.lianjia.com/" title="佛山房产网">佛山</a></div></li><li class="clear"><span class="code-title fl">G</span><div class="city-enum fl"><a href="https://gz.lianjia.com/" title="广州房产网">广州</a></div></li><li class="clear"><span class="code-title fl">H</span><div class="city-enum fl"><a href="https://hz.lianjia.com/" title="杭州房产网">杭州</a><a href="https://hui.lianjia.com/" title="惠州房产网">惠州</a><a href="http://hk.lianjia.com/" title="海口房产网">海口</a><a href="https://hf.lianjia.com/" title="合肥房产网">合肥</a></div></li><li class="clear"><span class="code-title fl">J</span><div class="city-enum fl"><a href="https://jn.lianjia.com/" title="济南房产网">济南</a></div></li><li class="clear"><span class="code-title fl">K</span><div class="city-enum fl"><a href="http://you.lianjia.com/km/" title="昆明房产网">昆明</a></div></li></ul></div><div class="fl citys-r"><ul><li class="clear"><span class="code-title fl">L</span><div class="city-enum fl"><a href="http://ls.lianjia.com/" title="陵水房产网">陵水</a><a href="https://lf.lianjia.com/" title="廊坊房产网">廊坊</a></div></li><li class="clear"><span class="code-title fl">N</span><div class="city-enum fl"><a href="https://nj.lianjia.com/" title="南京房产网">南京</a></div></li><li class="clear"><span class="code-title fl">Q</span><div class="city-enum fl"><a href="https://qd.lianjia.com/" title="青岛房产网">青岛</a><a href="http://qh.lianjia.com/" title="琼海房产网">琼海</a></div></li><li class="clear"><span class="code-title fl">S</span><div class="city-enum fl"><a href="http://sh.lianjia.com/" title="上海房产网">上海</a><a href="https://sz.lianjia.com/" title="深圳房产网">深圳</a><a href="http://su.lianjia.com/" title="苏州房产网">苏州</a><a href="http://sjz.lianjia.com/" title="石家庄房产网">石家庄</a><a href="https://sy.lianjia.com/" title="沈阳房产网">沈阳</a><a href="http://san.lianjia.com/" title="三亚房产网">三亚</a></div></li><li class="clear"><span class="code-title fl">T</span><div class="city-enum fl"><a href="https://tj.lianjia.com/" title="天津房产网">天津</a><a href="http://ty.lianjia.com/" title="太原房产网">太原</a></div></li><li class="clear"><span class="code-title fl">W</span><div class="city-enum fl"><a href="https://wh.lianjia.com/" title="武汉房产网">武汉</a><a href="http://wx.lianjia.com/" title="无锡房产网">无锡</a><a href="http://wc.lianjia.com/" title="文昌房产网">文昌</a><a href="http://wn.lianjia.com/" title="万宁房产网">万宁</a></div></li><li class="clear"><span class="code-title fl">X</span><div class="city-enum fl"><a href="https://xm.lianjia.com/" title="厦门房产网">厦门</a><a href="http://xa.lianjia.com/" title="西安房产网">西安</a><a href="http://you.lianjia.com/xsbn/" title="西双版纳房产网">西双版纳</a></div></li><li class="clear"><span class="code-title fl">Y</span><div class="city-enum fl"><a href="https://yt.lianjia.com/" title="烟台房产网">烟台</a></div></li><li class="clear"><span class="code-title fl">Z</span><div class="city-enum fl"><a href="https://zs.lianjia.com/" title="中山房产网">中山</a><a href="https://zh.lianjia.com/" title="珠海房产网">珠海</a></div></li></ul></div></div>
';
$citys = selector::select($html,'//div/div/ul/li/div/a');
$citys_href = selector::select($html,'//div/div/ul/li/div/a/@href');
$city_list = [];
foreach ($citys as $k => $city){
   $res = explode('://',explode('.',$citys_href[$k])[0]);
    $city_list[] = [
        'name' => $city,
        'href' => $citys_href[$k].'xiaoqu/pg1/',
        'code' => $res[1],
        'header' => $res[0]
    ];
}

file_put_contents('./data/city_back.json',json_encode($city_list,JSON_UNESCAPED_UNICODE));

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