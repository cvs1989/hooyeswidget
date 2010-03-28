<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
    <p>邮件模板</p>
    <ul class="subnav">
        <li><span>管理</span></li>
    </ul>
</div>
<div class="tdare info">
    <table width="100%" cellspacing="0" class="dataTable">
        <?php if ($this->_var['mailtemplate']): ?>
        <tr class="tatr1">
            <td  class="firstCell" align="left">模板描述</td>
            <td class="handler">操作</td>
        </tr>
        <?php endif; ?>
        <?php $_from = $this->_var['mailtemplates']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('code', 'mailtemplate');if (count($_from)):
    foreach ($_from AS $this->_var['code'] => $this->_var['mailtemplate']):
?>
        <tr class="tatr2">
            <td class="firstCell" align="left"><?php echo $this->_var['mailtemplate']['description']; ?></td>
            <td class="handler">
            <a href="index.php?app=mailtemplate&amp;act=edit&amp;code=<?php echo $this->_var['code']; ?>">编辑</a>
            </td>
        </tr>
        <?php endforeach; else: ?>
        <tr class="no_data">
            <td colspan="3">没有邮件模板</td>
        </tr>
        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </table>
        <div id="dataFuncs">

    </div>

</div>
<?php echo $this->fetch('footer.html'); ?>
