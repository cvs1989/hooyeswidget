<?php
function imageWaterMark($groundImage,$waterPos=0,$waterImage="",$waterText="php168",$textFont=5,$textColor="#FF0000",$w_alpha="") 
{
    $isWaterImage = FALSE; 
    $formatMsg = "�ݲ�֧�ָ��ļ���ʽ������ͼƬ���������ͼƬת��ΪGIF��JPG��PNG��ʽ��"; 

    //��ȡˮӡ�ļ� 
    if(!empty($waterImage) && file_exists($waterImage)) 
    { 
        $isWaterImage = TRUE; 
        $water_info = getimagesize($waterImage); 
        $water_w    = $water_info[0];//ȡ��ˮӡͼƬ�Ŀ� 
        $water_h    = $water_info[1];//ȡ��ˮӡͼƬ�ĸ� 

        switch($water_info[2])//ȡ��ˮӡͼƬ�ĸ�ʽ 
        { 
            case 1:$water_im = imagecreatefromgif($waterImage);break; 
            case 2:$water_im = imagecreatefromjpeg($waterImage);break; 
            case 3:$water_im = imagecreatefrompng($waterImage);break; 
            default:die($formatMsg); 
        } 
    } 

    //��ȡ����ͼƬ 
    if(!empty($groundImage) && file_exists($groundImage)) 
    { 
        $ground_info = getimagesize($groundImage); 
        $ground_w    = $ground_info[0];//ȡ�ñ���ͼƬ�Ŀ� 
        $ground_h    = $ground_info[1];//ȡ�ñ���ͼƬ�ĸ� 

        switch($ground_info[2])//ȡ�ñ���ͼƬ�ĸ�ʽ 
        { 
            case 1:$ground_im = imagecreatefromgif($groundImage);break; 
            case 2:$ground_im = imagecreatefromjpeg($groundImage);break; 
            case 3:$ground_im = imagecreatefrompng($groundImage);break; 
            default:die($formatMsg); 
        } 
    } 
    else 
    { 
        die("��Ҫ��ˮӡ��ͼƬ�����ڣ�"); 
    } 

    //ˮӡλ�� 
    if($isWaterImage)//ͼƬˮӡ 
    { 
        $w = $water_w; 
        $h = $water_h; 
        $label = "ͼƬ��"; 
    } 
    else//����ˮӡ 
    { 
        $temp = imagettfbbox(ceil($textFont*2.5),0,"./cour.ttf",$waterText);//ȡ��ʹ�� TrueType ������ı��ķ�Χ 
        $w = $temp[2] - $temp[6]; 
        $h = $temp[3] - $temp[7]; 
        unset($temp); 
        $label = "��������"; 
    } 
    if( ($ground_w<$w) || ($ground_h<$h) ) 
    { 
        //echo "��Ҫ��ˮӡ��ͼƬ�ĳ��Ȼ��ȱ�ˮӡ".$label."��С���޷�����ˮӡ��"; 
        return; 
    } 
    switch($waterPos) 
    { 
        case 0://��� 
            $posX = rand(0,($ground_w - $w)); 
            $posY = rand(0,($ground_h - $h)); 
            break; 
        case 1://1Ϊ���˾��� 
            $posX = 0; 
            $posY = 0; 
            break; 
        case 2://2Ϊ���˾��� 
            $posX = ($ground_w - $w) / 2; 
            $posY = 0; 
            break; 
        case 3://3Ϊ���˾��� 
            $posX = $ground_w - $w; 
            $posY = 0; 
            break; 
        case 4://4Ϊ�в����� 
            $posX = 0; 
            $posY = ($ground_h - $h) / 2; 
            break; 
        case 5://5Ϊ�в����� 
            $posX = ($ground_w - $w) / 2; 
            $posY = ($ground_h - $h) / 2; 
            break; 
        case 6://6Ϊ�в����� 
            $posX = $ground_w - $w; 
            $posY = ($ground_h - $h) / 2; 
            break; 
        case 7://7Ϊ�׶˾��� 
            $posX = 0; 
            $posY = $ground_h - $h; 
            break; 
        case 8://8Ϊ�׶˾��� 
            $posX = ($ground_w - $w) / 2; 
            $posY = $ground_h - $h; 
            break; 
        case 9://9Ϊ�׶˾��� 
            $posX = $ground_w - $w; 
            $posY = $ground_h - $h; 
            break; 
        default://��� 
            $posX = rand(0,($ground_w - $w)); 
            $posY = rand(0,($ground_h - $h)); 
            break;     
    } 

    //�趨ͼ��Ļ�ɫģʽ 
    imagealphablending($ground_im, true); 

    if($isWaterImage)//ͼƬˮӡ 
    {
		
		if(!$w_alpha)
		{
			global $webdb;
			$w_alpha=$webdb[waterAlpha];
		}
		$w_alpha>0 || $w_alpha=100;
        imagecopymerge($ground_im, $water_im, $posX, $posY, 0, 0, $water_w,$water_h,$w_alpha);
    } 
    else//����ˮӡ 
    { 
        if( !empty($textColor) && (strlen($textColor)==7) ) 
        { 
            $R = hexdec(substr($textColor,1,2)); 
            $G = hexdec(substr($textColor,3,2)); 
            $B = hexdec(substr($textColor,5)); 
        } 
        else 
        { 
            die("ˮӡ������ɫ��ʽ����ȷ��"); 
        } 
        imagestring ( $ground_im, $textFont, $posX, $posY, $waterText, imagecolorallocate($ground_im, $R, $G, $B));         
    } 

    //����ˮӡ���ͼƬ //
    @unlink($groundImage); 
	
	if(function_exists("Imagegif")){

		switch($ground_info[2])//ȡ�ñ���ͼƬ�ĸ�ʽ 
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

    //�ͷ��ڴ� 
    if(isset($water_info)) unset($water_info); 
    if(isset($water_im)) imagedestroy($water_im); 
    unset($ground_info); 
    imagedestroy($ground_im); 
} 

/**
*��ͼƬ��С
**/
function ResizeImage($oldpic,$newpic,$maxwidth=800,$maxheight=600){
	if( eregi('.jpg',$oldpic) ){
		 $im = imagecreatefromjpeg($oldpic);
	}elseif( eregi('.png',$oldpic) ){
		 $im = imagecreatefrompng($oldpic);
	}elseif( eregi('.gif',$oldpic) ){
		$im = imagecreatefromgif($oldpic);
	}else{
		die("ͼƬ��ʽ����$oldpic");
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
//ԭͼ,�������ͼ,����ԭ��X,����ԭ��Y,����ͼ��,����ͼ��,ĩ����X,ĩ����Y,ԭͼ�������,�Ƿ��Զ�ȡֵ
function cutimg($srcimgurl,$endimgurl,$x,$y,$endimg_w,$endimg_h,$border_w,$border_h,$scale=100,$fix=0){
	$path = dirname ($endimgurl);
	if (!is_dir($path)) {
		if(!@mkdir ($path, 0777)){
			showerr("{$path} ��Ŀ¼���ܴ���,�ļ�����ʧ��");
		}
	}
	$ground_info = getimagesize($srcimgurl);
	switch($ground_info[2]){ 
		case 1:$im = imagecreatefromgif($srcimgurl);break; 
		case 2:$im = imagecreatefromjpeg($srcimgurl);break; 
		case 3:$im = imagecreatefrompng($srcimgurl);break; 
		default:die("ͼƬ��ʽ������$srcimgurl"); 
    }
	if($fix){//�����ȡͷ���һ����
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
	imagefilledrectangle($ni,0,0,$dstW,$dstH,$black);// ���ʣ�౳��ɫ
	if(function_exists("imagecopyresampled")){// �ı�ͼƬ��С
		imagecopyresampled($ni,$im,$dstX,$dstY,0,0,$fdstW,$fdstH,$srcW,$srcH);
	}else{
		imagecopyresized($ni, $im, $dstX, $dstY, 0, 0, $fdstW, $fdstH, $srcW, $srcH);
	}
	if(function_exists("Imagegif")){
		Imagegif($ni,$dstFile); // ������ļ�
	}else{
		 ImageJpeg ($ni,$dstFile);
	}
	imagedestroy($im);// �����ڴ�
	imagedestroy($ni);
}

?>