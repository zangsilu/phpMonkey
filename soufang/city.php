<?php
/**
 * User: 张世路
 * Date: 2017/8/22
 * Time: 17:29
 */

use phpspider\core\db;
use phpspider\core\requests;
use phpspider\core\selector;

require '../vendor/autoload.php';
/* Do NOT delete this comment */
/* 不要删除这段注释 */

//永不超时
ini_set('max_execution_time', '0');

//插入的数据表
define('TABLE_NAME', 'system_gathering_place2');

//要爬取的城市
$city = [
    '上海'=>'sh',
    '北京'=>'bj',
];

$GLOBALS['config']['db'] = [
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'root',
    'pass' => 'LaiDian20160808',
    'name' => 'laidian2',
];

$html = '
<div class="outCont" id="c02">
                <ul>
                    <li class="blubk02"><strong>直辖市</strong><a class="red" href="http://esf1.fang.com/" target="_blank">北京</a>
                        <a href="http://esf.sh.fang.com/" class="red" target="_blank">上海</a>
                        <a href="http://esf.tj.fang.com/" target="_blank" class="red">天津</a>
                        <a class="red" href="http://esf.cq.fang.com/" target="_blank">重庆</a></li>
                      
                   <li class="blubk"><strong>河北</strong>
               
                     <a class="red" href="http://esf.xingtai.fang.com" target="_blank">邢台</a>
                
                     <a class="red" href="http://esf.zhangjiakou.fang.com" target="_blank">张家口</a>
                
                     <a class="red" href="http://esf.chengde.fang.com" target="_blank">承德</a>
                
                     <a class="red" href="http://esf.cangzhou.fang.com" target="_blank">沧州</a>
                
                     <a class="red" href="http://esf.zhangbei.fang.com" target="_blank">张北</a>
                
                     <a class="red" href="http://esf.zunhua.fang.com" target="_blank">遵化</a>
                
                     <a class="red" href="http://esf.qianan.fang.com" target="_blank">迁安</a>
                
                     <a class="red" href="http://esf.hd.fang.com" target="_blank">邯郸</a>
                
                     <a class="red" href="http://esf.bd.fang.com" target="_blank">保定</a>
                
                     <a class="red" href="http://esf.hs.fang.com" target="_blank">衡水</a>
                
                     <a class="red" href="http://esf.lf.fang.com" target="_blank">廊坊</a>
                
                     <a class="red" href="http://esf.qhd.fang.com" target="_blank">秦皇岛</a>
                
                     <a class="red" href="http://esf.sjz.fang.com" target="_blank">石家庄</a>
                
                     <a class="red" href="http://esf.ts.fang.com" target="_blank">唐山</a>
                
                     <a class="red" href="http://esf.dingzhou.fang.com" target="_blank">定州</a>
                
                     <a class="red" href="http://esf.xinji.fang.com" target="_blank">辛集</a>
                
                     <a class="red" href="http://esf.luanxian.fang.com" target="_blank">滦县</a>
                
                     <a class="red" href="http://esf.yutian.fang.com" target="_blank">玉田</a>
                
                     <a class="red" href="http://esf.hbjz.fang.com" target="_blank">晋州</a>
                
                     <a class="red" href="http://esf.luannan.fang.com" target="_blank">滦南</a>
                
                     <a class="red" href="http://esf.gaobeidian.fang.com" target="_blank">高碑店</a>
                
                     <a class="red" href="http://esf.hblt.fang.com" target="_blank">乐亭</a>
                
                     <a class="red" href="http://esf.qianxi.fang.com" target="_blank">迁西</a>
                
                     <a class="red" href="http://esf.hbql.fang.com" target="_blank">青龙</a>
                
                     <a class="red" href="http://esf.hbwj.fang.com" target="_blank">无极</a>
                
                     <a class="red" href="http://esf.hbzx.fang.com" target="_blank">赵县</a>
                
                     <a class="red" href="http://esf.hbys.fang.com" target="_blank">元氏</a>
                
                     <a class="red" href="http://esf.wenan.fang.com" target="_blank">文安</a>
                
                     <a class="red" href="http://esf.xinle.fang.com" target="_blank">新乐</a>
                
                     <a class="red" href="http://esf.hbbz.fang.com" target="_blank">霸州</a>
                
                     <a class="red" href="http://esf.changli.fang.com" target="_blank">昌黎</a>
                
                     <a class="red" href="http://esf.hbps.fang.com" target="_blank">平山</a>
                
                     <a class="red" href="http://esf.wuan.fang.com" target="_blank">武安</a>
                
                     <a class="red" href="http://esf.renqiu.fang.com" target="_blank">任丘</a>
                
                     <a class="red" href="http://esf.jz.fang.com" target="_blank">冀州</a>
                
                     <a class="red" href="http://esf.hbsh.fang.com" target="_blank">三河</a>
                
                     <a class="red" href="http://esf.hbsz.fang.com" target="_blank">深州</a>
                
            </li><li class="blubk02"><strong>山西</strong>
          
                     <a class="red" href="http://esf.datong.fang.com" target="_blank">大同</a>
                
                     <a class="red" href="http://esf.yangquan.fang.com" target="_blank">阳泉</a>
                
                     <a class="red" href="http://esf.changzhi.fang.com" target="_blank">长治</a>
                
                     <a class="red" href="http://esf.shuozhou.fang.com" target="_blank">朔州</a>
                
                     <a class="red" href="http://esf.jinzhong.fang.com" target="_blank">晋中</a>
                
                     <a class="red" href="http://esf.xinzhou.fang.com" target="_blank">忻州</a>
                
                     <a class="red" href="http://esf.linfen.fang.com" target="_blank">临汾</a>
                
                     <a class="red" href="http://esf.lvliang.fang.com" target="_blank">吕梁</a>
                
                     <a class="red" href="http://esf.yuncheng.fang.com" target="_blank">运城</a>
                
                     <a class="red" href="http://esf.jc.fang.com" target="_blank">晋城</a>
                
                     <a class="red" href="http://esf.sxly.fang.com" target="_blank">临猗</a>
                
                     <a class="red" href="http://esf.taiyuan.fang.com" target="_blank">太原</a>
                
                     <a class="red" href="http://esf.qingxu.fang.com" target="_blank">清徐</a>
                
                     <a class="red" href="http://esf.yangqu.fang.com" target="_blank">阳曲</a>
                
                     <a class="red" href="http://esf.huairen.fang.com" target="_blank">怀仁</a>
                
                   </li><li class="blubk"><strong>内蒙古</strong>
               
                     <a class="red" href="http://esf.chifeng.fang.com" target="_blank">赤峰</a>
                
                     <a class="red" href="http://esf.wlcb.fang.com" target="_blank">乌兰察布</a>
                
                     <a class="red" href="http://esf.tl.fang.com" target="_blank">通辽</a>
                
                     <a class="red" href="http://esf.alsm.fang.com" target="_blank">阿拉善盟</a>
                
                     <a class="red" href="http://esf.erds.fang.com" target="_blank">鄂尔多斯</a>
                
                     <a class="red" href="http://esf.bt.fang.com" target="_blank">包头</a>
                
                     <a class="red" href="http://esf.wuhai.fang.com" target="_blank">乌海</a>
                
                     <a class="red" href="http://esf.xlglm.fang.com" target="_blank">锡林郭勒盟</a>
                
                     <a class="red" href="http://esf.xam.fang.com" target="_blank">兴安盟</a>
                
                     <a class="red" href="http://esf.byne.fang.com" target="_blank">巴彦淖尔</a>
                
                     <a class="red" href="http://esf.hailaer.fang.com" target="_blank">海拉尔</a>
                
                     <a class="red" href="http://esf.hlbe.fang.com" target="_blank">呼伦贝尔</a>
                
                     <a class="red" href="http://esf.nm.fang.com" target="_blank">呼和浩特</a>
                
                     <a class="red" href="http://esf.xilinhaote.fang.com" target="_blank">锡林浩特</a>
                
            </li><li class="blubk02"><strong>辽宁</strong>
          
                     <a class="red" href="http://esf.fushun.fang.com" target="_blank">抚顺</a>
                
                     <a class="red" href="http://esf.benxi.fang.com" target="_blank">本溪</a>
                
                     <a class="red" href="http://esf.dandong.fang.com" target="_blank">丹东</a>
                
                     <a class="red" href="http://esf.jinzhou.fang.com" target="_blank">锦州</a>
                
                     <a class="red" href="http://esf.fuxin.fang.com" target="_blank">阜新</a>
                
                     <a class="red" href="http://esf.liaoyang.fang.com" target="_blank">辽阳</a>
                
                     <a class="red" href="http://esf.panjin.fang.com" target="_blank">盘锦</a>
                
                     <a class="red" href="http://esf.tieling.fang.com" target="_blank">铁岭</a>
                
                     <a class="red" href="http://esf.chaoyang.fang.com" target="_blank">朝阳</a>
                
                     <a class="red" href="http://esf.huludao.fang.com" target="_blank">葫芦岛</a>
                
                     <a class="red" href="http://esf.yk.fang.com" target="_blank">营口</a>
                
                     <a class="red" href="http://esf.lnzh.fang.com" target="_blank">庄河</a>
                
                     <a class="red" href="http://esf.pulandian.fang.com" target="_blank">普兰店</a>
                
                     <a class="red" href="http://esf.anshan.fang.com" target="_blank">鞍山</a>
                
                     <a class="red" href="http://esf.dl.fang.com" target="_blank">大连</a>
                
                     <a class="red" href="http://esf.sy.fang.com" target="_blank">沈阳</a>
                
                     <a class="red" href="http://esf.wafangdian.fang.com" target="_blank">瓦房店</a>
                
                     <a class="red" href="http://esf.donggang.fang.com" target="_blank">东港</a>
                
                     <a class="red" href="http://esf.fengcheng.fang.com" target="_blank">凤城</a>
                
                     <a class="red" href="http://esf.xinmin.fang.com" target="_blank">新民</a>
                
                     <a class="red" href="http://esf.liaozhong.fang.com" target="_blank">辽中</a>
                
                     <a class="red" href="http://esf.faku.fang.com" target="_blank">法库</a>
                
                     <a class="red" href="http://esf.kangping.fang.com" target="_blank">康平</a>
                
                     <a class="red" href="http://esf.haicheng.fang.com" target="_blank">海城</a>
                
                     <a class="red" href="http://esf.lnta.fang.com" target="_blank">台安</a>
                
                   </li><li class="blubk"><strong>吉林</strong>
               
                     <a class="red" href="http://esf.siping.fang.com" target="_blank">四平</a>
                
                     <a class="red" href="http://esf.liaoyuan.fang.com" target="_blank">辽源</a>
                
                     <a class="red" href="http://esf.tonghua.fang.com" target="_blank">通化</a>
                
                     <a class="red" href="http://esf.baishan.fang.com" target="_blank">白山</a>
                
                     <a class="red" href="http://esf.songyuan.fang.com" target="_blank">松原</a>
                
                     <a class="red" href="http://esf.baicheng.fang.com" target="_blank">白城</a>
                
                     <a class="red" href="http://esf.yanbian.fang.com" target="_blank">延边</a>
                
                     <a class="red" href="http://esf.changchun.fang.com" target="_blank">长春</a>
                
                     <a class="red" href="http://esf.jl.fang.com" target="_blank">吉林</a>
                
                     <a class="red" href="http://esf.nongan.fang.com" target="_blank">农安</a>
                
                     <a class="red" href="http://esf.dehui.fang.com" target="_blank">德惠</a>
                
                     <a class="red" href="http://esf.jlys.fang.com" target="_blank">榆树</a>
                
                     <a class="red" href="http://esf.gongzhuling.fang.com" target="_blank">公主岭</a>
                
                     <a class="red" href="http://esf.huadian.fang.com" target="_blank">桦甸</a>
                
            </li><li class="blubk02"><strong>黑龙江</strong>
          
                     <a class="red" href="http://esf.qiqihaer.fang.com" target="_blank">齐齐哈尔</a>
                
                     <a class="red" href="http://esf.jixi.fang.com" target="_blank">鸡西</a>
                
                     <a class="red" href="http://esf.hegang.fang.com" target="_blank">鹤岗</a>
                
                     <a class="red" href="http://esf.shuangyashan.fang.com" target="_blank">双鸭山</a>
                
                     <a class="red" href="http://esf.hljyichun.fang.com" target="_blank">伊春</a>
                
                     <a class="red" href="http://esf.jiamusi.fang.com" target="_blank">佳木斯</a>
                
                     <a class="red" href="http://esf.qitaihe.fang.com" target="_blank">七台河</a>
                
                     <a class="red" href="http://esf.mudanjiang.fang.com" target="_blank">牡丹江</a>
                
                     <a class="red" href="http://esf.heihe.fang.com" target="_blank">黑河</a>
                
                     <a class="red" href="http://esf.suihua.fang.com" target="_blank">绥化</a>
                
                     <a class="red" href="http://esf.dxal.fang.com" target="_blank">大兴安岭</a>
                
                     <a class="red" href="http://esf.daqing.fang.com" target="_blank">大庆</a>
                
                     <a class="red" href="http://esf.hrb.fang.com" target="_blank">哈尔滨</a>
                
                     <a class="red" href="http://esf.zhaodong.fang.com" target="_blank">肇东</a>
                
                     <a class="red" href="http://esf.binxian.fang.com" target="_blank">宾县</a>
                
                     <a class="red" href="http://esf.anda.fang.com" target="_blank">安达</a>
                
                     <a class="red" href="http://esf.bayan.fang.com" target="_blank">巴彦</a>
                
                     <a class="red" href="http://esf.shangzhi.fang.com" target="_blank">尚志</a>
                
                     <a class="red" href="http://esf.wuchang.fang.com" target="_blank">五常</a>
                
                     <a class="red" href="http://esf.yilan.fang.com" target="_blank">依兰</a>
                
                     <a class="red" href="http://esf.zhaoyuan.fang.com" target="_blank">肇源</a>
                
                     <a class="red" href="http://esf.zhaozhou.fang.com" target="_blank">肇州</a>
                
                     <a class="red" href="http://esf.hailin.fang.com" target="_blank">海林</a>
                
                   </li><li class="blubk"><strong>江苏</strong>
               
                     <a class="red" href="http://esf.yixing.fang.com" target="_blank">宜兴</a>
                
                     <a class="red" href="http://esf.shuyang.fang.com" target="_blank">沭阳</a>
                
                     <a class="red" href="http://esf.zjg.fang.com" target="_blank">张家港</a>
                
                     <a class="red" href="http://esf.sq.fang.com" target="_blank">宿迁</a>
                
                     <a class="red" href="http://esf.tc.fang.com" target="_blank">太仓</a>
                
                     <a class="red" href="http://esf.pizhou.fang.com" target="_blank">邳州</a>
                
                     <a class="red" href="http://esf.xinghua.fang.com" target="_blank">兴化</a>
                
                     <a class="red" href="http://esf.rugao.fang.com" target="_blank">如皋</a>
                
                     <a class="red" href="http://esf.liyang.fang.com" target="_blank">溧阳</a>
                
                     <a class="red" href="http://esf.taixing.fang.com" target="_blank">泰兴</a>
                
                     <a class="red" href="http://esf.dongtai.fang.com" target="_blank">东台</a>
                
                     <a class="red" href="http://esf.qidong.fang.com" target="_blank">启东</a>
                
                     <a class="red" href="http://esf.jiangdu.fang.com" target="_blank">江都</a>
                
                     <a class="red" href="http://esf.haimen.fang.com" target="_blank">海门</a>
                
                     <a class="red" href="http://esf.xinyi.fang.com" target="_blank">新沂</a>
                
                     <a class="red" href="http://esf.gaoyou.fang.com" target="_blank">高邮</a>
                
                     <a class="red" href="http://esf.jingjiang.fang.com" target="_blank">靖江</a>
                
                     <a class="red" href="http://esf.changshu.fang.com" target="_blank">常熟</a>
                
                     <a class="red" href="http://esf.cz.fang.com" target="_blank">常州</a>
                
                     <a class="red" href="http://esf.huaian.fang.com" target="_blank">淮安</a>
                
                     <a class="red" href="http://esf.jy.fang.com" target="_blank">江阴</a>
                
                     <a class="red" href="http://esf.ks.fang.com" target="_blank">昆山</a>
                
                     <a class="red" href="http://esf.lyg.fang.com" target="_blank">连云港</a>
                
                     <a class="red" href="http://esf.nanjing.fang.com" target="_blank">南京</a>
                
                     <a class="red" href="http://esf.nt.fang.com" target="_blank">南通</a>
                
                     <a class="red" href="http://esf.suzhou.fang.com" target="_blank">苏州</a>
                
                     <a class="red" href="http://esf.taizhou.fang.com" target="_blank">泰州</a>
                
                     <a class="red" href="http://esf.wuxi.fang.com" target="_blank">无锡</a>
                
                     <a class="red" href="http://esf.wj.fang.com" target="_blank">吴江</a>
                
                     <a class="red" href="http://esf.xz.fang.com" target="_blank">徐州</a>
                
                     <a class="red" href="http://esf.yancheng.fang.com" target="_blank">盐城</a>
                
                     <a class="red" href="http://esf.yz.fang.com" target="_blank">扬州</a>
                
                     <a class="red" href="http://esf.zhenjiang.fang.com" target="_blank">镇江</a>
                
                     <a class="red" href="http://esf.rudong.fang.com" target="_blank">如东</a>
                
                     <a class="red" href="http://esf.yizheng.fang.com" target="_blank">仪征</a>
                
                     <a class="red" href="http://esf.jintan.fang.com" target="_blank">金坛</a>
                
                     <a class="red" href="http://esf.jssn.fang.com" target="_blank">睢宁</a>
                
                     <a class="red" href="http://esf.jsfx.fang.com" target="_blank">丰县</a>
                
                     <a class="red" href="http://esf.peixian.fang.com" target="_blank">沛县</a>
                
                     <a class="red" href="http://esf.baoying.fang.com" target="_blank">宝应</a>
                
                     <a class="red" href="http://esf.jr.fang.com" target="_blank">句容</a>
                
                     <a class="red" href="http://esf.funing.fang.com" target="_blank">阜宁</a>
                
                     <a class="red" href="http://esf.njgc.fang.com" target="_blank">高淳</a>
                
                     <a class="red" href="http://esf.jiangyan.fang.com" target="_blank">姜堰</a>
                
                     <a class="red" href="http://esf.quanshan.fang.com" target="_blank">泉山</a>
                
                     <a class="red" href="http://esf.tongshan.fang.com" target="_blank">铜山</a>
                
                     <a class="red" href="http://esf.ksys.fang.com" target="_blank">玉山</a>
                
                     <a class="red" href="http://esf.haian.fang.com" target="_blank">海安</a>
                
                     <a class="red" href="http://esf.jinhu.fang.com" target="_blank">金湖</a>
                
                     <a class="red" href="http://esf.donghai.fang.com" target="_blank">东海</a>
                
            </li><li class="blubk02"><strong>浙江</strong>
          
                     <a class="red" href="http://esf.quzhou.fang.com" target="_blank">衢州</a>
                
                     <a class="red" href="http://esf.changxing.fang.com" target="_blank">长兴</a>
                
                     <a class="red" href="http://esf.deqing.fang.com" target="_blank">德清</a>
                
                     <a class="red" href="http://esf.tz.fang.com" target="_blank">台州</a>
                
                     <a class="red" href="http://esf.ruian.fang.com" target="_blank">瑞安</a>
                
                     <a class="red" href="http://esf.ls.fang.com" target="_blank">丽水</a>
                
                     <a class="red" href="http://esf.yueqing.fang.com" target="_blank">乐清</a>
                
                     <a class="red" href="http://esf.haining.fang.com" target="_blank">海宁</a>
                
                     <a class="red" href="http://esf.wenling.fang.com" target="_blank">温岭</a>
                
                     <a class="red" href="http://esf.linhai.fang.com" target="_blank">临海</a>
                
                     <a class="red" href="http://esf.zhuji.fang.com" target="_blank">诸暨</a>
                
                     <a class="red" href="http://esf.cixi.fang.com" target="_blank">慈溪</a>
                
                     <a class="red" href="http://esf.yuyao.fang.com" target="_blank">余姚</a>
                
                     <a class="red" href="http://esf.tongxiang.fang.com" target="_blank">桐乡</a>
                
                     <a class="red" href="http://esf.shangyu.fang.com" target="_blank">上虞</a>
                
                     <a class="red" href="http://esf.pinghu.fang.com" target="_blank">平湖</a>
                
                     <a class="red" href="http://esf.zjfy.fang.com" target="_blank">富阳</a>
                
                     <a class="red" href="http://esf.ninghai.fang.com" target="_blank">宁海</a>
                
                     <a class="red" href="http://esf.fenghua.fang.com" target="_blank">奉化</a>
                
                     <a class="red" href="http://esf.hz.fang.com" target="_blank">杭州</a>
                
                     <a class="red" href="http://esf.huzhou.fang.com" target="_blank">湖州</a>
                
                     <a class="red" href="http://esf.jx.fang.com" target="_blank">嘉兴</a>
                
                     <a class="red" href="http://esf.jh.fang.com" target="_blank">金华</a>
                
                     <a class="red" href="http://esf.nb.fang.com" target="_blank">宁波</a>
                
                     <a class="red" href="http://esf.shaoxing.fang.com" target="_blank">绍兴</a>
                
                     <a class="red" href="http://esf.wz.fang.com" target="_blank">温州</a>
                
                     <a class="red" href="http://esf.zhoushan.fang.com" target="_blank">舟山</a>
                
                     <a class="red" href="http://esf.linan.fang.com" target="_blank">临安</a>
                
                     <a class="red" href="http://esf.jiande.fang.com" target="_blank">建德</a>
                
                     <a class="red" href="http://esf.zjtl.fang.com" target="_blank">桐庐</a>
                
                     <a class="red" href="http://esf.zjxs.fang.com" target="_blank">象山</a>
                
                     <a class="red" href="http://esf.yuhuan.fang.com" target="_blank">玉环</a>
                
                     <a class="red" href="http://esf.chunan.fang.com" target="_blank">淳安</a>
                
                     <a class="red" href="http://esf.zhenhai.fang.com" target="_blank">镇海</a>
                
                     <a class="red" href="http://esf.aj.fang.com" target="_blank">安吉</a>
                
                     <a class="red" href="http://esf.haiyan.fang.com" target="_blank">海盐</a>
                
                   </li><li class="blubk"><strong>安徽</strong>
               
                     <a class="red" href="http://esf.huaibei.fang.com" target="_blank">淮北</a>
                
                     <a class="red" href="http://esf.tongling.fang.com" target="_blank">铜陵</a>
                
                     <a class="red" href="http://esf.anqing.fang.com" target="_blank">安庆</a>
                
                     <a class="red" href="http://esf.huangshan.fang.com" target="_blank">黄山</a>
                
                     <a class="red" href="http://esf.chuzhou.fang.com" target="_blank">滁州</a>
                
                     <a class="red" href="http://esf.fuyang.fang.com" target="_blank">阜阳</a>
                
                     <a class="red" href="http://esf.ahsuzhou.fang.com" target="_blank">宿州</a>
                
                     <a class="red" href="http://esf.chaohu.fang.com" target="_blank">巢湖</a>
                
                     <a class="red" href="http://esf.luan.fang.com" target="_blank">六安</a>
                
                     <a class="red" href="http://esf.bozhou.fang.com" target="_blank">亳州</a>
                
                     <a class="red" href="http://esf.chizhou.fang.com" target="_blank">池州</a>
                
                     <a class="red" href="http://esf.xuancheng.fang.com" target="_blank">宣城</a>
                
                     <a class="red" href="http://esf.huoqiu.fang.com" target="_blank">霍邱</a>
                
                     <a class="red" href="http://esf.tongcheng.fang.com" target="_blank">桐城</a>
                
                     <a class="red" href="http://esf.bengbu.fang.com" target="_blank">蚌埠</a>
                
                     <a class="red" href="http://esf.hf.fang.com" target="_blank">合肥</a>
                
                     <a class="red" href="http://esf.huainan.fang.com" target="_blank">淮南</a>
                
                     <a class="red" href="http://esf.mas.fang.com" target="_blank">马鞍山</a>
                
                     <a class="red" href="http://esf.wuhu.fang.com" target="_blank">芜湖</a>
                
                     <a class="red" href="http://esf.feixi.fang.com" target="_blank">肥西</a>
                
                     <a class="red" href="http://esf.lujiang.fang.com" target="_blank">庐江</a>
                
                     <a class="red" href="http://esf.feidong.fang.com" target="_blank">肥东</a>
                
                     <a class="red" href="http://esf.dangtu.fang.com" target="_blank">当涂</a>
                
                     <a class="red" href="http://esf.ahcf.fang.com" target="_blank">长丰</a>
                
                     <a class="red" href="http://esf.guzhen.fang.com" target="_blank">固镇</a>
                
                     <a class="red" href="http://esf.huaiyuan.fang.com" target="_blank">怀远</a>
                
                     <a class="red" href="http://esf.wuhe.fang.com" target="_blank">五河</a>
                
                     <a class="red" href="http://esf.fanchang.fang.com" target="_blank">繁昌</a>
                
            </li><li class="blubk02"><strong>福建</strong>
          
                     <a class="red" href="http://esf.sanming.fang.com" target="_blank">三明</a>
                
                     <a class="red" href="http://esf.nanping.fang.com" target="_blank">南平</a>
                
                     <a class="red" href="http://esf.longyan.fang.com" target="_blank">龙岩</a>
                
                     <a class="red" href="http://esf.ningde.fang.com" target="_blank">宁德</a>
                
                     <a class="red" href="http://esf.fq.fang.com" target="_blank">福清</a>
                
                     <a class="red" href="http://esf.pingtan.fang.com" target="_blank">平潭</a>
                
                     <a class="red" href="http://esf.nanan.fang.com" target="_blank">南安</a>
                
                     <a class="red" href="http://esf.longhai.fang.com" target="_blank">龙海</a>
                
                     <a class="red" href="http://esf.huian.fang.com" target="_blank">惠安</a>
                
                     <a class="red" href="http://esf.changle.fang.com" target="_blank">长乐</a>
                
                     <a class="red" href="http://esf.shishi.fang.com" target="_blank">石狮</a>
                
                     <a class="red" href="http://esf.fz.fang.com" target="_blank">福州</a>
                
                     <a class="red" href="http://esf.putian.fang.com" target="_blank">莆田</a>
                
                     <a class="red" href="http://esf.qz.fang.com" target="_blank">泉州</a>
                
                     <a class="red" href="http://esf.xm.fang.com" target="_blank">厦门</a>
                
                     <a class="red" href="http://esf.zhangzhou.fang.com" target="_blank">漳州</a>
                
                     <a class="red" href="http://esf.fjax.fang.com" target="_blank">安溪</a>
                
                     <a class="red" href="http://esf.lianjiang.fang.com" target="_blank">连江</a>
                
                     <a class="red" href="http://esf.yongchun.fang.com" target="_blank">永春</a>
                
                     <a class="red" href="http://esf.luoyuan.fang.com" target="_blank">罗源</a>
                
                     <a class="red" href="http://esf.minqing.fang.com" target="_blank">闽清</a>
                
                     <a class="red" href="http://esf.quangang.fang.com" target="_blank">泉港</a>
                
                     <a class="red" href="http://esf.yongtai.fang.com" target="_blank">永泰</a>
                
                     <a class="red" href="http://esf.fuan.fang.com" target="_blank">福安</a>
                
                     <a class="red" href="http://esf.dh.fang.com" target="_blank">德化</a>
                
                     <a class="red" href="http://esf.ya.fang.com" target="_blank">永安</a>
                
                   </li><li class="blubk"><strong>江西</strong>
               
                     <a class="red" href="http://esf.jingdezhen.fang.com" target="_blank">景德镇</a>
                
                     <a class="red" href="http://esf.pingxiang.fang.com" target="_blank">萍乡</a>
                
                     <a class="red" href="http://esf.xinyu.fang.com" target="_blank">新余</a>
                
                     <a class="red" href="http://esf.yingtan.fang.com" target="_blank">鹰潭</a>
                
                     <a class="red" href="http://esf.jian.fang.com" target="_blank">吉安</a>
                
                     <a class="red" href="http://esf.yichun.fang.com" target="_blank">宜春</a>
                
                     <a class="red" href="http://esf.jxfuzhou.fang.com" target="_blank">抚州</a>
                
                     <a class="red" href="http://esf.shangrao.fang.com" target="_blank">上饶</a>
                
                     <a class="red" href="http://esf.ganzhou.fang.com" target="_blank">赣州</a>
                
                     <a class="red" href="http://esf.jiujiang.fang.com" target="_blank">九江</a>
                
                     <a class="red" href="http://esf.nc.fang.com" target="_blank">南昌</a>
                
                     <a class="red" href="http://esf.jinxian.fang.com" target="_blank">进贤</a>
                
                     <a class="red" href="http://esf.xinjian.fang.com" target="_blank">新建</a>
                
                     <a class="red" href="http://esf.ruijin.fang.com" target="_blank">瑞金</a>
                
                     <a class="red" href="http://esf.jxfc.fang.com" target="_blank">丰城</a>
                
                     <a class="red" href="http://esf.jxja.fang.com" target="_blank">靖安</a>
                
            </li><li class="blubk02"><strong>山东</strong>
          
                     <a class="red" href="http://esf.zaozhuang.fang.com" target="_blank">枣庄</a>
                
                     <a class="red" href="http://esf.jining.fang.com" target="_blank">济宁</a>
                
                     <a class="red" href="http://esf.taian.fang.com" target="_blank">泰安</a>
                
                     <a class="red" href="http://esf.laiwu.fang.com" target="_blank">莱芜</a>
                
                     <a class="red" href="http://esf.dz.fang.com" target="_blank">德州</a>
                
                     <a class="red" href="http://esf.rz.fang.com" target="_blank">日照</a>
                
                     <a class="red" href="http://esf.heze.fang.com" target="_blank">菏泽</a>
                
                     <a class="red" href="http://esf.lc.fang.com" target="_blank">聊城</a>
                
                     <a class="red" href="http://esf.linyi.fang.com" target="_blank">临沂</a>
                
                     <a class="red" href="http://esf.dy.fang.com" target="_blank">东营</a>
                
                     <a class="red" href="http://esf.zhangqiu.fang.com" target="_blank">章丘</a>
                
                     <a class="red" href="http://esf.zhucheng.fang.com" target="_blank">诸城</a>
                
                     <a class="red" href="http://esf.zy.fang.com" target="_blank">招远</a>
                
                     <a class="red" href="http://esf.tengzhou.fang.com" target="_blank">滕州</a>
                
                     <a class="red" href="http://esf.sg.fang.com" target="_blank">寿光</a>
                
                     <a class="red" href="http://esf.pingdu.fang.com" target="_blank">平度</a>
                
                     <a class="red" href="http://esf.xintai.fang.com" target="_blank">新泰</a>
                
                     <a class="red" href="http://esf.zoucheng.fang.com" target="_blank">邹城</a>
                
                     <a class="red" href="http://esf.feicheng.fang.com" target="_blank">肥城</a>
                
                     <a class="red" href="http://esf.laizhou.fang.com" target="_blank">莱州</a>
                
                     <a class="red" href="http://esf.zouping.fang.com" target="_blank">邹平</a>
                
                     <a class="red" href="http://esf.jiaonan.fang.com" target="_blank">胶南</a>
                
                     <a class="red" href="http://esf.longkou.fang.com" target="_blank">龙口</a>
                
                     <a class="red" href="http://esf.zb.fang.com" target="_blank">淄博</a>
                
                     <a class="red" href="http://esf.gaomi.fang.com" target="_blank">高密</a>
                
                     <a class="red" href="http://esf.binzhou.fang.com" target="_blank">滨州</a>
                
                     <a class="red" href="http://esf.jn.fang.com" target="_blank">济南</a>
                
                     <a class="red" href="http://esf.qd.fang.com" target="_blank">青岛</a>
                
                     <a class="red" href="http://esf.weihai.fang.com" target="_blank">威海</a>
                
                     <a class="red" href="http://esf.wf.fang.com" target="_blank">潍坊</a>
                
                     <a class="red" href="http://esf.yt.fang.com" target="_blank">烟台</a>
                
                     <a class="red" href="http://esf.jimo.fang.com" target="_blank">即墨</a>
                
                     <a class="red" href="http://esf.laixi.fang.com" target="_blank">莱西</a>
                
                     <a class="red" href="http://esf.changyi.fang.com" target="_blank">昌邑</a>
                
                     <a class="red" href="http://esf.guangrao.fang.com" target="_blank">广饶</a>
                
                     <a class="red" href="http://esf.penglai.fang.com" target="_blank">蓬莱</a>
                
                     <a class="red" href="http://esf.anqiu.fang.com" target="_blank">安丘</a>
                
                     <a class="red" href="http://esf.qingzhou.fang.com" target="_blank">青州</a>
                
                     <a class="red" href="http://esf.linqu.fang.com" target="_blank">临朐</a>
                
                     <a class="red" href="http://esf.sdjy.fang.com" target="_blank">济阳</a>
                
                     <a class="red" href="http://esf.sdsh.fang.com" target="_blank">商河</a>
                
                     <a class="red" href="http://esf.sdcl.fang.com" target="_blank">昌乐</a>
                
                     <a class="red" href="http://esf.laiyang.fang.com" target="_blank">莱阳</a>
                
                     <a class="red" href="http://esf.sdpy.fang.com" target="_blank">平阴</a>
                
                     <a class="red" href="http://esf.linqing.fang.com" target="_blank">临清</a>
                
                     <a class="red" href="http://esf.qixia.fang.com" target="_blank">栖霞</a>
                
                     <a class="red" href="http://esf.haiyang.fang.com" target="_blank">海阳</a>
                
                     <a class="red" href="http://esf.ytcd.fang.com" target="_blank">长岛</a>
                
                     <a class="red" href="http://esf.jncq.fang.com" target="_blank">长清</a>
                
                     <a class="red" href="http://esf.jiaozhou.fang.com" target="_blank">胶州</a>
                
                   </li><li class="blubk"><strong>河南</strong>
               
                     <a class="red" href="http://esf.kaifeng.fang.com" target="_blank">开封</a>
                
                     <a class="red" href="http://esf.pingdingshan.fang.com" target="_blank">平顶山</a>
                
                     <a class="red" href="http://esf.anyang.fang.com" target="_blank">安阳</a>
                
                     <a class="red" href="http://esf.hebi.fang.com" target="_blank">鹤壁</a>
                
                     <a class="red" href="http://esf.jiaozuo.fang.com" target="_blank">焦作</a>
                
                     <a class="red" href="http://esf.puyang.fang.com" target="_blank">濮阳</a>
                
                     <a class="red" href="http://esf.xuchang.fang.com" target="_blank">许昌</a>
                
                     <a class="red" href="http://esf.luohe.fang.com" target="_blank">漯河</a>
                
                     <a class="red" href="http://esf.sanmenxia.fang.com" target="_blank">三门峡</a>
                
                     <a class="red" href="http://esf.nanyang.fang.com" target="_blank">南阳</a>
                
                     <a class="red" href="http://esf.shangqiu.fang.com" target="_blank">商丘</a>
                
                     <a class="red" href="http://esf.xinyang.fang.com" target="_blank">信阳</a>
                
                     <a class="red" href="http://esf.zhoukou.fang.com" target="_blank">周口</a>
                
                     <a class="red" href="http://esf.zhumadian.fang.com" target="_blank">驻马店</a>
                
                     <a class="red" href="http://esf.xx.fang.com" target="_blank">新乡</a>
                
                     <a class="red" href="http://esf.hnyz.fang.com" target="_blank">禹州</a>
                
                     <a class="red" href="http://esf.changge.fang.com" target="_blank">长葛</a>
                
                     <a class="red" href="http://esf.yanling.fang.com" target="_blank">鄢陵</a>
                
                     <a class="red" href="http://esf.gongyi.fang.com" target="_blank">巩义</a>
                
                     <a class="red" href="http://esf.jiyuan.fang.com" target="_blank">济源</a>
                
                     <a class="red" href="http://esf.ly.fang.com" target="_blank">洛阳</a>
                
                     <a class="red" href="http://esf.zz.fang.com" target="_blank">郑州</a>
                
                     <a class="red" href="http://esf.xinzheng.fang.com" target="_blank">新郑</a>
                
                     <a class="red" href="http://esf.xingyang.fang.com" target="_blank">荥阳</a>
                
                     <a class="red" href="http://esf.yichuan.fang.com" target="_blank">伊川</a>
                
                     <a class="red" href="http://esf.yanshi.fang.com" target="_blank">偃师</a>
                
                     <a class="red" href="http://esf.zhongmou.fang.com" target="_blank">中牟</a>
                
                     <a class="red" href="http://esf.dengfeng.fang.com" target="_blank">登封</a>
                
                     <a class="red" href="http://esf.hnxa.fang.com" target="_blank">新安</a>
                
                     <a class="red" href="http://esf.hnyy.fang.com" target="_blank">宜阳</a>
                
                     <a class="red" href="http://esf.xinmi.fang.com" target="_blank">新密</a>
                
                     <a class="red" href="http://esf.songxian.fang.com" target="_blank">嵩县</a>
                
                     <a class="red" href="http://esf.luoning.fang.com" target="_blank">洛宁</a>
                
                     <a class="red" href="http://esf.mengjin.fang.com" target="_blank">孟津</a>
                
                     <a class="red" href="http://esf.ruyang.fang.com" target="_blank">汝阳</a>
                
                     <a class="red" href="http://esf.dengzhou.fang.com" target="_blank">邓州</a>
                
                     <a class="red" href="http://esf.lankao.fang.com" target="_blank">兰考</a>
                
                     <a class="red" href="http://esf.ruzhou.fang.com" target="_blank">汝州</a>
                
                     <a class="red" href="http://esf.luanchuan.fang.com" target="_blank">栾川</a>
                
                     <a class="red" href="http://esf.yongcheng.fang.com" target="_blank">永城</a>
                
                     <a class="red" href="http://esf.wg.fang.com" target="_blank">舞钢</a>
                
            </li><li class="blubk02"><strong>湖北</strong>
          
                     <a class="red" href="http://esf.shiyan.fang.com" target="_blank">十堰</a>
                
                     <a class="red" href="http://esf.ezhou.fang.com" target="_blank">鄂州</a>
                
                     <a class="red" href="http://esf.jingmen.fang.com" target="_blank">荆门</a>
                
                     <a class="red" href="http://esf.xiaogan.fang.com" target="_blank">孝感</a>
                
                     <a class="red" href="http://esf.jingzhou.fang.com" target="_blank">荆州</a>
                
                     <a class="red" href="http://esf.huanggang.fang.com" target="_blank">黄冈</a>
                
                     <a class="red" href="http://esf.xianning.fang.com" target="_blank">咸宁</a>
                
                     <a class="red" href="http://esf.suizhou.fang.com" target="_blank">随州</a>
                
                     <a class="red" href="http://esf.enshi.fang.com" target="_blank">恩施</a>
                
                     <a class="red" href="http://esf.xiantao.fang.com" target="_blank">仙桃</a>
                
                     <a class="red" href="http://esf.shennongjia.fang.com" target="_blank">神农架</a>
                
                     <a class="red" href="http://esf.tianmen.fang.com" target="_blank">天门</a>
                
                     <a class="red" href="http://esf.qj.fang.com" target="_blank">潜江</a>
                
                     <a class="red" href="http://esf.huangshi.fang.com" target="_blank">黄石</a>
                
                     <a class="red" href="http://esf.wuhan.fang.com" target="_blank">武汉</a>
                
                     <a class="red" href="http://esf.xiangyang.fang.com" target="_blank">襄阳</a>
                
                     <a class="red" href="http://esf.yc.fang.com" target="_blank">宜昌</a>
                
                     <a class="red" href="http://esf.dangyang.fang.com" target="_blank">当阳</a>
                
                     <a class="red" href="http://esf.yidu.fang.com" target="_blank">宜都</a>
                
                     <a class="red" href="http://esf.zhijiang.fang.com" target="_blank">枝江</a>
                
                     <a class="red" href="http://esf.whhn.fang.com" target="_blank">汉南</a>
                
                     <a class="red" href="http://esf.hbjs.fang.com" target="_blank">京山</a>
                
                     <a class="red" href="http://esf.zhongxiang.fang.com" target="_blank">钟祥</a>
                
                     <a class="red" href="http://esf.lhk.fang.com" target="_blank">老河口</a>
                
                     <a class="red" href="http://esf.hbyc.fang.com" target="_blank">宜城</a>
                
                     <a class="red" href="http://esf.hbzy.fang.com" target="_blank">枣阳</a>
                
                   </li><li class="blubk"><strong>湖南</strong>
               
                     <a class="red" href="http://esf.shaoyang.fang.com" target="_blank">邵阳</a>
                
                     <a class="red" href="http://esf.zhangjiajie.fang.com" target="_blank">张家界</a>
                
                     <a class="red" href="http://esf.yiyang.fang.com" target="_blank">益阳</a>
                
                     <a class="red" href="http://esf.chenzhou.fang.com" target="_blank">郴州</a>
                
                     <a class="red" href="http://esf.yongzhou.fang.com" target="_blank">永州</a>
                
                     <a class="red" href="http://esf.huaihua.fang.com" target="_blank">怀化</a>
                
                     <a class="red" href="http://esf.loudi.fang.com" target="_blank">娄底</a>
                
                     <a class="red" href="http://esf.xiangxi.fang.com" target="_blank">湘西</a>
                
                     <a class="red" href="http://esf.cs.fang.com" target="_blank">长沙</a>
                
                     <a class="red" href="http://esf.changde.fang.com" target="_blank">常德</a>
                
                     <a class="red" href="http://esf.hengyang.fang.com" target="_blank">衡阳</a>
                
                     <a class="red" href="http://esf.xt.fang.com" target="_blank">湘潭</a>
                
                     <a class="red" href="http://esf.yueyang.fang.com" target="_blank">岳阳</a>
                
                     <a class="red" href="http://esf.zhuzhou.fang.com" target="_blank">株洲</a>
                
                     <a class="red" href="http://esf.liuyang.fang.com" target="_blank">浏阳</a>
                
                     <a class="red" href="http://esf.ningxiang.fang.com" target="_blank">宁乡</a>
                
                     <a class="red" href="http://esf.liling.fang.com" target="_blank">醴陵</a>
                
                     <a class="red" href="http://esf.xiangxiang.fang.com" target="_blank">湘乡</a>
                
                     <a class="red" href="http://esf.youxian.fang.com" target="_blank">攸县</a>
                
                     <a class="red" href="http://esf.cswc.fang.com" target="_blank">望城</a>
                
                     <a class="red" href="http://esf.leiyang.fang.com" target="_blank">耒阳</a>
                
                     <a class="red" href="http://esf.cn.fang.com" target="_blank">常宁</a>
                
                     <a class="red" href="http://esf.ss.fang.com" target="_blank">韶山</a>
                
            </li><li class="blubk02"><strong>广东</strong>
          
                     <a class="red" href="http://esf.shaoguan.fang.com" target="_blank">韶关</a>
                
                     <a class="red" href="http://esf.shanwei.fang.com" target="_blank">汕尾</a>
                
                     <a class="red" href="http://esf.heyuan.fang.com" target="_blank">河源</a>
                
                     <a class="red" href="http://esf.chaozhou.fang.com" target="_blank">潮州</a>
                
                     <a class="red" href="http://esf.jieyang.fang.com" target="_blank">揭阳</a>
                
                     <a class="red" href="http://esf.yunfu.fang.com" target="_blank">云浮</a>
                
                     <a class="red" href="http://esf.shunde.fang.com" target="_blank">顺德</a>
                
                     <a class="red" href="http://esf.yangchun.fang.com" target="_blank">阳春</a>
                
                     <a class="red" href="http://esf.kaiping.fang.com" target="_blank">开平</a>
                
                     <a class="red" href="http://esf.dg.fang.com" target="_blank">东莞</a>
                
                     <a class="red" href="http://esf.fs.fang.com" target="_blank">佛山</a>
                
                     <a class="red" href="http://esf.gz.fang.com" target="_blank">广州</a>
                
                     <a class="red" href="http://esf.huizhou.fang.com" target="_blank">惠州</a>
                
                     <a class="red" href="http://esf.jm.fang.com" target="_blank">江门</a>
                
                     <a class="red" href="http://esf.maoming.fang.com" target="_blank">茂名</a>
                
                     <a class="red" href="http://esf.meizhou.fang.com" target="_blank">梅州</a>
                
                     <a class="red" href="http://esf.qingyuan.fang.com" target="_blank">清远</a>
                
                     <a class="red" href="http://esf.st.fang.com" target="_blank">汕头</a>
                
                     <a class="red" href="http://esf.sz.fang.com" target="_blank">深圳</a>
                
                     <a class="red" href="http://esf.yangjiang.fang.com" target="_blank">阳江</a>
                
                     <a class="red" href="http://esf.zj.fang.com" target="_blank">湛江</a>
                
                     <a class="red" href="http://esf.zhaoqing.fang.com" target="_blank">肇庆</a>
                
                     <a class="red" href="http://esf.zs.fang.com" target="_blank">中山</a>
                
                     <a class="red" href="http://esf.zh.fang.com" target="_blank">珠海</a>
                
                     <a class="red" href="http://esf.taishan.fang.com" target="_blank">台山</a>
                
                     <a class="red" href="http://esf.enping.fang.com" target="_blank">恩平</a>
                
                     <a class="red" href="http://esf.huidong.fang.com" target="_blank">惠东</a>
                
                     <a class="red" href="http://esf.gdlm.fang.com" target="_blank">龙门</a>
                
                     <a class="red" href="http://esf.boluo.fang.com" target="_blank">博罗</a>
                
                     <a class="red" href="http://esf.heshan.fang.com" target="_blank">鹤山</a>
                
                     <a class="red" href="http://esf.puning.fang.com" target="_blank">普宁</a>
                
                     <a class="red" href="http://esf.ld.fang.com" target="_blank">罗定</a>
                
                     <a class="red" href="http://esf.xf.fang.com" target="_blank">新丰</a>
                
                   </li><li class="blubk"><strong>广西</strong>
               
                     <a class="red" href="http://esf.wuzhou.fang.com" target="_blank">梧州</a>
                
                     <a class="red" href="http://esf.qinzhou.fang.com" target="_blank">钦州</a>
                
                     <a class="red" href="http://esf.baise.fang.com" target="_blank">百色</a>
                
                     <a class="red" href="http://esf.hezhou.fang.com" target="_blank">贺州</a>
                
                     <a class="red" href="http://esf.hechi.fang.com" target="_blank">河池</a>
                
                     <a class="red" href="http://esf.laibin.fang.com" target="_blank">来宾</a>
                
                     <a class="red" href="http://esf.chongzuo.fang.com" target="_blank">崇左</a>
                
                     <a class="red" href="http://esf.yl.fang.com" target="_blank">玉林</a>
                
                     <a class="red" href="http://esf.bh.fang.com" target="_blank">北海</a>
                
                     <a class="red" href="http://esf.fangchenggang.fang.com" target="_blank">防城港</a>
                
                     <a class="red" href="http://esf.guigang.fang.com" target="_blank">贵港</a>
                
                     <a class="red" href="http://esf.guilin.fang.com" target="_blank">桂林</a>
                
                     <a class="red" href="http://esf.liuzhou.fang.com" target="_blank">柳州</a>
                
                     <a class="red" href="http://esf.nn.fang.com" target="_blank">南宁</a>
                
                     <a class="red" href="http://esf.gxby.fang.com" target="_blank">宾阳</a>
                
                     <a class="red" href="http://esf.hengxian.fang.com" target="_blank">横县</a>
                
                     <a class="red" href="http://esf.yongning.fang.com" target="_blank">邕宁</a>
                
            </li><li class="blubk02"><strong>重庆</strong>
          
                     <a class="red" href="http://esf.changshou.fang.com" target="_blank">长寿</a>
                
                     <a class="red" href="http://esf.jiangjin.fang.com" target="_blank">江津</a>
                
                     <a class="red" href="http://esf.yongchuan.fang.com" target="_blank">永川</a>
                
                     <a class="red" href="http://esf.cq.fang.com" target="_blank">重庆</a>
                
                     <a class="red" href="http://esf.bishan.fang.com" target="_blank">璧山</a>
                
                     <a class="red" href="http://esf.dazu.fang.com" target="_blank">大足</a>
                
                     <a class="red" href="http://esf.dianjiang.fang.com" target="_blank">垫江</a>
                
                     <a class="red" href="http://esf.fengjie.fang.com" target="_blank">奉节</a>
                
                     <a class="red" href="http://esf.kaixian.fang.com" target="_blank">开县</a>
                
                     <a class="red" href="http://esf.liangping.fang.com" target="_blank">梁平</a>
                
                     <a class="red" href="http://esf.rongchang.fang.com" target="_blank">荣昌</a>
                
                     <a class="red" href="http://esf.shizhu.fang.com" target="_blank">石柱</a>
                
                     <a class="red" href="http://esf.tongnan.fang.com" target="_blank">潼南</a>
                
                     <a class="red" href="http://esf.wulong.fang.com" target="_blank">武隆</a>
                
                     <a class="red" href="http://esf.wushan.fang.com" target="_blank">巫山</a>
                
                     <a class="red" href="http://esf.yunyang.fang.com" target="_blank">云阳</a>
                
                     <a class="red" href="http://esf.zhongxian.fang.com" target="_blank">忠县</a>
                
                     <a class="red" href="http://esf.tongliang.fang.com" target="_blank">铜梁</a>
                
                     <a class="red" href="http://esf.fengdu.fang.com" target="_blank">丰都</a>
                
                   </li><li class="blubk"><strong>四川</strong>
               
                     <a class="red" href="http://esf.zigong.fang.com" target="_blank">自贡</a>
                
                     <a class="red" href="http://esf.panzhihua.fang.com" target="_blank">攀枝花</a>
                
                     <a class="red" href="http://esf.guangyuan.fang.com" target="_blank">广元</a>
                
                     <a class="red" href="http://esf.yibin.fang.com" target="_blank">宜宾</a>
                
                     <a class="red" href="http://esf.guangan.fang.com" target="_blank">广安</a>
                
                     <a class="red" href="http://esf.dazhou.fang.com" target="_blank">达州</a>
                
                     <a class="red" href="http://esf.yaan.fang.com" target="_blank">雅安</a>
                
                     <a class="red" href="http://esf.bazhong.fang.com" target="_blank">巴中</a>
                
                     <a class="red" href="http://esf.ziyang.fang.com" target="_blank">资阳</a>
                
                     <a class="red" href="http://esf.liangshan.fang.com" target="_blank">凉山</a>
                
                     <a class="red" href="http://esf.abazhou.fang.com" target="_blank">阿坝州</a>
                
                     <a class="red" href="http://esf.fuling.fang.com" target="_blank">涪陵</a>
                
                     <a class="red" href="http://esf.qianjiang.fang.com" target="_blank">黔江</a>
                
                     <a class="red" href="http://esf.wanzhou.fang.com" target="_blank">万州</a>
                
                     <a class="red" href="http://esf.qijiang.fang.com" target="_blank">綦江</a>
                
                     <a class="red" href="http://esf.hechuan.fang.com" target="_blank">合川</a>
                
                     <a class="red" href="http://esf.ganzi.fang.com" target="_blank">甘孜</a>
                
                     <a class="red" href="http://esf.cd.fang.com" target="_blank">成都</a>
                
                     <a class="red" href="http://esf.deyang.fang.com" target="_blank">德阳</a>
                
                     <a class="red" href="http://esf.leshan.fang.com" target="_blank">乐山</a>
                
                     <a class="red" href="http://esf.luzhou.fang.com" target="_blank">泸州</a>
                
                     <a class="red" href="http://esf.meishan.fang.com" target="_blank">眉山</a>
                
                     <a class="red" href="http://esf.mianyang.fang.com" target="_blank">绵阳</a>
                
                     <a class="red" href="http://esf.neijiang.fang.com" target="_blank">内江</a>
                
                     <a class="red" href="http://esf.nanchong.fang.com" target="_blank">南充</a>
                
                     <a class="red" href="http://esf.suining.fang.com" target="_blank">遂宁</a>
                
                     <a class="red" href="http://esf.pengzhou.fang.com" target="_blank">彭州</a>
                
                     <a class="red" href="http://esf.scjt.fang.com" target="_blank">金堂</a>
                
                     <a class="red" href="http://esf.qionglai.fang.com" target="_blank">邛崃</a>
                
                     <a class="red" href="http://esf.chongzhou.fang.com" target="_blank">崇州</a>
                
                     <a class="red" href="http://esf.dayi.fang.com" target="_blank">大邑</a>
                
                     <a class="red" href="http://esf.jianyang.fang.com" target="_blank">简阳</a>
                
                     <a class="red" href="http://esf.dujiangyan.fang.com" target="_blank">都江堰</a>
                
                     <a class="red" href="http://esf.xinjin.fang.com" target="_blank">新津</a>
                
                     <a class="red" href="http://esf.emeishan.fang.com" target="_blank">峨眉山</a>
                
            </li><li class="blubk02"><strong>贵州</strong>
          
                     <a class="red" href="http://esf.lps.fang.com" target="_blank">六盘水</a>
                
                     <a class="red" href="http://esf.zunyi.fang.com" target="_blank">遵义</a>
                
                     <a class="red" href="http://esf.anshun.fang.com" target="_blank">安顺</a>
                
                     <a class="red" href="http://esf.tongren.fang.com" target="_blank">铜仁</a>
                
                     <a class="red" href="http://esf.qianxinan.fang.com" target="_blank">黔西南</a>
                
                     <a class="red" href="http://esf.bijie.fang.com" target="_blank">毕节</a>
                
                     <a class="red" href="http://esf.qdn.fang.com" target="_blank">黔东南</a>
                
                     <a class="red" href="http://esf.qiannan.fang.com" target="_blank">黔南</a>
                
                     <a class="red" href="http://esf.gy.fang.com" target="_blank">贵阳</a>
                
                     <a class="red" href="http://esf.kaiyang.fang.com" target="_blank">开阳</a>
                
                     <a class="red" href="http://esf.xiuwen.fang.com" target="_blank">修文</a>
                
                     <a class="red" href="http://esf.qingzhen.fang.com" target="_blank">清镇</a>
                
                   </li><li class="blubk"><strong>云南</strong>
               
                     <a class="red" href="http://esf.yuxi.fang.com" target="_blank">玉溪</a>
                
                     <a class="red" href="http://esf.baoshan.fang.com" target="_blank">保山</a>
                
                     <a class="red" href="http://esf.zhaotong.fang.com" target="_blank">昭通</a>
                
                     <a class="red" href="http://esf.puer.fang.com" target="_blank">普洱</a>
                
                     <a class="red" href="http://esf.lincang.fang.com" target="_blank">临沧</a>
                
                     <a class="red" href="http://esf.chuxiong.fang.com" target="_blank">楚雄</a>
                
                     <a class="red" href="http://esf.honghe.fang.com" target="_blank">红河</a>
                
                     <a class="red" href="http://esf.wenshan.fang.com" target="_blank">文山</a>
                
                     <a class="red" href="http://esf.xishuangbanna.fang.com" target="_blank">西双版纳</a>
                
                     <a class="red" href="http://esf.dali.fang.com" target="_blank">大理</a>
                
                     <a class="red" href="http://esf.dehong.fang.com" target="_blank">德宏</a>
                
                     <a class="red" href="http://esf.diqing.fang.com" target="_blank">迪庆</a>
                
                     <a class="red" href="http://esf.nujiang.fang.com" target="_blank">怒江</a>
                
                     <a class="red" href="http://esf.km.fang.com" target="_blank">昆明</a>
                
                     <a class="red" href="http://esf.lijiang.fang.com" target="_blank">丽江</a>
                
                     <a class="red" href="http://esf.qujing.fang.com" target="_blank">曲靖</a>
                
                     <a class="red" href="http://esf.anning.fang.com" target="_blank">安宁</a>
                
                     <a class="red" href="http://esf.ynyl.fang.com" target="_blank">宜良</a>
                
            </li><li class="blubk02"><strong>海南</strong>
          
                     <a class="red" href="http://esf.sansha.fang.com" target="_blank">三沙</a>
                
                     <a class="red" href="http://esf.wuzhishan.fang.com" target="_blank">五指山</a>
                
                     <a class="red" href="http://esf.hn.fang.com" target="_blank">海南</a>
                
                     <a class="red" href="http://esf.sanya.fang.com" target="_blank">三亚</a>
                
                     <a class="red" href="http://esf.dongfang.fang.com" target="_blank">东方</a>
                
                     <a class="red" href="http://esf.danzhou.fang.com" target="_blank">儋州</a>
                
                     <a class="red" href="http://esf.wanning.fang.com" target="_blank">万宁</a>
                
                   </li><li class="blubk"><strong>陕西</strong>
               
                     <a class="red" href="http://esf.tongchuan.fang.com" target="_blank">铜川</a>
                
                     <a class="red" href="http://esf.weinan.fang.com" target="_blank">渭南</a>
                
                     <a class="red" href="http://esf.yanan.fang.com" target="_blank">延安</a>
                
                     <a class="red" href="http://esf.hanzhong.fang.com" target="_blank">汉中</a>
                
                     <a class="red" href="http://esf.sxyulin.fang.com" target="_blank">榆林</a>
                
                     <a class="red" href="http://esf.ankang.fang.com" target="_blank">安康</a>
                
                     <a class="red" href="http://esf.shangluo.fang.com" target="_blank">商洛</a>
                
                     <a class="red" href="http://esf.baoji.fang.com" target="_blank">宝鸡</a>
                
                     <a class="red" href="http://esf.xian.fang.com" target="_blank">西安</a>
                
                     <a class="red" href="http://esf.xianyang.fang.com" target="_blank">咸阳</a>
                
                     <a class="red" href="http://esf.lantian.fang.com" target="_blank">蓝田</a>
                
                     <a class="red" href="http://esf.huxian.fang.com" target="_blank">户县</a>
                
                     <a class="red" href="http://esf.zhouzhi.fang.com" target="_blank">周至</a>
                
                     <a class="red" href="http://esf.gaoling.fang.com" target="_blank">高陵</a>
                
            </li><li class="blubk02"><strong>甘肃</strong>
          
                     <a class="red" href="http://esf.jiayuguan.fang.com" target="_blank">嘉峪关</a>
                
                     <a class="red" href="http://esf.jinchang.fang.com" target="_blank">金昌</a>
                
                     <a class="red" href="http://esf.baiyin.fang.com" target="_blank">白银</a>
                
                     <a class="red" href="http://esf.tianshui.fang.com" target="_blank">天水</a>
                
                     <a class="red" href="http://esf.wuwei.fang.com" target="_blank">武威</a>
                
                     <a class="red" href="http://esf.zhangye.fang.com" target="_blank">张掖</a>
                
                     <a class="red" href="http://esf.pingliang.fang.com" target="_blank">平凉</a>
                
                     <a class="red" href="http://esf.jiuquan.fang.com" target="_blank">酒泉</a>
                
                     <a class="red" href="http://esf.dingxi.fang.com" target="_blank">定西</a>
                
                     <a class="red" href="http://esf.longnan.fang.com" target="_blank">陇南</a>
                
                     <a class="red" href="http://esf.gannan.fang.com" target="_blank">甘南</a>
                
                     <a class="red" href="http://esf.linxia.fang.com" target="_blank">临夏</a>
                
                     <a class="red" href="http://esf.lz.fang.com" target="_blank">兰州</a>
                
                     <a class="red" href="http://esf.qingyang.fang.com" target="_blank">庆阳</a>
                
                     <a class="red" href="http://esf.yongdeng.fang.com" target="_blank">永登</a>
                
                     <a class="red" href="http://esf.yuzhong.fang.com" target="_blank">榆中</a>
                
                   </li><li class="blubk"><strong>宁夏</strong>
               
                     <a class="red" href="http://esf.shizuishan.fang.com" target="_blank">石嘴山</a>
                
                     <a class="red" href="http://esf.wuzhong.fang.com" target="_blank">吴忠</a>
                
                     <a class="red" href="http://esf.guyuan.fang.com" target="_blank">固原</a>
                
                     <a class="red" href="http://esf.zhongwei.fang.com" target="_blank">中卫</a>
                
                     <a class="red" href="http://esf.yinchuan.fang.com" target="_blank">银川</a>
                
            </li><li class="blubk02"><strong>青海</strong>
          
                     <a class="red" href="http://esf.yushu.fang.com" target="_blank">玉树</a>
                
                     <a class="red" href="http://esf.guoluo.fang.com" target="_blank">果洛</a>
                
                     <a class="red" href="http://esf.huangnan.fang.com" target="_blank">黄南</a>
                
                     <a class="red" href="http://esf.haixi.fang.com" target="_blank">海西</a>
                
                     <a class="red" href="http://esf.haidong.fang.com" target="_blank">海东</a>
                
                     <a class="red" href="http://esf.haibei.fang.com" target="_blank">海北</a>
                
                     <a class="red" href="http://esf.xn.fang.com" target="_blank">西宁</a>
                
                   </li><li class="blubk"><strong>新疆</strong>
               
                     <a class="red" href="http://esf.kelamayi.fang.com" target="_blank">克拉玛依</a>
                
                     <a class="red" href="http://esf.alaer.fang.com" target="_blank">阿拉尔</a>
                
                     <a class="red" href="http://esf.betl.fang.com" target="_blank">博尔塔拉</a>
                
                     <a class="red" href="http://esf.tulufan.fang.com" target="_blank">吐鲁番</a>
                
                     <a class="red" href="http://esf.kashi.fang.com" target="_blank">喀什</a>
                
                     <a class="red" href="http://esf.tmsk.fang.com" target="_blank">图木舒克</a>
                
                     <a class="red" href="http://esf.wujiaqu.fang.com" target="_blank">五家渠</a>
                
                     <a class="red" href="http://esf.hami.fang.com" target="_blank">哈密</a>
                
                     <a class="red" href="http://esf.hetian.fang.com" target="_blank">和田</a>
                
                     <a class="red" href="http://esf.kuerle.fang.com" target="_blank">库尔勒</a>
                
                     <a class="red" href="http://esf.kzls.fang.com" target="_blank">克孜勒苏</a>
                
                     <a class="red" href="http://esf.akesu.fang.com" target="_blank">阿克苏</a>
                
                     <a class="red" href="http://esf.bazhou.fang.com" target="_blank">巴州</a>
                
                     <a class="red" href="http://esf.changji.fang.com" target="_blank">昌吉</a>
                
                     <a class="red" href="http://esf.shihezi.fang.com" target="_blank">石河子</a>
                
                     <a class="red" href="http://esf.xj.fang.com" target="_blank">乌鲁木齐</a>
                
                     <a class="red" href="http://esf.yili.fang.com" target="_blank">伊犁</a>
                
                     <a class="red" href="http://esf.kuitun.fang.com" target="_blank">奎屯</a>
                
               
                    </li><li class="blubk02"><strong>其他</strong> <a href="http://www.hkproperty.com/" target="_blank">香港</a> <a href="http://esf.macau.fang.com/" target="_blank">澳门</a>

                        <a href="http://www.twproperty.com.tw/" target="_blank">台湾</a>
                        <a href="http://www.sgproperty.com/" target="_blank">新加坡</a>
                        <a href="http://vancouver.fang.com/" target="_blank">温哥华</a></li>
                </ul>
            </div>
';

//$html = curl_get('http://esf.hf.fang.com/newsecond/esfcities.aspx');

//获取所有的省
$pros = selector::select($html,"//div[contains(@class,'outCont')]/ul/li/strong");

//获取每个省下的市区
$arr = [];
foreach ($pros as $k=>$v){
    $index = $k+1;
    $citys = selector::select($html,"//div[contains(@class,'outCont')]/ul/li[{$index}]/a");
    $citys_code = selector::select($html,"//div[contains(@class,'outCont')]/ul/li[{$index}]/a/@href");

    foreach ($citys as $m=>$n){
        preg_match('/http:\/\/esf.(.*?).fang.com/',$citys_code[$m],$code);

        $arr[$v][] = [
                'province_name'=>$v,
                'city_name'=>$n.'市',
                'code'=>$code[1] ?? '',
        ];
    }
}
$citys_json = file_put_contents('./city_back.json',json_encode($arr,JSON_UNESCAPED_UNICODE));

echo '省市区结构爬取完成!';

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

function unicode_decode($name)
{

    $json = '{"str":"' . $name . '"}';
    $arr = json_decode($json, true);
    if (empty($arr)) {
        return '';
    }
    return $arr['str'];
}
