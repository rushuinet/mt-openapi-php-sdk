<?php

namespace MtOpenApi\Api;


class OrderService extends RequestService
{
    //取消原因
    public $cancelReason = [
        2001=>"APP方商家超时接单",
        2002=>"APP方非顾客原因修改订单",
        2003=>"APP方非顾客原因取消订单",
        2004=>"APP方配送延迟",
        2005=>"APP方售后投诉",
        2006=>"APP方用户要求取消",
        2007=>"APP方其他原因取消（未传code，默认为此原因）",
        2008=>"店铺太忙",
        2009=>"商品已售完",
        2010=>"地址无法配送",
        2011=>"店铺已打烊",
        2012=>"联系不上用户",
        2013=>"重复订单",
        2014=>"配送员取餐慢",
        2015=>"配送员送餐慢",
        2016=>"配送员丢餐、少餐、餐洒",
    ];
    /**
     * 获取订单
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#8.11
     * @param $order_id
     * @return mixed
     */
    public function get_order($order_id)
    {
        $params = array(
            'order_id'=>$order_id
        );
        return $this->call('order/getOrderDetai',$params);
    }

    /** 确认接单
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#8.2
     * @param string $order_id
     * @return mixed
     */
    public function confirm_order($order_id)
    {
        $params = array(
            'order_id'=>$order_id
        );
        return $this->call("order/confirm", $params);
    }

    /** 取消接单
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#8.3
     * @param  string $order_id
     * @return mixed
     */
    public function cancel_order($order_id,$reason,$reason_code)
    {
        $params = array(
            'orderId'=>$order_id,
            'reason'=>$reason,
            'reason_code'=>$reason_code,
        );
        return $this->call("order/cancel", $params);
    }

    /** 同意退单/取消单
     * @param $order_id
     * @return mixed
     */
    public function agree_refund($order_id)
    {
        $params = array(
            'orderId'=>$order_id,
            'isAgreed'=>true,
            'operator'=>'小叮',
        );
        return $this->call("ocs/orderCancelOperate", $params);
    }

    /** 不同意退单/取消单
     * @param string $order_id 订单Id
     * @param string $reason 商家不同意退单原因
     * @return mixed
     */
    public function disagree_refund($order_id, $reason)
    {
        $params = array(
            'order_id'=>$order_id,
            'isAgreed'=>false,
            'operator'=>'小叮',
            'remark'=>$reason,
        );
        return $this->call("ocs/orderCancelOperate", $params);
    }

    /**
     * 开始配送
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#8.4
     * @param $order_id
     * @return \stdClass
     */
    public function began_delivery($order_id)
    {
        $params = array(
            'order_id'=>$order_id,
            'courier_name'=>'小叮'
        );
        return $this->call("order/delivering", $params);
    }

    /** 订单确认送达
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#8.5
     * @param string $order_id
     * @return mixed
     */
    public function received_order($order_id)
    {
        $params = array(
            'order_id'=>$order_id
        );
        return $this->call("order/arrived", $params);
    }


    /**
     * 获取退款详情
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#8.21
     * @param $refund_id
     * @return mixed
     */
    public function get_refund($order_id)
    {
        $params = array(
            'order_id'=>$order_id
        );
        return $this->call('order/getPartRefundFoods',$params);
    }
    /**
     * 同意退款
     * @param $refund_id
     * @return mixed
     */
    public function agreed_refund($refund_id)
    {
        $params = array(
            'serviceOrder'=>$refund_id,
            'approveType'=>1,
            'optPin'=>'小叮',
        );
        return $this->call('afs/afsOpenApprove',$params);
    }
    /**
     * 不同意退款
     * @param $refund_id
     * @return mixed
     */
    public function disagreed_refund($refund_id,$reason)
    {
        $params = array(
            'serviceOrder'=>$refund_id,
            'approveType'=>3,
            'rejectReason'=>$reason,
            'optPin'=>'小叮',
        );
        return $this->call('afs/afsOpenApprove',$params);
    }

}