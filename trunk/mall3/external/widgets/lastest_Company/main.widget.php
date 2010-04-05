<?php

/**
 * 最新企业
 *
 */
class lastest_CompanyWidget extends BaseWidget
{
    var $_name = 'lastest_Company';
     var $_ttl  = 1;
    function _get_data()
    {
 if (empty($this->options['num']) || intval($this->options['num']) <= 0)
        {
            $this->options['num'] = 5;
        }

        $cache_server =& cache_server();
        $key = $this->_get_cache_id();
        $data = $cache_server->get($key);
        if($data === false)
        {
            $order_goods_mod =& m('store');
            $data = $order_goods_mod->find(array(
                'conditions'=>'state = 1',
                'order' => ' store_id desc',
                'fields' => 'store_name',
                'limit' =>  $this->options['num'],
            ));
/*            foreach ($data as $key => $goods)
            {
                empty($goods['goods_image']) && $data[$key]['goods_image'] = Conf::get('default_goods_image');
            }*/
            $cache_server->set($key, $data, $this->_ttl);
        }

        return $data;
    }
    function _new_stores($num)
    {
        $store_mod =& m('store');
        $goods_mod =& m('goods');
        $stores = $store_mod->find(array(
            'conditions' => 'state = 1',
            'order' => 'add_time DESC',
            'join'  => 'belongs_to_user',
            'limit' => '0,' . $num,
        ));
        foreach ($stores as $key => $store){
            empty($store['store_logo']) && $stores[$key]['store_logo'] = Conf::get('default_store_logo');
            $stores[$key]['goods_count'] = $goods_mod->get_count_of_store($store['store_id']);
        }

        return $stores;
    }
   
}

?>