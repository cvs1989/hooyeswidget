	var defminval = 0;
	var defmaxval = 200;
	var defaa = 101;
	var defbb = 202;
	var defcc = defaa - defbb;
	var deforileft = Math.floor((100 - defminval)/(defmaxval-defminval)*defbb - defaa);
	var defminbrightval = -100;
	var defmaxbrightval = 100;
	var defmincontrastval = -100;
	var defmaxcontrastval = 100;
	var defbrightleft = Math.floor((0 - defminbrightval)/(defmaxbrightval-defminbrightval)*defbb - defaa);
	var defbrightorileft = Math.floor((100 - defminbrightval)/(defmaxbrightval-defminbrightval)*defbb - defaa);
	var defcontrastleft = Math.floor((0 - defmincontrastval)/(defmaxcontrastval-defmincontrastval)*defbb - defaa);
	var defcontrastorileft = Math.floor((100 - defmincontrastval)/(defmaxcontrastval-defmincontrastval)*defbb - defaa);
	var o_w = 0;
	var o_h = 0;
	var img_width = 0;
	var img_height = 0;
	var a_r = 100;

	var SELECTTAG = "rectangle_style";
	var RESIZETAG = "handler_style";
	var RESIZETAGW = 6;
	var RESIZETAGH = 6;
	var MINWIDTH = 5;
	var MINHEIGHT = 5;
	var LOCX = 0;
	var LOCY = 0;
	var DEFSELPicEditW = 100;
	var DEFSELPicEditH = 100;
//	var currentImage = new Image();
	var dragApproved = false;
	var originX = 100, originY = 100;

	var bgimg =  document.getElementById("bgImage");
	var cvimg =  document.getElementById("CoverPic");
	var sclval =  document.getElementById("ScaleVal");
	var sclbar =  document.getElementById("ScaleBar");
//	var document.all.scale =  document.getElementById("scale");
	var szdesc =  document.getElementById("SizeDesc");
	var sdiv =  document.getElementById("ShowDiv");
	var hdiv =  document.getElementById("HandlerDiv");
