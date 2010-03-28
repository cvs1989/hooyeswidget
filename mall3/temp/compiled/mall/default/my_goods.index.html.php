<?php echo $this->fetch('member.header.html'); ?>
<script type="text/javascript">
$(function(){
    var t = new EditableTable($('#my_goods'));
});
</script>
<style>
.member_no_records {border-top: 0px !important;}
.table td{padding-left: 5px;}
.table .ware_text {width: 155px;}
</style>
<div class="content">
  <div class="totline"></div>
  <div class="botline"></div>
  <?php echo $this->fetch('member.menu.html'); ?>
  <div id="right">
    <?php echo $this->fetch('member.submenu.html'); ?>
        <div class="wrap">
            <div class="eject_btn_two eject_pos1" title="淘宝助理导入"><b class="ico1" onclick="go('index.php?app=my_goods&amp;act=import_taobao');">淘宝助理导入</b></div>
            <div class="eject_btn_two eject_pos2" title="新增产品"><b class="ico2" onclick="go('index.php?app=my_goods&amp;act=add');">新增产品</b></div>
            <div class="public_select table">
                <table id="my_goods" server="<?php echo $this->_var['site_url']; ?>/index.php?app=my_goods&amp;act=ajax_col" >

                    <tr class="line_bold">
                        <th class="width1"><input type="checkbox" id="all" class="checkall"/></th>
                        <th class="align1" colspan="2">
                            <span class="all"><label for="all">全选</label></span>
                            <a href="#" class="edit" ectype="batchbutton" uri="index.php?app=my_goods&act=batch_edit" name="id">编辑</a>
                            <a href="#" class="delete" ectype="batchbutton" uri="index.php?app=my_goods&act=drop" name="id" presubmit="confirm('您确定要删除它吗？')">删除</a>
                        </th>
                        <th colspan="8">
                            <div class="select_div">
                            <form method="get">
                            <?php if ($this->_var['filtered']): ?>
                            <a class="detlink" style="float:right" href="<?php echo url('app=my_goods'); ?>">取消检索</a>
                            <?php endif; ?>
                            <input type="hidden" name="app" value="my_goods">
                            <select class="select1" name='sgcate_id'>
                                <option value="0">本店分类</option>
                                <?php echo $this->html_options(array('options'=>$this->_var['sgcategories'],'selected'=>$_GET['sgcate_id'])); ?>
                            </select>
                            <select class="select2" name="character">
                                <option value="0">状态</option>
                                <?php echo $this->html_options(array('options'=>$this->_var['lang']['character_array'],'selected'=>$_GET['character'])); ?>
                            </select>
                            <input type="text" class="text_normal" name="keyword" value="<?php echo htmlspecialchars($_GET['keyword']); ?>"/>
                            <input type="submit" class="btn" value="搜索" />
                            </form>
                            </div>
                        </th>
                    </tr>
                    <?php if ($this->_var['goods_list']): ?>
                    <tr class="gray"  ectype="table_header">
                        <th width="30"></th>
                        <th width="55"></th>
                        <th width="165" coltype="editable" column="goods_name" checker="check_required" inputwidth="90%" title="排序"  class="cursor_pointer"><span ectype="order_by">产品名称</span></th>
                        <th width="70" column="cate_id" title="排序"  class="cursor_pointer"><span ectype="order_by">产品分类</span></th>
                        <th width="55" coltype="editable" column="brand" checker="check_required" inputwidth="55px" title="排序"  class="cursor_pointer"><span ectype="order_by">品牌</span></th>
                        <th width="55" class="cursor_pointer" coltype="editable" column="price" checker="check_number" inputwidth="50px" title="排序"><span ectype="order_by">价格</span></th>
                        <th width="55" class="cursor_pointer" coltype="editable" column="stock" checker="check_pint" inputwidth="50px" title="排序"><span ectype="order_by">库存</span></th>
                        <th width="25" coltype="switchable" column="if_show" onclass="right_ico" offclass="wrong_ico" title="排序"  class="cursor_pointer"><span ectype="order_by">上架</span></th>
                        <th width="25" coltype="switchable" column="recommended" onclass="right_ico" offclass="wrong_ico" title="排序"  class="cursor_pointer"><span ectype="order_by">推荐</span></th>
                        <th width="25" column="closed" title="排序" class="cursor_pointer"><span ectype="order_by">禁售</span></th>
                        <th>操作</th>
                    </tr>
                    <?php endif; ?>
                    <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['_goods_f'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['_goods_f']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['_goods_f']['iteration']++;
?>
                    <tr class="line<?php if (($this->_foreach['_goods_f']['iteration'] == $this->_foreach['_goods_f']['total'])): ?> last_line<?php endif; ?>" ectype="table_item" idvalue="<?php echo $this->_var['goods']['goods_id']; ?>">
                        <td width="25" class="align2"><input type="checkbox" class="checkitem" value="<?php echo $this->_var['goods']['goods_id']; ?>"/></td>
                        <td width="50" class="align2"><a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. ''); ?>" target="_blank"><img src="<?php echo $this->_var['site_url']; ?>/<?php echo $this->_var['goods']['default_image']; ?>" width="50" height="50"  /></a></td>
                        <td width="160" align="align2">
                          <p class="ware_text"><span class="color2" ectype="editobj"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></span></p>
                        </td>
                        <td width="65"><span class="color2"><?php echo nl2br($this->_var['goods']['cate_name']); ?></span></td>
                        <td width="50" class="align2"><span class="color2" ectype="editobj"><?php echo htmlspecialchars($this->_var['goods']['brand']); ?></span></td>
                        <td width="50" class="align2"><?php if ($this->_var['goods']['spec_qty']): ?><span ectype="dialog" dialog_width="430" uri="index.php?app=my_goods&amp;act=spec_edit&amp;id=<?php echo $this->_var['goods']['goods_id']; ?>" dialog_id="my_goods_spec_edit" class="cursor_pointer"><?php else: ?><span class="color2" ectype="editobj"><?php endif; ?><?php echo $this->_var['goods']['price']; ?></span></td>
                        <td width="50" class="align2"><?php if ($this->_var['goods']['spec_qty']): ?><span ectype="dialog" dialog_width="430" uri="index.php?app=my_goods&amp;act=spec_edit&amp;id=<?php echo $this->_var['goods']['goods_id']; ?>" dialog_id="my_goods_spec_edit" class="cursor_pointer"><?php else: ?><span class="color2" ectype="editobj"><?php endif; ?><?php echo $this->_var['goods']['stock']; ?></span></td>
                        <td width="20" class="align2"><span style="margin:0px 5px;" ectype="editobj" <?php if ($this->_var['goods']['if_show']): ?>class="right_ico" status="on"<?php else: ?>class="wrong_ico" stauts="off"<?php endif; ?>></span></td>
                        <td width="20" class="align2"><span style="margin:0px 5px;" ectype="editobj" <?php if ($this->_var['goods']['recommended']): ?>class="right_ico" status="on"<?php else: ?>class="wrong_ico" stauts="off"<?php endif; ?>></span></td>
                        <td width="20" class="align2"><span style="margin:0px 5px;" <?php if ($this->_var['goods']['closed']): ?>class="no_ico"<?php else: ?>class="no_ico_disable"<?php endif; ?>></span></td>
                        <td><div><a href="<?php echo url('app=my_goods&act=edit&id=' . $this->_var['goods']['goods_id']. ''); ?>" class="edit">编辑</a>
                            <a  href="javascirpt:;" ectype="dialog" dialog_id="export_ubbcode" dialog_title="导出UBB" dialog_width="380" uri="<?php echo url('app=my_goods&act=export_ubbcode&id=' . $this->_var['goods']['goods_id']. ''); ?>" class="export">导出UBB</a> <a href="javascript:drop_confirm('您确定要删除它吗？', 'index.php?app=my_goods&amp;act=drop&id=<?php echo $this->_var['goods']['goods_id']; ?>');" class="delete">删除</a></div></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td class="align2 member_no_records padding6" colspan="10"><?php echo $this->_var['lang'][$_GET['act']]; ?>没有符合条件的产品</td>
                    </tr>
                    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    <?php if ($this->_var['goods_list']): ?>
                    <tr class="line_bold line_bold_bottom">
                        <td colspan="11">��</td>
                    </tr>
                    <tr>
                        <th><input type="checkbox" id="all2" class="checkall"/></th>
                        <th colspan="10">
                            <p class="position1">
                                <span class="all"><label for="all2">全选</label></span>
                                <a href="#" class="edit" ectype="batchbutton" uri="index.php?app=my_goods&amp;act=batch_edit" name="id">编辑</a>
                                <a href="#" class="delete" uri="index.php?app=my_goods&act=drop" name="id" presubmit="confirm('您确定要删除它吗？')" ectype="batchbutton">删除</a>
                            </p>
                            <p class="position2">
                                <?php echo $this->fetch('member.page.bottom.html'); ?>
                            </p>
                        </th>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
            <div class="wrap_bottom"></div>
        </div>
        <div class="clear"></div>
        <div class="adorn_right1"></div>
        <div class="adorn_right2"></div>
        <div class="adorn_right3"></div>
        <div class="adorn_right4"></div>
    </div>
    <div class="clear"></div>
</div>
<iframe name="iframe_post" id="iframe_post" width="0" height="0"></iframe>
<?php echo $this->fetch('footer.html'); ?>