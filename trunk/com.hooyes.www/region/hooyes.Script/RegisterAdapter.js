// Created By hooyes 2009-9-28
     var citys=json;
     var Index;
     var Index2;
     var SelectProvinceId="Select1";  //1级 :省级下拉菜单ID
     var SelectCityId="Select2";      //2级 :市级
     var SelectCountyId="Select3";    //3级 :县级
     $(function(){
     // InitSelector(2594,6328,6374); 
    InitProvince();
    $('#'+SelectProvinceId).change(function(){hooyeschange(this)});
    $('#'+SelectCityId).change(function(){hooyeshooyeschange2(this)});
    });
    function InitProvince(){
    var ProvinceId=SelectProvinceId;
    $.each(citys,function(i,n){
    var newChild=document.createElement("option");
    newChild.value=n.value;
    newChild.innerText=n.treeNode;
    newChild.textContent=n.treeNode;
    document.getElementById(ProvinceId).appendChild(newChild);
     });    
    }
    function hooyeschange(obj){
     Index=obj.options.selectedIndex;
     var CityId=SelectCityId;
     removeCity();
     removeCounty();
     $.each(citys[Index].childNode,function(i,n){
    var newChild=document.createElement("option");
    newChild.value=n.value;
    newChild.innerText=n.treeNode;
    newChild.textContent=n.treeNode;
    document.getElementById(CityId).appendChild(newChild);
     });
     //3
     var obj=document.getElementById(SelectCityId);
     hooyeshooyeschange2(obj);
    }
    function hooyeshooyeschange2(obj){
     Index2=obj.options.selectedIndex;
     var CityId=SelectCountyId;
    removeCounty();
    $.each(citys[Index].childNode[Index2].childNode,function(i,n){
    var newChild=document.createElement("option");
    newChild.value=n.value;
    newChild.innerText=n.treeNode;
    newChild.textContent=n.treeNode;
    document.getElementById(CityId).appendChild(newChild);
     });
    }
    function removeCity(){ $('#'+SelectCityId+">option").remove();}
    function removeCounty(){ $('#'+SelectCountyId+">option").remove();}
    function groupSetting(){
    var a=$('#'+SelectProvinceId).val();
    var b=$('#'+SelectCityId).val();
    var c=$('#'+SelectCountyId).val();
    $('#UserGroupId').val(c||b||a);
    $('#UserRootGroupId').val(a);
   // $('#xxx').html($('#form1').serialize());
    }
    function regionCombine(){
    var returnVal="";
    var a=document.getElementById(SelectProvinceId);
    var b=document.getElementById(SelectCityId);
    var c=document.getElementById(SelectCountyId);
    var aText=(a.options.length) ? a.options[a.options.selectedIndex].text : "";
    var bText=(b.options.length) ? "|"+b.options[b.options.selectedIndex].text : "";
    var cText=(c.options.length) ? "|"+c.options[c.options.selectedIndex].text : "";
    returnVal=aText+bText+cText;
    return returnVal;
    }
    function InitSelector(ProvinceValue,CityValue,CountyValue){
	  Sel(SelectProvinceId,ProvinceValue); hooyeschange(document.getElementById(SelectProvinceId))
	  Sel(SelectCityId,CityValue);hooyeshooyeschange2(document.getElementById(SelectCityId))
	  Sel(SelectCountyId,CountyValue);
	   function Sel(x,v){
	   var o= document.getElementById(x);for(var i=0;i<o.options.length;i++){if(o.options[i].value==v){ o.options[i].selected=true; }}}
	 }
