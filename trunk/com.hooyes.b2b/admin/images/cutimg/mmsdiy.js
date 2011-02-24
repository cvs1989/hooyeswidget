	function checkBrowser()
	{
		this.ver=navigator.appVersion;
		this.dom=document.getElementById?1:0;
//ie7 can not return the full path, so...
		this.ie6=(this.ver.indexOf("MSIE 6")>-1 && this.dom)?1:0;
		this.ie5=(this.ver.indexOf("MSIE 5")>-1 && this.dom)?1:0;
		this.ie4=(document.all && !this.dom)?1:0;
		this.ns5=(this.dom && parseInt(this.ver) >= 5) ?1:0;
		this.ns4=(document.layers && !this.dom)?1:0;
		this.mac=(this.ver.indexOf('Mac') > -1) ?1:0;
		this.ope=(navigator.userAgent.indexOf('Opera')>-1);
		this.ffox=(navigator.userAgent.indexOf('Firefox')>-1);
		this.maxthon=(navigator.userAgent.indexOf('Maxthon')>-1);
		this.ie=(this.ie6 || this.ie5 || this.ie4);
		this.ns=(this.ns4 || this.ns5);
		this.bw=(this.ie6 || this.ie5 || this.ie4 || this.ns5 || this.ns4 || this.mac || this.ope || this.ffox || this.maxthon);
		this.nbw=(!this.bw);
		return this;
	}

	function SelectNumber(snd)
	{
		temp=snd.NumberList.options[snd.NumberList.selectedIndex].value;
		phone = temp.split("|");
		snd.ToNum.value=phone[0];
		PhoneChangeBrand(document.all.BRAND, document.all.PHONE, phone[1]);
	}

	function Validate(aForm)
	{
		if ("object"==typeof(aForm.UserNum) && aForm.UserNum.value.length!=11)
		{
			alert("请填入您的手机号码！");
			aForm.UserNum.focus();
			return (false);
		}

		if ("object"==typeof(aForm.UserPwd) && aForm.UserPwd.value.length==0)
		{
			alert("请输入正确的登录密码！");
			aForm.UserPwd.focus();
			return (false);
		}

		if ("object"==typeof(aForm.ToNum) && aForm.ToNum.value.length!=0 && aForm.ToNum.value.length!=11)
		{
			alert("请填入正确的接收手机号码！");
			aForm.ToNum.focus();
			return (false);
		}

	/*
	   if (aForm.PHONE.value=="0")
	   {
		 alert("请选择您的手机型号！");
		 aForm.PHONE.focus();
		 return (false);
	   }
	*/
		var expiration = new Date((new Date()).getTime() + 60*(24*60*60*1000));

		if ("object"==typeof(aForm.SendType[0]))
		{
			for (i=0; i<aForm.SendType.length; i++)
			{
				if (aForm.SendType[i].checked)
				{
					setCookie("SavedSendMethod", aForm.SendType[i].value, expiration);
				}
			}
		}
		else if ("object"==typeof(aForm.SendType))
		{
			setCookie("SavedSendMethod", aForm.SendType.value, expiration);
		}

		if ("object"==typeof(aForm.pid))
		{
			aForm.pid.value = aForm.PHONE.value;
		}

		if (("object"==typeof(aForm.SaveNum)) && (aForm.SaveNum.checked == true))
		{
			if (("object"==typeof(aForm.UserNum)))
			{
				setCookie("SavedUserNum", aForm.UserNum.value, expiration);
			}

			if ("object"==typeof(aForm.ToNum))
			{
				var SavedToNum = getCookie("SavedToNum");
				var this_cookie_str_1 = "";
				var this_cookie_str_2 = "";
				var has_it = 0;
				if (SavedToNum && (SavedToNum != "null") && (SavedToNum != "undefined"))
				{
					var cookie_arr = SavedToNum.split(",");
					for (i=0;i<cookie_arr.length;i++)
					{
						if (cookie_arr[i])
						{
							var cookie_arr_2 = cookie_arr[i].split("|");
							if (cookie_arr_2[0]==aForm.ToNum.value)
							{
								if (cookie_arr_2[1] == aForm.PHONE.value)
								{
									has_it = 1;
									break;
								}
								else
								{
									this_cookie_str_1 = cookie_arr_2[0]+"|"+aForm.PHONE.value+",";
								}
							}
							else
							{
								this_cookie_str_2 = this_cookie_str_2 + cookie_arr[i]+",";
							}
						}
					}
					if (!has_it)
					{
						if (!this_cookie_str_1)
						{
							this_cookie_str_1 = aForm.ToNum.value+"|"+aForm.PHONE.value+",";
						}
						this_cookie_str = this_cookie_str_1+this_cookie_str_2;
						setCookie("SavedToNum", this_cookie_str.substring(0, (this_cookie_str.length-1)), expiration);
					}
				}
				else
				{
					setCookie("SavedToNum", aForm.ToNum.value+"|"+aForm.PHONE.value, expiration);
				}
				setCookie("SavedLastToNum", aForm.ToNum.value+"|"+aForm.PHONE.value, expiration);
			}
		}
		else
		{
			setCookie("SavedUserNum");
			setCookie("SavedToNum");
		}
		return (true);
	}

	function setCookie(name, value, expires)
	{
		if (name == '')
		{
			return null;
		}
		var exp = "";
		if (expires != null)
		{
			exp = '; expires =' + expires.toGMTString();
		}
		document.cookie = name + '=' + escape(value) + '; path=/' + exp;
	}

	function getCookie(name)
	{
		if (name == '')
		{
			return 0;
		}
		var cname = name + '=';
		var dc = document.cookie;
		if (dc.length > 0)
		{
			var begin = dc.indexOf(cname);
			if (begin != -1)
			{
				begin += cname.length;
				var end = dc.indexOf(";", begin);
				if (end == -1)
				{
					end = dc.length;
				}
				return unescape(dc.substring(begin, end));
			}
		}
		return null;
	}

	function showname()
	{
		if (event.button==2)
		{
			return false;
		}
	}   
	function keypress()
	{
		if(event.keyCode==78 && event.ctrlKey)
			return false;
	}
	function contextmenu()
	{
		return false;
	}
	function fixpwdnew(usrname)
	{
		window.open('http://mms.sohu.com/user/fixpwd.php?num=' + usrname,'','top=50,left=200,width=500,height=450,scrollbars=no,resizable=no,center=yes');
	}

	function popup_w(url, force)
	{
		var is_popup = getCookie("POPUPMMSDIY");
		if (is_popup != "Y")
		{
	//3 hours
			if (force)
			{
				window.showModalDialog("pp.html", "", "dialogwidth:1px;dialogheight:1px");
			}
			else
			{
				window.open(url);
			}
			var expiration = new Date((new Date()).getTime() + 3*60*60*1000);
			setCookie("POPUPMMSDIY", "Y", expiration);
		}
	}

	function focusthis()
	{
	   if ("object"==typeof(document.snd.UserNum))
	   {
			document.snd.UserNum.focus();
	   }
	   else if ("object"==typeof(document.snd.UserPwd))
	   {
			document.snd.UserPwd.focus();
	   }
	   else if ("object"==typeof(document.snd.ToNum))
	   {
			document.snd.ToNum.focus();
	   }
	   else
	   {
			document.all.sendit.focus();
	   }
	}

	function select_element_add(select_name, elem_text, elem_val, is_selected)
	{
		var already_add = 0;
		var select_length = select_name.length;
		var i = 0;
		for (i=0; i<select_length; i++)
		{
			if (select_name.options[i].value == elem_val)
			{
				already_add = 1;
				break;
			}
		}
		if (!already_add)
		{
			var oOption = document.createElement("OPTION");
			select_name.options.add(oOption);
			oOption.innerText = elem_text;
			oOption.value = elem_val;
			if (is_selected)
			{
				select_name.options[select_length].selected = true;
			}
		}
	}

