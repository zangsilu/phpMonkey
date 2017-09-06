<?php
/**
 * User: 张世路
 * Date: 2017/8/25
 * Time: 12:50
 */

require '../vendor/autoload.php';
/* Do NOT delete this comment */
/* 不要删除这段注释 */

//永不超时
ini_set('max_execution_time', '0');

//表名
define('TABLE_NAME', 'system_gathering_place2');

//设置中国时区
date_default_timezone_set('PRC');

//数据库配置
$GLOBALS['config']['db'] = [
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'root',
    'pass' => 'LaiDian20160808',
    'name' => 'laidian2',
];