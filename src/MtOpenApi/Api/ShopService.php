<?php

namespace MtOpenApi\Api;


class ShopService extends RequestService
{
    /**
     * 门店ID
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#2.2
     * @return mixed
     */
    public function get_shop_ids()
    {
        $params = array();
        return $this->call('poi/getids',$params,self::METHOD_GET);
    }
    /**
     * 店铺列表
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#2.3
     * @param string $shop_ids  APP方门店id(半角逗号分隔)
     * @return mixed
     */
    public function get_shop_list($shop_ids)
    {
        $params = array(
            'app_poi_codes'=>$shop_ids
        );
        return $this->call('poi/mget',$params,self::METHOD_GET);
    }


    /**
     * 创建店铺
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#2.1
     * @param $params
     * @return mixed
     */
    public function create_shop($params)
    {
        return $this->call('poi/save',$params,self::METHOD_POST);
    }

    /**
     * 更新店铺
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#2.1
     * @param $params
     * @return mixed
     */
    public function update_shop($params)
    {
        return $this->call('poi/save',$params,self::METHOD_POST);
    }

    /**
     * 删除店铺
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#2.1
     * @param $params
     * @return mixed
     */
    public function delete_shop($params)
    {
        return $this->call('poi/save',$params,self::METHOD_POST);
    }

    /**
     * 店铺品类
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#2.3
     * @param string $shop_ids  APP方门店id(半角逗号分隔)
     * @return mixed
     */
    public function get_tag()
    {
        $params = array(
            //'app_poi_codes'=>$shop_ids
        );
        return $this->call('poiTag/list',$params,self::METHOD_GET);
    }

}