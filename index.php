<?php
/**
 * User: 张世路
 * Date: 2017/8/22
 * Time: 17:29
 */

use phpspider\core\db;
use phpspider\core\log;
use phpspider\core\phpspider;
use phpspider\core\requests;
use phpspider\core\selector;

require './vendor/autoload.php';
/* Do NOT delete this comment */
/* 不要删除这段注释 */

$GLOBALS['config']['db'] = [
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'root',
    'pass' => 'LaiDian20160808',
    'name' => 'laidian2',
];


$configs = [
    'name'                => '搜房网数据',
    'log_show'            => true,
    'log_file'            => './log_file.txt',
    'log_type'            => '',
    'output_encoding'     => 'utf8-mb4',
    'tasknum'             => 1,//进程数
    'export'              => [
        'type'  => 'csv',
        'file'  => __DIR__ . '/data/qiushibaike.sql',
        'table' => 'system_gathering_place2',
    ],
    'queue_config'        => [
        'host'    => '127.0.0.1',
        'port'    => 6379,
        'pass'    => '',
        'db'      => 5,
        'prefix'  => 'phpspider',
        'timeout' => 30,
    ],
    'domains'             => [
        'qiushibaike.com',
        'www.qiushibaike.com',
    ],
    'scan_urls'           => [
        'http://www.qiushibaike.com/',
    ],
    'content_url_regexes' => [
        "http://www.qiushibaike.com/article/\d+",
    ],
    'list_url_regexes'    => [
        "http://www.qiushibaike.com/8hr/page/\d+\?s=\d+",
    ],
    'fields'              => [
        [
            // 抽取内容页的文章内容
            'name'     => "name",
            'selector' => "//*[@id='single-next-link']",
            'required' => true,
        ],
        [
            // 抽取内容页的文章作者
            'name'     => "location",
            'selector' => "//div[contains(@class,'author')]//h2",
            'required' => true,
        ],
    ],
];

// 状态码
/*echo requests::$status_code;
print_r(requests::$encoding);
die;*/

//$html = requests::get("http://www.jianshu.com/p/fe8b23809dc4");
//$data = selector::select($html, "//div[contains(@class,'author')]//span");
//$data = selector::select($html, "//span[contains(@class,'tag')]");
//log::info("成功处理一个页面");
//var_dump($data);die;

//获取HTML内容
//$url = "http://hebinchengrh.fang.com/";
$url = "http://hebinchengrh.fang.com/";
$html = curl_get($url);
//echo ($html);die;
$html = '
<div class="xiangqing">
          <dl>
            <dd>物业类别：标准写字楼（甲级）</dd>
            <dd>总 层 数：17层</dd>
            <dd>物 业 费：22.00元/平米·月</dd>
            <dd>建筑面积：40356平方米</dd>
            <dd>得 房 率：62.00%</dd>
            <dd>竣工时间：2004-12-20</dd>
            <dd>电梯数量：客梯6部</dd>
            <dd>停 车 位：暂无资料</dd>
            <dt>物业公司：第一太平物业顾问（上海）分公司</dt>
          </dl>
        </div>
';
//提取文章标题
// 选择器规则
//$selector = '//*[@id="dsy_H01_07"]/div[2]/ul/li[1]/a';
// 提取结果
//$result = selector::select($html, $selector);
preg_match("/<li><strong>房屋总数<\/strong>(.*)<\/li>/", $html, $arr);
var_dump($arr[1]);
die;

var_dump($html);
die;

//提取文章作者
$selector = "//div[contains(@class,'article')]//div[contains(@class,'info')]/span[2]/a";
$result = selector::select($html, $selector);
print_r($result);
die;

$spider = new phpspider($configs);


$spider->on_extract_page = function ($page, $data) {

    $data = [
        'name'     => $data['name'],
        'location' => $data['location'],
    ];
    $rows = db::insert('system_gathering_place2', $data);
    var_dump($rows);
};


$spider->start();


function curl_get($url, $gzip = false)
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
