<?php
function imageWaterMark($groundImage,$waterPos=0,$waterImage="",$waterText="php168",$textFont=5,$textColor="#FF0000",$w_alpha="") 
{
    $isWaterImage = FALSE; 
    $formatMsg = "暂不支持该文件格式，请用图片处理软件将图片转换为GIF、JPG、PNG格式。"; 

    //读取水印文件 
    if(!empty($waterImage) && file_exists($waterImage)) 
    { 
        $isWaterImage = TRUE; 
        $water_info = getimagesize($waterImage); 
        $water_w    = $water_info[0];//取得水印图片的宽 
        $water_h    = $water_info[1];//取得水印图片的高 

        switch($water_info[2])//取得水印图片的格式 
        { 
            case 1:$water_im = imagecreatefromgif($waterImage);break; 
            case 2:$water_im = imagecreatefromjpeg($waterImage);break; 
            case 3:$water_im = imagecreatefrompng($waterImage);break; 
            default:die($formatMsg); 
        } 
    } 

    //读取背景图片 
    if(!empty($groundImage) && file_exists($groundImage)) 
    { 
        $ground_info = getimagesize($groundImage); 
        $ground_w    = $ground_info[0];//取得背景图片的宽 
        $ground_h    = $ground_info[1];//取得背景图片的高 

        switch($ground_info[2])//取得背景图片的格式 
        { 
            case 1:$ground_im = imagecreatefromgif($groundImage);break; 
            case 2:$ground_im = imagecreatefromjpeg($groundImage);break; 
            case 3:$ground_im = imagecreatefrompng($groundImage);break; 
            default:die($formatMsg); 
        } 
    } 
    else 
    { 
        die("需要加水印的图片不存在！"); 
    } 

    //水印位置 
    if($isWaterImage)//图片水印 
    { 
        $w = $water_w; 
        $h = $water_h; 
        $label = "图片的"; 
    } 
    else//文字水印 
    { 
        $temp = imagettfbbox(ceil($textFont*2.5),0,"./cour.ttf",$waterText);//取得使用 TrueType 字体的文本的范围 
        $w = $temp[2] - $temp[6]; 
        $h = $temp[3] - $temp[7]; 
        unset($temp); 
        $label = "文字区域"; 
    } 
    if( ($ground_w<$w) || ($ground_h<$h) ) 
    { 
        //echo "需要加水印的图片的长度或宽度比水印".$label."还小，无法生成水印！"; 
        return; 
    } 
    switch($waterPos) 
    { 
        case 0://随机 
            $posX = rand(0,($ground_w - $w)); 
            $posY = rand(0,($ground_h - $h)); 
            break; 
        case 1://1为顶端居左 
            $posX = 0; 
            $posY = 0; 
            break; 
        case 2://2为顶端居中 
            $posX = ($ground_w - $w) / 2; 
            $posY = 0; 
            break; 
        case 3://3为顶端居右 
            $posX = $ground_w - $w; 
            $posY = 0; 
            break; 
        case 4://4为中部居左 
            $posX = 0; 
            $posY = ($ground_h - $h) / 2; 
            break; 
        case 5://5为中部居中 
            $posX = ($ground_w - $w) / 2; 
            $posY = ($ground_h - $h) / 2; 
            break; 
        case 6://6为中部居右 
            $posX = $ground_w - $w; 
            $posY = ($ground_h - $h) / 2; 
            break; 
        case 7://7为底端居左 
            $posX = 0; 
            $posY = $ground_h - $h; 
            break; 
        case 8://8为底端居中 
            $posX = ($ground_w - $w) / 2; 
            $posY = $ground_h - $h; 
            break; 
        case 9://9为底端居右 
            $posX = $ground_w - $w; 
            $posY = $ground_h - $h; 
            break; 
        default://随机 
            $posX = rand(0,($ground_w - $w)); 
            $posY = rand(0,($ground_h - $h)); 
            break;     
    } 

    //设定图像的混色模式 
    imagealphablending($ground_im, true); 

    if($isWaterImage)//图片水印 
    {
		
		if(!$w_alpha)
		{
			global $webdb;
			$w_alpha=$webdb[waterAlpha];
		}
		$w_alpha>0 || $w_alpha=100;
        imagecopymerge($ground_im, $water_im, $posX, $posY, 0, 0, $water_w,$water_h,$w_alpha);
    } 
    else//文字水印 
    { 
        if( !empty($textColor) && (strlen($textColor)==7) ) 
        { 
            $R = hexdec(substr($textColor,1,2)); 
            $G = hexdec(substr($textColor,3,2)); 
            $B = hexdec(substr($textColor,5)); 
        } 
        else 
        { 
            die("水印文字颜色格式不正确！"); 
        } 
        imagestring ( $ground_im, $textFont, $posX, $posY, $waterText, imagecolorallocate($ground_im, $R, $G, $B));         
    } 

    //生成水印后的图片 //
    @unlink($groundImage); 
	
	if(function_exists("Imagegif")){

		switch($ground_info[2])//取得背景图片的格式 
		{ 
			case 1:imagegif($ground_im,$groundImage);break; 
			case 2:imagejpeg($ground_im,$groundImage);break; 
			case 3:imagepng($ground_im,$groundImage);break; 
			default:die($errorMsg); 
		} 

	}elseif(function_exists("imagejpeg")){
		imagejpeg($ground_im,$groundImage);
	}else{
		imagepng($ground_im,$groundImage);
	}

    //释放内存 
    if(isset($water_info)) unset($water_info); 
    if(isset($water_im)) imagedestroy($water_im); 
    unset($ground_info); 
    imagedestroy($ground_im); 
} 

