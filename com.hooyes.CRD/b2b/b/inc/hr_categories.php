<?php

class hrCategories{
	var $db;
	var $unsets;
	var $categories;
	
	function hrCategories(){
		global $db, $_pre;
		$this->db = &$db;
		$this->pre = &$_pre;
		
		$this->catetories = array();
		$this->unsets = array();
	}
	
	function get_one($id){
		return unserialize(@read_file(PHP168_PATH.'b/php168/hrcategories/category_'. $id .'.php'));
	}
	
	function cache_read(){
		if(!empty($this->categories)) return;
		
		if($content = @file_get_contents(PHP168_PATH.'b/php168/hrcategories/categories.php')){
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
		
		$query = $this->db->query("SELECT * FROM {$this->pre}hr_sort ORDER BY order_sort DESC");
		
		while($arr = $this->db->fetch_array($query)){
			$this->categories[$arr['hr_sid']] = array(
				'hr_sid' => $arr['hr_sid'],
				'sup' => $arr['sup'],
				'sname' => $arr['sname'],
				'hot' => $arr['hot']
			);
			$datas[$arr['hr_sid']] = $arr;
		}
		
		foreach($this->categories as $v){
			if($v['sup']){
				$this->categories[$v['sup']]['categories'][] = &$this->categories[$v['hr_sid']];
				$this->unsets[] = $v['hr_sid'];
			}
			write_file(PHP168_PATH.'b/php168/hrcategories/category_'. $v['hr_sid'] .'.php', serialize($datas[$v['hr_sid']]));
		}
		$mix = array(
			'categories' => &$this->categories,
			'unsets' => &$this->unsets
		);
		write_file(PHP168_PATH.'b/php168/hrcategories/categories.php', serialize($mix));
	}
	
	function get_parents($id){
		if(!isset($this->categories[$id])) return array();
		
		$p = $this->categories[$id]['sup'];
		$ps = array();
		while($p){
			array_unshift($ps, $this->categories[$p]);
			unset($ps[0]['categories']);
			$p = $this->categories[$p]['sup'];
		}
		return $ps;
	}
	
	function get_children_ids($id){
		if(!isset($this->categories[$id]) && !isset($this->categories[$id]['categories'])) return array();
		
		$ids = array();
		foreach($this->categories[$id]['categories'] as $v){
			$ids[$v['hr_sid']] = $v['hr_sid'];
			if(isset($v['categories']))
				$ids = $ids + $this->get_children_ids($v['hr_sid']);
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

$hrcategory = new hrCategories();
?>