function bookmark(is_click)
{
	var is_book = getCookie("BOOKMARKMMSDIY");
	url = "http://diy.mms.sohu.com/"; 
	title = "搜狐无线-DIY";
	if (is_book != "Y")
	{
//7 days
		var expiration = new Date((new Date()).getTime() + 7*(24*60*60*1000));
		setCookie("BOOKMARKMMSDIY", "Y", expiration);
		window.external.AddFavorite(url, title);
	}
	else
	{
		if (is_click)
		{
			window.external.AddFavorite(url, title);
//			alert(title+"\n\n已经放入您的收藏夹!");
		}
	}
}

	function ValidateDIY(aForm)
	{
	   if (aForm.elements['uploadtype'][1].checked)
	   {
			if (!aForm.picurl.value || (aForm.picurl.value=="http://"))
			{
				 alert("请输入你要发送的图片的网络地址！");
				 aForm.picurl.focus();
				 return (false);
			 }
			 else
		   {
				aForm.uploadfile.value=aForm.picurl.value;
		   }
	   }
	   else if (aForm.elements['uploadtype'][0].checked)
	   {
			if (!aForm.picfile.value)
			{
				 alert("请输入你电脑中的本地图片！");
				 aForm.picfile.focus();
				 return (false);
			 }
			 else
		   {
				aForm.uploadfile.value=aForm.picfile.value;
		   }
	   }
	   else if (aForm.elements['uploadtype'][2].checked)
	   {
			aForm.uploadfile.value=aForm.picurl.value;
	   }
	/*
	   if ("object"==typeof(aForm.Key) && !aForm.Key.value)
	   {
			 alert("请输入系统保护码！");
			 aForm.Key.focus();
			 return (false);
	   }
	*/
	   return (true);
	}
	function showdiybook()
	{
		window.open('http://img.mms.sohu.com/i/diy/images/350x250.jpg', 'diybook', 'toolbar=no,width=370,height=270,resizable=no,scrollbars=no');
	}

	function focusthis()
	{
	   if ("object"==typeof(document.snd.UserNum))
	   {
			document.snd.UserNum.focus();
	   }
	   else if ("object"==typeof(document.snd.UserPwd))
	   {
			document.snd.UserPwd.focus();
	   }
	   else if ("object"==typeof(document.snd.ToNum))
	   {
			document.snd.ToNum.focus();
	   }
	   else
	   {
			document.all.sendit.focus();
	   }
	}
