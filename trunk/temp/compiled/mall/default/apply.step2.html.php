<?php echo $this->fetch('header.html'); ?>
<script src="<?php echo $this->lib_base . "/" . 'mlselection.js'; ?>" charset="utf-8"></script>
<script src="<?php echo $this->lib_base . "/" . 'jquery.plugins/jquery.validate.js'; ?>" charset="utf-8"></script>
<style type="text/css">
.d_inline{display:inline;}
</style>
<div class="content">
<script type="text/javascript">
//<!CDATA[
var SITE_URL = "<?php echo $this->_var['site_url']; ?>";
$(function(){
    regionInit("region");

    $("#apply_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parents('td').next('td');
            error_td.find('.field_notice').hide();
            error_td.find('.fontColor3').hide();
            error_td.append(error);
        },
        success: function(label){
            label.addClass('validate_right').text('OK!');
        },
        onkeyup: false,
        rules: {
            owner_name: {
                required: true
            },
            store_name: {
                required: true,
                remote : {
                    url  : 'index.php?app=apply&act=check_name&ajax=1',
                    type : 'get',
                    data : {
                        store_name : function(){
                            return $('#store_name').val();
                        }
                    }
                }
            },
            tel: {
                required: true,
                minlength:6,
                checkTel:true
            },
            image_1: {
                accept: "jpg|jpeg|png|gif"
            },
            image_2: {
                accept: "jpg|jpeg|png|gif"
            },
            image_3: {
                accept: "jpg|jpeg|png|gif"
            },
            notice: {
                required : true
            }
        },
        messages: {
            owner_name: {
                required: '请输入企业联系人姓名'
            },
            store_name: {
                required: '请输入企业名称',
                remote: '该企业名称已存在，请您换一个'
            },
            tel: {
                required: '请输入联系电话',
                minlength: '电话号码由数字、加号、减号、空格、括号组成,并不能少于6位',
                checkTel: '电话号码由数字、加号、减号、空格、括号组成,并不能少于6位'
            },
            image_1: {
                accept: '请上传格式为 jpg,jpeg,png,gif 的文件'
            },
            image_2: {
                accept: '请上传格式为 jpg,jpeg,png,gif 的文件'
            },
            image_3: {
                accept: '请上传格式为 jpg,jpeg,png,gif 的文件'
            },
            notice: {
                required: '请阅读并同意开店协议'
            }
        }
    });
});
//]]>
</script>
    <div class="module_common">
        <h2><b class="set_up_shop" title="SHOP REGISTRATION我要开店"></b></h2>
        <div class="wrap">
            <div class="wrap_child">

                <div class="module_new_shop">

                    <div class="chart">
                        <div class="pos_x1 bg_a1" title="1. 选择企业类型"></div>
                        <div class="pos_x2 bg_b2" title="2. 填写企业联系人和企业信息"></div>
                        <div class="pos_x3 bg_c" title="3. 完成"></div>
                    </div>

                    <div class="info_shop">
                        <form method="post" enctype="multipart/form-data" id="apply_form">
                        <table>
                            <tr>
                                <th>企业联系人姓名: </th>
                                <td class="width7"><input type="text" class="text width7" name="owner_name"/></td>
                                <td class="padding3"><span class="fontColor3">*</span> <span class="field_notice">请填写真实姓名</span></td>
                            </tr>
                            <tr>
                                <th>身份证号: </th>
                                <td><input type="text" class="text width7" name="owner_card"/></td>
                                <td class="padding3"> <span class="field_notice">请填写真实准确的身份证号</span></td>
                            </tr>
                            <tr>
                                <th>企业名称: </th>
                                <td><input type="text" class="text width7" name="store_name" id="store_name"/></td>
                                <td class="padding3"><span class="fontColor3">*</span> <span class="field_notice">请控制在20个字以内</span></td>
                            </tr>
                            <tr>
                                <th>所属分类: </th>
                                <td>
                                    <div class="select_add"><select name="cate_id">
                                    <option value="0">请选择...</option>
                                    <?php echo $this->html_options(array('options'=>$this->_var['scategories'])); ?>
                                    </select>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>所在地区: </th>
                                <td>
                                <div class="select_add" id="region" style="width:500px;border:1px solide red;">
                                    <input type="hidden" name="region_id" value="0" class="mls_id" />
                                    <input type="hidden" name="region_name" value="" class="mls_names" />
                                    <select class="d_inline">
                                    <option value="0">请选择...</option>
                                    <?php echo $this->html_options(array('options'=>$this->_var['regions'])); ?>
                                    </select>
                                </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>详细地址: </th>
                                <td><input type="text" class="text width7" name="address"/></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>邮政编码: </th>
                                <td><input type="text" class="text width7" name="zipcode"/></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>联系电话: </th>
                                <td>
                                    <input type="text" class="text width7" name="tel" />
                                </td>
                                <td class="padding3"><span class="fontColor3">*</span> <span class="field_notice">请输入联系电话</span></td>
                            </tr>
                            <tr>
                                <th>上传证件: </th>
                                <td><input type="file" name="image_1" /></td>
                                <td class="padding3"><span class="field_notice">支持格式jpg,jpeg,png,gif，请保证图片清晰且文件大小不超过400KB</span></td>
                            </tr>
                            <tr>
                                <th>上传执照: </th>
                                <td><input type="file" name="image_2" /></td>
                                <td class="padding3"><span class="field_notice">支持格式jpg,jpeg,png,gif，请保证图片清晰且文件大小不超过400KB</span></td>
                            </tr>
                            <tr>
                                <td colspan="2"><p class="padding4"><input type="checkbox" name="notice" value="1" id="warning" /> <label for="warning">我已认真阅读并完全同意<a href="index.php?app=article&act=system&code=setup_store" target="_blank">开店协议</a>中的所有条款</label></p></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3"><p class="padding4"><input class="btn" type="submit" value="" /></p></td>
                            </tr>
                        </table>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>
<?php echo $this->fetch('footer.html'); ?>