/**
*把图片缩小
**/
function ResizeImage($oldpic,$newpic,$maxwidth=800,$maxheight=600){
	if( eregi('.jpg',$oldpic) ){
		 $im = imagecreatefromjpeg($oldpic);
	}elseif( eregi('.png',$oldpic) ){
		 $im = imagecreatefrompng($oldpic);
	}elseif( eregi('.gif',$oldpic) ){
		$im = imagecreatefromgif($oldpic);
	}else{
		die("图片格式不对$oldpic");
	}
    $width = imagesx($im);
    $height = imagesy($im);
    if(($maxwidth && $width > $maxwidth) || ($maxheight && $height > $maxheight)){
        if($maxwidth && $width > $maxwidth){
            $widthratio = $maxwidth/$width;
            $RESIZEWIDTH=true;
        }
        if($maxheight && $height > $maxheight){
            $heightratio = $maxheight/$height;
            $RESIZEHEIGHT=true;
        }
        if($RESIZEWIDTH && $RESIZEHEIGHT){
            if($widthratio < $heightratio){
                $ratio = $widthratio;
            }else{
                $ratio = $heightratio;
            }
        }elseif($RESIZEWIDTH){
            $ratio = $widthratio;
        }elseif($RESIZEHEIGHT){
            $ratio = $heightratio;
        }
        $newwidth = $width * $ratio;
        $newheight = $height * $ratio;
        if(function_exists("imagecopyresampled")){
              $newim = imagecreatetruecolor($newwidth, $newheight);
              imagecopyresampled($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        }else{
            $newim = imagecreate($newwidth, $newheight);
			//imagepstext($newim,'tryr','10','12','000000','000000',0,0,5,5,1,1);
			imagecopyresized($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        }
        ImageJpeg ($newim,$newpic);
        ImageDestroy ($newim);
    }else{
        ImageJpeg ($im,$newpic);
    }
	ImageDestroy ($im);
}
//原图,处理过的图,坐标原点X,坐标原点Y,最终图宽,最终图高,末坐标X,末坐标Y,原图缩放情况,是否自动取值
function cutimg($srcimgurl,$endimgurl,$x,$y,$endimg_w,$endimg_h,$border_w,$border_h,$scale=100,$fix=0){
	$path = dirname ($endimgurl);
	if (!is_dir($path)) {
		if(!@mkdir ($path, 0777)){
			showerr("{$path} 此目录不能创建,文件创建失败");
		}
	}
	$ground_info = getimagesize($srcimgurl);
	switch($ground_info[2]){ 
		case 1:$im = imagecreatefromgif($srcimgurl);break; 
		case 2:$im = imagecreatefromjpeg($srcimgurl);break; 
		case 3:$im = imagecreatefrompng($srcimgurl);break; 
		default:die("图片格式不允许$srcimgurl"); 
    }
	if($fix){//方便截取头像的一部分
		if($ground_info[0]<$ground_info[1]){
			$border_w=$ground_info[0];
			$border_h=$endimg_h*$ground_info[0]/$endimg_w;
		}elseif($ground_info[0]>$ground_info[1]){
			$border_h=$ground_info[1];
			$border_w=$endimg_w*$ground_info[1]/$endimg_h;
		}else{
			$border_w=$ground_info[0];
			$border_h=$ground_info[1];
		}
	}
	$newim = imagecreatetruecolor($endimg_w, $endimg_h);
	$x=($x*100)/$scale;
	$y=($y*100)/$scale;
	$border_width=($border_w*100)/$scale;
	$border_height=($border_h*100)/$scale;
	imagecopyresampled($newim, $im, 0,0, $x,$y, $endimg_w, $endimg_h, $border_width, $border_height );
	if(function_exists("Imagegif")){
		switch($ground_info[2]){ 
			case 1:imagegif($newim,$endimgurl);break;
			case 2:imagejpeg($newim,$endimgurl);break;
			case 3:imagepng($newim,$endimgurl);break;
			default:die("errorMsg"); 
		}
	}elseif(function_exists("imagejpeg")){
		imagejpeg($newim,$endimgurl);
	}else{
		imagepng($newim,$endimgurl);
	}
	ImageDestroy ($newim);
	ImageDestroy ($im);
}

function gdfillcolor($srcFile,$dstFile,$dstW,$dstH){ 
	@ImageAlphaBlending($srcFile, true);
	$picdata = GetImageSize($srcFile); 
	switch ($picdata[2]) { 
		case 1: 
			$im = @ImageCreateFromGIF($srcFile); 
			break; 
		case 2: 
			$im = @imageCreateFromJpeg($srcFile);
			break; 
		case 3: 
			$im = @ImageCreateFromPNG($srcFile); 
			break;
		case 6:
			$im = @ImageCreateFromWbmp($srcFile);
			break;
	}
	$srcW=ImageSX($im); 
	$srcH=ImageSY($im); 
	$dstX=0; 
	$dstY=0; 
	if ($srcW*$dstH>$srcH*$dstW) { 
		$fdstH=round($srcH*$dstW/$srcW);
		$dstY=floor(($dstH-$fdstH)/2);
		$fdstW=$dstW;
	}else{
		$fdstW=round($srcW*$dstH/$srcH);
		$dstX=floor(($dstW-$fdstW)/2);
		$fdstH=$dstH;
	}
	$dstX=($dstX<0)?0:$dstX; 
	$dstY=($dstX<0)?0:$dstY; 
	$dstX=($dstX>($dstW/2))?floor($dstW/2):$dstX; 
	$dstY=($dstY>($dstH/2))?floor($dstH/s):$dstY;


	$ni=imagecreatetruecolor($dstW,$dstH);
	$black = ImageColorAllocate($ni, 255,255,255);
	$white = ImageColorAllocate($ni, 255,255,255);
	imagefilledrectangle($ni,0,0,$dstW,$dstH,$black);// 填充剩余背景色
	if(function_exists("imagecopyresampled")){// 改变图片大小
		imagecopyresampled($ni,$im,$dstX,$dstY,0,0,$fdstW,$fdstH,$srcW,$srcH);
	}else{
		imagecopyresized($ni, $im, $dstX, $dstY, 0, 0, $fdstW, $fdstH, $srcW, $srcH);
	}
	if(function_exists("Imagegif")){
		Imagegif($ni,$dstFile); // 输出到文件
	}else{
		 ImageJpeg ($ni,$dstFile);
	}
	imagedestroy($im);// 清理内存
	imagedestroy($ni);
}

?>