//	var document.all.x =  document.getElementById("x");
//	var document.all.y =  document.getElementById("y");
	var piceditbd =  document.getElementById("PicEditborder");
	var sltpicedit =  document.getElementById("selectPicEdit");
	var picedit =  document.getElementById("PicEdit");
	var edtr =  document.getElementById("EditTR");
	var prtr =  document.getElementById("PrevTR");


	var ie=document.all
	var ns6=document.getElementById&&!document.all


	var LastScale = 100;
	function InitResizeTag()
	{
		hdiv.style.cursor = "SE-resize";
		hdiv.style.border = "1 solid black";
		hdiv.style.position = "absolute";
		hdiv.title = "Resize";
		hdiv.style.left = LOCX + DEFSELPicEditW - RESIZETAGW;
		hdiv.style.top = LOCY + DEFSELPicEditH - RESIZETAGH;
		hdiv.style.width = RESIZETAGW;
		hdiv.style.height = RESIZETAGH;
	}

	function ImageScale(ScaleRate)
	{
		cvimg.width = o_w*(ScaleRate/100);
		cvimg.height = o_h*(ScaleRate/100);
		bgimg.width = o_w*(ScaleRate/100);
		bgimg.height = o_h*(ScaleRate/100);

/*
		w = parseInt(piceditbd.style.width)*(ScaleRate/LastScale);
		h = parseInt(piceditbd.style.height)*(ScaleRate/LastScale);
		rrate = w/h
		AdjustSelPicEdit("wh",w,h,rrate);
*/
		y = document.all.y.value*(ScaleRate/LastScale);
		x = document.all.x.value*(ScaleRate/LastScale);
/*
		if(y < 0)
			y = 0;
		if(y + eval(PicEdit.h.value) >= cvimg.height)
			y = cvimg.height - eval(PicEdit.h.value);
		if(x < 0)
			x = 0;
		if(x + eval(PicEdit.w.value) >= cvimg.width)
			x = cvimg.width - eval(PicEdit.w.value);
*/
		AdjustSelPicEdit("xy", x, y, 0);

		LastScale = ScaleRate;
	}

	function InitWorkImage()
	{
		cvimg.style.position = "absolute";
		cvimg.style.top = 0;
		cvimg.style.left = 0;
		cvimg.style.visibility="hidden";
		bgimg.style.visibility="hidden";
	}

	function InitPicEditborder()
	{
		piceditbd.style.position = "absolute";
		piceditbd.style.width = DEFSELPicEditW;
		piceditbd.style.height = DEFSELPicEditH;
		piceditbd.style.left = 0;
		piceditbd.style.top = 0;
		piceditbd.style.border = "1 solid white";
	}

	function InitSelectPicEdit()
	{
		sltpicedit.style.position = "absolute";
		sltpicedit.style.overflow = "hidden";
		sltpicedit.style.clip = "rect(0 100 100 0)";
		sltpicedit.style.width = DEFSELPicEditW;
		sltpicedit.style.height = DEFSELPicEditH;
		sltpicedit.style.left = LOCX;
		sltpicedit.style.top = LOCY;
	}

	function onMouseDrags(e)
	{
		if (!ie && !ns6)
			return;
		var ele=ns6? e.target : event.srcElement
		var topelement=ns6? "HTML" : "BODY"

		while ((ele.tagName!=topelement) && (ele.className != SELECTTAG) && (ele.className != RESIZETAG))
		{
			ele=ns6? ele.parentNode : ele.parentElement
		}

		tx=ns6? e.clientX: event.clientX
		ty=ns6? e.clientY: event.clientY
		switch(ele.className.toString())
		{
			case SELECTTAG:
				dragApproved = true;
				originX = eval(picedit.x.value) - tx;
				originY = eval(picedit.y.value) - ty;
				document.onmousemove = onSelPicEditMove;
				return false;
				break;
			case RESIZETAG:
				dragApproved=true;
				originX = eval(picedit.w.value) - tx;
				originY = eval(picedit.h.value) - ty;
				document.onmousemove = onSelPicEditResize;
				return false;
				break;
		}
	}

	function onSelPicEditMove(e)
	{
		if (canmove)
		{
	//freeze
	/*
			if(dragApproved)
			{
				var selectPicEdit = sltpicedit;
				x = originX + event.clientX;
				if(x < 0)
					x = 0;
				//if(x >= cvimg.width)
				//	x = cvimg.width - MINWIDTH;
				//if((x + eval(PicEdit.w.value - 1)) > cvimg.width)
				//	x = cvimg.width - eval(PicEdit.w.value);
				//alert(PicEdit.w.value);
				if(x + eval(PicEdit.w.value) >= cvimg.width)
					x = cvimg.width - eval(PicEdit.w.value);
				y = originY + event.clientY;
				if(y < 0)
					y = 0;
				//if(y >= cvimg.height)
				//	y = cvimg.height - MINWIDTH;
				//if((y + eval(PicEdit.h.value - 1)) > cvimg.height)
				//	y = cvimg.height - eval(PicEdit.h.value);
				if(y + eval(PicEdit.h.value) >= cvimg.height)
					y = cvimg.height - eval(PicEdit.h.value);
				AdjustSelPicEdit("xy", x, y, 0);
				return false;
			}
			return true;
	*/
			if (dragApproved)
			{
				var selectPicEdit = sltpicedit;
				tx=ns6? e.clientX: event.clientX
				ty=ns6? e.clientY: event.clientY
				x = originX + tx;
				if(x < 0)	x = 0;
				if(x >= cvimg.width) x = cvimg.width - 2;
				y = originY + ty;
				if(y < 0)	y = 0;
				if(y >= cvimg.height) y = cvimg.height - 2;
				AdjustSelPicEdit("xy",x,y,0);
				return false;
			}
			return true;
		}
		else
		{
			return true;
		}
	}

	function onSelPicEditResize(e)
	{
		var rrate = 0;
		tx=ns6? e.clientX: event.clientX
		ty=ns6? e.clientY: event.clientY
		if(dragApproved)
		{
			w = originX + tx;
			if(w <= 0)
				w = 1;
//			if(w >= cvimg.width)
//				w = cvimg.width - eval(PicEdit.w.value);
			h = originY + ty;
			if(h <= 0)
				h = 1;
//			if(h >= cvimg.height)
//				h = cvimg.height - eval(PicEdit.h.value);
			rrate = w/h;
			AdjustSelPicEdit("wh",w,h,rrate);
			return false;
		}
		return true;
	}

	function AdjustSelPicEdit(field, xw, yh, whr)
	{
		var x=0, y=0,zw=0, zh=0;
		switch(field)
		{
			case "xy":
				picedit.x.value = x = xw;
				picedit.y.value = y = yh;
				break;
			case "whr":
				if(xw > 0)
					picedit.w.value = xw;
				if(yh > 0)
					picedit.h.value = yh;
				picedit.whr.value = whr;
				break;
			case "wh":
				picedit.w.value = xw;
				if(eval(picedit.whr.value) > 0)
					picedit.h.value = Math.round(xw/eval(picedit.whr.value));
				else
					picedit.h.value = yh;
				break;
		}

		x = eval(picedit.x.value);
		y = eval(picedit.y.value);
		zw = Math.floor(parseInt(eval(picedit.w.value) + x));
		zh = Math.floor(parseInt(eval(picedit.h.value) + y));
		if ((zw - x) > 0)
		{
			piceditbd.style.width = zw - x;
		}
		if ((zh - y) > 0)
		{
			piceditbd.style.height = zh - y;
		}
		piceditbd.style.left = x;
		piceditbd.style.top = y;
		sltpicedit.style.width = zw;
		sltpicedit.style.height = zh;
		hdiv.style.left = LOCX + zw - 1;
		hdiv.style.top = LOCY + zh;
		sltpicedit.style.clip = "rect("+ y +"," + zw + "," + zh + ","+ x +")";
	}




	function SubmitOid(iid)
	{
		picedit.oid.value=iid;
		picedit.submit();
	}

	function SubmitSend()
	{
		picedit.ActionGo.value="Send";
		picedit.submit();
	}

	function SubmitAni()
	{
		picedit.ActionGo.value="SendAni";
		picedit.submit();
	}

	function SubmitAction(Action)
	{
		picedit.ActionGo.value=Action;
		picedit.submit();
	}

	function ModifyFrame(frame, action)
	{
		picedit.ActionGo.value=action;
		picedit.CurrFrame.value = frame;
		picedit.submit();
	}

	function ShowBright()
	{
		if (typeof(document.all.contrastdiv) == "object")
		{
			if (document.all.brightdiv.style.visibility != "visible")
			{
				if (typeof(document.all.sizediv) == "object")
				{
					document.all.sizediv.style.visibility="hidden";
				}
				if (typeof(document.all.contrastdiv) == "object")
				{
					document.all.contrastdiv.style.visibility="hidden";
				}
				document.all.brightdiv.style.visibility="visible";
			}
			else
			{
				if (typeof(document.all.sizediv) == "object")
				{
					document.all.sizediv.style.visibility="visible";
				}
				if (typeof(document.all.contrastdiv) == "object")
				{
					document.all.contrastdiv.style.visibility="hidden";
				}
				document.all.brightdiv.style.visibility="hidden";
			}
		}
	}

	function ShowContrast()
	{
		if (typeof(document.all.contrastdiv) == "object")
		{
			if (document.all.contrastdiv.style.visibility != "visible")
			{
				if (typeof(document.all.sizediv) == "object")
				{
					document.all.sizediv.style.visibility="hidden";
				}
				if (typeof(document.all.brightdiv) == "object")
				{
					document.all.brightdiv.style.visibility="hidden";
				}
				document.all.contrastdiv.style.visibility="visible";
			}
			else
			{
				if (typeof(document.all.sizediv) == "object")
				{
					document.all.sizediv.style.visibility="visible";
				}
				if (typeof(document.all.brightdiv) == "object")
				{
					document.all.brightdiv.style.visibility="hidden";
				}
				document.all.contrastdiv.style.visibility="hidden";
			}
		}
	}



	function dragHandler(obj)
	{
		var dragobj = event.srcElement;
		if( event.type == "dragstart")
		{
			offx = dragobj.style.pixelLeft - event.clientX;
		}
		else if( event.type == "drag" )
		{
			dragobj.style.left = offx + event.clientX;
			if(dragobj.style.pixelLeft< -defaa) dragobj.style.pixelLeft = -defaa;
			if(dragobj.style.pixelLeft > -defcc ) dragobj.style.pixelLeft = -defcc;
			scale = Math.floor((defmaxval-defminval)*(defaa + dragobj.style.pixelLeft)/defbb) + defminval ;
			if (scale)
			{
				obj.value = scale + "%";
				document.all.scale.value = scale;
				ImageScale(scale);
			}
		}
//		window.status = dragobj.style.pixelLeft +":"+ event.clientX;
	}

	function BrightdragHandler(obj)
	{
		var dragobj = event.srcElement;
		if( event.type == "dragstart")
		{
			offx = dragobj.style.pixelLeft - event.clientX;
		}
		else if( event.type == "drag" )
		{
			dragobj.style.left = offx + event.clientX;
			if(dragobj.style.pixelLeft< -defaa) dragobj.style.pixelLeft = -defaa;
			if(dragobj.style.pixelLeft > -defcc ) dragobj.style.pixelLeft = -defcc;
			scale = Math.floor((defmaxbrightval-defminbrightval)*(defaa + dragobj.style.pixelLeft)/defbb) + defminbrightval;
			obj.value = scale;
			document.all.Bright.value = scale;
		}
	}

	function ContrastdragHandler(obj)
	{
		var dragobj = event.srcElement;
		if( event.type == "dragstart")
		{
			offx = dragobj.style.pixelLeft - event.clientX;
		}
		else if( event.type == "drag" )
		{
			dragobj.style.left = offx + event.clientX;
			if(dragobj.style.pixelLeft< -defaa) dragobj.style.left = -defaa;
			if(dragobj.style.pixelLeft > -defcc ) dragobj.style.left = -defcc;
			scale = Math.floor((defmaxcontrastval-defmincontrastval)*(defaa + dragobj.style.pixelLeft)/defbb) + defmincontrastval;
			obj.value = scale;
			document.all.Contrast.value = scale;
		}
	}

	function setOriginBright()
	{
		document.all.BrightVal.value = "0";
		document.all.BrightBar.style.left = defbrightleft;
		document.all.Bright.value = 0;
	}

	function setOriginContrast()
	{
		document.all.ContrastVal.value = "0";
		document.all.ContrastBar.style.left = defcontrastleft;
		document.all.Contrast.value = 0;
	}

	function setDefaultSize()
	{
		var defleft = Math.floor((a_r - defminval)/(defmaxval-defminval)*defbb - defaa);
		sclval.value = a_r+"%";
		sclbar.style.left = defleft;
		document.all.scale.value = a_r ;
		ImageScale(a_r);
	}

	function setOriginSize()
	{
		sclval.value = "100%";
		sclbar.style.left = deforileft;
		document.all.scale.value = 100;
		ImageScale(100);
	}

	function setPhoneSize()
	{
		pid = picedit.PHONE.value;
		pwi = pic_wh_arr[pid][0];
		phi = pic_wh_arr[pid][1];
		imgwi = o_w;
		imghi = o_h;

		if (pwi/imgwi < phi/imghi)
		{
			ph_rate = phi/imghi*100;
		}
		else
		{
			ph_rate = pwi/imgwi*100;
		}

		defphleft = Math.floor((ph_rate - defminval)/(defmaxval-defminval)*defbb - defaa);
		sclval.value = Math.floor(ph_rate)+"%";
		sclbar.style.left = defphleft;
		document.all.scale.value = Math.floor(ph_rate);
		ImageScale(ph_rate);
		AdjustSelPicEdit("xy", 0, 0, 0);
	}

	function OnPhoneModelChange(value)
	{
		var pw = pic_wh_arr[value][0];
		var ph = pic_wh_arr[value][1];

		AdjustSelPicEdit("whr", 0, 0, pw/ph);
		AdjustSelPicEdit("wh", pw, ph, 0);
		picedit.rw.value = pw;
		picedit.rh.value = ph;
//default phone size
		if (img_loaded)
		{
			setDefaultSize();
		}
//		picedit.pid.value = value;
//		picedit.bid.value = picedit.BRAND.value;
	}

	function OnNext()
	{
		picedit.submit();
	}

	function OnForward()
	{
		var forwardURL = "";
		location.replace(forwardURL);
	}
	var ImgShowTime;
	var iii = 0;
	var img_loaded = 0;

	function ImgShow()
	{
		if ((!bgimg.readyState) || (bgimg.readyState=="complete" && cvimg.readyState=="complete"))
		{
			clearTimeout(ImgShowTime);
			prtr.style.display="none";
			prtr.style.visibility="hidden";
			edtr.style.display="block";
			edtr.style.visibility="visible";
//			edtd.innerHTML = edtd.innerHTML.replace("Í¼Æ¬ÔØÈë<BR>ÇëÉÔµÈ...", "");
			o_w = currentImage.width;
			o_h = currentImage.height;
			w_r = d_w/o_w;
			h_r = d_h/o_h;
			if (o_w > d_w || o_h > d_w)
			{
				if (w_r > h_r)
				{
					img_width = h_r*o_w;
					img_height = d_h;
					a_r = Math.floor(h_r*100);
				}
				else
				{
					img_height = w_r*o_h;
					img_width = d_w;
					a_r = Math.floor(w_r*100);
				}
			}
			else
			{
				img_width = o_w;
				img_height = o_h;
				a_r = 100;
			}
			var defleft = Math.floor((a_r - defminval)/(defmaxval-defminval)*defbb - defaa);
			sclval.value = a_r+"%";
			sclbar.style.left = defleft;
			document.all.scale.value = a_r ;

			ImageScale(a_r);
			szdesc.innerHTML="£¨"+o_w+" X "+o_h+"£©";
			sdiv.style.visibility = cvimg.style.visibility = bgimg.style.visibility = "visible";
//default phone size
			setDefaultSize();
			img_loaded = 1;
		}
		else
		{
			iii++;
//			window.status = iii;
			ImgShowTime = setTimeout("ImgShow()", 1);
			sdiv.style.visibility = "hidden";
		}
	}

	function InitMainDiv()
	{
		sdiv.style.position = "relative";
		sdiv.style.overflow = "auto";
		sdiv.style.border = "1 solid white";
		sdiv.style.left = d_l;
		sdiv.style.top = d_t;
		sdiv.style.width = d_w+3;
		sdiv.style.height = d_h+7;
		if (showopacity)
		{
			bgimg.style.filter = "alpha(opacity:30)";
		}
		bgimg.style.position = "absolute";
		bgimg.style.top = LOCX;
		bgimg.style.left = LOCY;
		cvimg.src = bgimg.src = currentImage.src;
	}

	function InitPage()
	{
		InitResizeTag();
		InitPicEditborder();
		InitWorkImage();
		InitSelectPicEdit();
		InitMainDiv();
//		ImgShow();
	}

	function init()
	{
		InitPage();
//default phone size
		if (setdefault)
		{
			setDefaultSize();
		}
		picedit.w.value = pic_wh_arr[document.all.PHONE.value][0];
		picedit.h.value = pic_wh_arr[document.all.PHONE.value][1];
		OnPhoneModelChange(document.all.PHONE.value);
	}