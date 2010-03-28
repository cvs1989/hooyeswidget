<?php echo $this->fetch('member.header.html'); ?>
<div class="content">
    <div class="particular">
        <div class="particular_wrap">
            <p class="<?php if ($this->_var['icon'] == "notice"): ?>success<?php else: ?>defeated<?php endif; ?>">
                <span></span>
                <b><?php echo $this->_var['message']; ?></b>
                <?php if ($this->_var['err_file']): ?>
                <b style="clear: both; float: left; font-size: 15px;">Error File: <strong><?php echo $this->_var['err_file']; ?></strong> at <strong><?php echo $this->_var['err_line']; ?></strong> line.</b>
                <?php endif; ?>
            </p>
        </div>
        <div class="clear"></div>
        <div class="adorn_right1"></div>
        <div class="adorn_right2"></div>
        <div class="adorn_right3"></div>
        <div class="adorn_right4"></div>
    </div>
    <div class="clear"></div>
</div>
<script type="text/javascript">
//<!CDATA[
<?php if ($this->_var['redirect']): ?>
window.setTimeout("<?php echo $this->_var['redirect']; ?>", 1000);
<?php else: ?>
window.setTimeout("javascript:history.back()", 3000);
<?php endif; ?>
//]]>
</script>
<?php echo $this->fetch('footer.html'); ?>