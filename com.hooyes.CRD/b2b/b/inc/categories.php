<?php

class bCategories{
	var $db;
	var $unsets;
	var $categories;
	
	function bCategories(){
		global $db, $_pre;
		$this->db = &$db;
		$this->pre = &$_pre;
		
		$this->catetories = array();
		$this->unsets = array();
	}
	
	function get_one($id){
		return unserialize(@read_file(PHP168_PATH.'b/php168/bcategories/category_'. $id .'.php'));
	}
	
	function cache_read(){
		if(!empty($this->categories)) return;
		
		if($content = @file_get_contents(PHP168_PATH.'b/php168/bcategories/categories.php')){
			$info = unserialize($content);
			$this->categories = $info['categories'];
			$this->unsets = $info['unsets'];
			unset($info);
		}else{
			$this->cache_write();
			$this->cache_read();
		}
	}
	
	function cache_write(){
		$this->categories = array();
		$this->unsets = array();
		$datas = array();
		
		$query = $this->db->query("SELECT * FROM {$this->pre}sort ORDER BY list DESC");
		
		while($arr = $this->db->fetch_array($query)){
			$this->categories[$arr['fid']] = array(
				'fid' => $arr['fid'],
				'fup' => $arr['fup'],
				'mid' => $arr['mid'],
				'name' => $arr['name'],
				'best' => $arr['best']
			);
			$datas[$arr['fid']] = $arr;
		}
		
		foreach($this->categories as $v){
			if($v['fup']){
				$this->categories[$v['fup']]['categories'][] = &$this->categories[$v['fid']];
				$this->unsets[] = $v['fid'];
			}
			write_file(PHP168_PATH.'b/php168/bcategories/category_'. $v['fid'] .'.php', serialize($datas[$v['fid']]));
		}
		unset($datas);
		$mix = array(
			'categories' => &$this->categories,
			'unsets' => &$this->unsets
		);
		write_file(PHP168_PATH.'b/php168/bcategories/categories.php', serialize($mix));
	}
	
	function get_parents($id){
		if(!isset($this->categories[$id])) return array();
		
		$p = $this->categories[$id]['fup'];
		$ps = array();
		while($p){
			array_unshift($ps, $this->categories[$p]);
			unset($ps[0]['categories']);
			$p = $this->categories[$p]['fup'];
		}
		return $ps;
	}
	
	function get_children_ids($id){
		if(!isset($this->categories[$id]) && !isset($this->categories[$id]['categories'])) return array();
		
		$ids = array();
		foreach($this->categories[$id]['categories'] as $v){
			$ids[$v['fid']] = $v['fid'];
			if(isset($v['categories']))
				$ids = $ids + $this->get_children_ids($v['fid']);
		}
		
		return $ids;
	}
	
	function unsets($return = false){
		if($return){
			$categories = $this->categories;
			foreach($this->unsets as $v) unset($categories[$v]);
			return $categories;
		}else{
			foreach($this->unsets as $v) unset($this->categories[$v]);
		}
	}
	
	function key_reset(&$cs){
		$c = array();
		$i = 0;
		foreach($cs as $v)
			$c[$i++] = $v;
		return $c;
	}
	
}

$bcategory = new bCategories();
?>