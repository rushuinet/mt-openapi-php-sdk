<?php

namespace MtOpenApi\Api;


class ProductService extends RequestService
{
    /**
     * 获取分类
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#4.9
     * @param $name
     * @param $pid
     * @return mixed
     */
    public function get_category($shop_id)
    {
        $params = array(
            'app_poi_code'=>$shop_id
        );
        return $this->call('foodCat/list',$params,self::METHOD_GET);
    }

    /**
     * 创建商品分类
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#4.1
     * @param $name
     * @param $pid
     * @return mixed
     */
    public function create_category($shop_id,$name,$old_name,$sort)
    {
        $params = array(
            'app_poi_code'=>$shop_id,
            'category_name_origin'=>$old_name,
            'category_name'=>$name,
            'sequence'=>$sort
        );
        return $this->call('foodCat/update',$params,self::METHOD_POST);
    }

    /**
     * 修改商品分类
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#4.1
     * @param $id
     * @param $name
     * @return mixed
     */
    public function update_category($shop_id,$name,$old_name,$sort)
    {
        $params = array(
            'app_poi_code'=>$shop_id,
            'category_name_origin'=>$old_name,
            'category_name'=>$name,
            'sequence'=>$sort
        );
        return $this->call('foodCat/update',$params,self::METHOD_POST);
    }



    /**
     * 创建商品
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#4.4
     * @param $params
     * @return mixed
     */
    public function create_product($params)
    {
        return $this->call('food/save',$params,self::METHOD_POST);
    }

    /**
     * 修改商品
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#4.4
     * @param $params
     * @return mixed
     */
    public function update_product($params)
    {
        return $this->call('food/save',$params,self::METHOD_POST);
    }

    /**
     * 绑定商品属性
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#4.16
     * @param $shop_id
     * @param $property
     * @return mixed
     */
    public function bind_property($shop_id,$property)
    {
        $params = array(
            'app_poi_code'=>$shop_id,
            'food_property'=>$property
        );
        return $this->call('food/bind/property',$params,self::METHOD_POST);
    }

    /**
     * 删除商品
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#4.5
     * @param $shop_id
     * @param $product_id
     * @return mixed
     */
    public function delete_product($shop_id,$product_id)
    {
        $params = [
            'app_poi_code'=>$shop_id,
            'app_food_code'=>$product_id,
        ];
        return $this->call('food/delete',$params,self::METHOD_POST);
    }

    /**
     *
     * 批量更新售卖状态(上下架)
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#4.19
     * @param $shop_id
     * @param $food_data [{"app_food_code": "abcd135","skus":[{"sku_id":"abcd135"}] },{...}]
     * @param $status   0上架1下架
     * @return mixed
     * @throws \Exception
     */
    public function batch_product_shelf($shop_id,$food_data,$status)
    {
        $params = array(
            'app_poi_code'=>$shop_id,
            'food_data'=>$food_data,
            'sell_status'=>$status,
        );
        return $this->call('food/sku/sellStatus',$params,self::METHOD_POST);
    }


    /**
     * 批量更新商品库存
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#4.13
     * @param $shop_id
     * @param $food_data
     * @return mixed
     */
    public function batch_product_stock($shop_id,$food_data)
    {
        $params = array(
            'app_poi_code'=>$shop_id,
            'food_data'=>$food_data,
        );
        return $this->call('food/sku/stock',$params,self::METHOD_POST);
    }

    /**
     * 上传图片
     * @doc http://mss.sankuai.com/v1/mss_mt_tenant_2230562/static/doc/doc.html#13
     * @param $shop_id
     * @param $list
     * @return mixed
     */
    public function image_upload($shop_id,$img_url)
    {
        $params = array(
            'app_poi_code'=>$shop_id,
            'img_data'=>"@$img_url",
            'img_name'=>basename($img_url),
        );
        return $this->call('image/upload',$params,self::METHOD_POST);
    }

}