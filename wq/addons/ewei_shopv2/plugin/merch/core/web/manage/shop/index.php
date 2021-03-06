<?php
/*
 * 人人商城
 *
 * 青岛易联互动网络科技有限公司
 * http://www.we7shop.cn
 * TEL: 4000097827/18661772381/15865546761
 */
require EWEI_SHOPV2_PLUGIN . 'merch/core/inc/page_merch.php';

class Index_EweiShopV2Page extends MerchWebPage
{

    function main()
    {
        global $_W, $_GPC;

        if(!empty($_W['shopversion']))
        {
            if(mcv('shop.adv')){
                header('location: '.webUrl('shop/adv'));
            }
            elseif(mcv('shop.nav')){
                header('location: '.webUrl('shop/nav'));
            }
            elseif(mcv('shop.banner')){
                header('location: '.webUrl('shop/banner'));
            }
            elseif(mcv('shop.cube')){
                header('location: '.webUrl('shop/cube'));
            }
            elseif(mcv('shop.recommand')){
                header('location: '.webUrl('shop/recommand'));
            }
            elseif(mcv('shop.sort')){
                header('location: '.webUrl('shop/sort'));
            }
            elseif(mcv('shop.verify.store')){
                header('location: '.webUrl('shop/verify/store'));
            }
            elseif(mcv('shop.verify.saler')){
                header('location: '.webUrl('shop/verify/saler'));
            }
            elseif(mcv('shop.verify.set')){
                header('location: '.webUrl('shop/verify/set'));
            }
            elseif(mcv('goods')){
                header('location: '.webUrl('goods'));
            }
            elseif(mcv('order')){
                header('location: '.webUrl('order'));
            }
            elseif(mcv('statistics')){
                header('location: '.webUrl('statistics'));
            }
            elseif(mcv('sale')){
                header('location: '.webUrl('sale'));
            }
            elseif(mcv('perm')){
                header('location: '.webUrl('perm'));
            }
            elseif(mcv('apply')){
                header('location: '.webUrl('apply'));
            }
            elseif(mcv('exhelper')){
                header('location: '.webUrl('exhelper'));
            }
            elseif(mcv('diypage')){
                header('location: '.webUrl('diypage'));
            }
        }
        else
        {
            $user = pdo_fetch('select `id`,`logo`,`merchname`,`desc` from ' . tablename('ewei_shop_merch_user') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $_W['uniaccount']['merchid'], ':uniacid' => $_W['uniacid']));

            //待发货详细信息
            $order_sql ="select id,ordersn,createtime,address,price,invoicename from " . tablename('ewei_shop_order') . " where uniacid = :uniacid and merchid=:merchid and isparent=0 and deleted=0 AND ( status = 1 or (status=0 and paytype=3) ) ORDER BY createtime ASC LIMIT 20";

            $order = pdo_fetchall($order_sql,array(':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid']));

            foreach ($order as &$value)
            {
                $value['address'] = iunserializer($value['address']);
            }
            unset($value);
            $order_ok = $order;

            $merchid = $_W['merchid'];
            $url = mobileUrl('merch', array('merchid' => $merchid), true);
            $qrcode = m('qrcode')->createQrcode($url);

            include $this->template();
        }
    }

    public function ajax()
    {
        global $_W, $_GPC;
        $paras = array(':uniacid' => $_W['uniacid'],':merchid'=>$_W['merchid']);
        //已售罄商品
        $goods_totals = pdo_fetchcolumn(
            'SELECT COUNT(1) FROM ' . tablename('ewei_shop_goods') ." WHERE uniacid = :uniacid and merchid = :merchid and status=1 and deleted=0 and total<=0 and total<>-1  ",
            $paras
        );
        show_json(1,array(
            'goods_totals' => $goods_totals
        ));
    }
}