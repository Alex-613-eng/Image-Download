<?php 

	// Get the entry info from ajax
	$urlImage = $_POST['url_Image'];
	$minWidth= $_POST['min_Width'];
	$minHeight= $_POST['min_Height'];
	$infoText= $_POST['info_Text'];
	if ($infoText == "") {
		$infoText = "Hello World.";
	}

	$resultFlag = 0;

	// Get the image width and height before download
	$size=getimagesize($urlImage);
	$width=$size[0];
	$height=$size[1];

	// Download the image bigger than MinWidth & MinHeight
	if($width >= $minWidth && $height >= $minHeight) {
		
		// Get the image name
		$fileNameWithExt = basename($urlImage);
		$arr = explode(".",$fileNameWithExt);
		$sizeArr = sizeof($arr);

		if ($sizeArr > 1) {
			$fileType = $arr[$sizeArr - 1];
			if(($fileType != 'jpg') && ($fileType != 'jpeg') && ($fileType != 'png') && ($fileType != 'PNG') && ($fileType != 'gif')) {
				$fileType != 'jpg';
			}
			$lengthExt = strlen($fileType);
			$fileName = substr($fileNameWithExt,0,-$lengthExt-1) . rand(100,1000);
			$fileNameWithExt = $fileName . "." . $fileType;
		} else {
			$fileType = $arr[0];
			$fileName = substr($urlImage,-3) . rand(100,1000);
			$fileNameWithExt = $fileName  . ".jpg";
		}
			$imgPath = 'images/' . $fileNameWithExt;
		

		// Save image 
		file_put_contents($imgPath, file_get_contents($urlImage));

		// Resize Image
		$targetFile = 'images/' . $fileName;
		$originalFile = $imgPath;

		reduceHeight($targetFile, $originalFile, $fileType);
		cropWidth($imgPath, $fileType);
		addText($imgPath, $infoText, $fileType);

		echo json_encode(array("imageName"=>$fileNameWithExt));
	}

	// Add text into Image
	function addText($newImagePath, $infoText, $fileType) {
		switch ($fileType) {
	        case 'jpeg':
                $jpg_image = imagecreatefromjpeg($newImagePath);
        		$textcolor = imagecolorallocate($jpg_image, 0, 0, 255,);
        		imagestring ($jpg_image, 40,20, 90, $infoText, $textcolor);
        		imagejpeg($jpg_image, $newImagePath);
        		imagedestroy($jpg_image);
                break;

             case 'jpg':
                $jpg_image = imagecreatefromjpeg($newImagePath);
        		$textcolor = imagecolorallocate($jpg_image, 0, 0, 255,);
        		imagestring ($jpg_image, 40,20, 90, $infoText, $textcolor);
        		imagejpeg($jpg_image, $newImagePath);
        		imagedestroy($jpg_image);
                break;

            case 'png':
	    		$jpg_image = imagecreatefrompng($newImagePath);
	    		imagesavealpha($jpg_image, true);
        		$textcolor = imagecolorallocate($jpg_image, 0, 0, 255,);
        		imagestring ($jpg_image, 40,20, 90, $infoText, $textcolor);
        		imagepng($jpg_image, $newImagePath, 0);
        		imagedestroy($jpg_image);
                break;

            case 'gif':
                $jpg_image = imagecreatefromgif($newImagePath);
        		$textcolor = imagecolorallocate($jpg_image, 0, 0, 255,);
        		imagestring ($jpg_image, 40,20, 90, $infoText, $textcolor);
        		imagegif($jpg_image, $newImagePath, 0);
        		imagedestroy($jpg_image);
                break;

            default: 
	            $jpg_image = imagecreatefromjpeg($newImagePath);
        		$textcolor = imagecolorallocate($jpg_image, 0, 0, 255,);
        		imagestring ($jpg_image, 40,20, 90, $infoText, $textcolor);
        		imagejpeg($jpg_image, $newImagePath);
        		imagedestroy($jpg_image);
                break;
        }
	}

	// Reduce the Image Height with 200px
	function reduceHeight($targetFile, $originalFile, $fileType) {
		$newHeight = 200;
	    $info = getimagesize($originalFile);

	    switch ($fileType) {
	            case 'jpeg':
	                    $image_create_func = 'imagecreatefromjpeg';
	                    $image_save_func = 'imagejpeg';
	                    $new_image_ext = 'jpg';
	                    break;

	             case 'jpg':
	                    $image_create_func = 'imagecreatefromjpeg';
	                    $image_save_func = 'imagejpeg';
	                    $new_image_ext = 'jpg';
	                    break;

	            case 'png':
	                    $image_create_func = 'imagecreatefrompng';
	                    $image_save_func = 'imagepng';
	                    $new_image_ext = 'png';
	                    break;

	            case 'PNG':
	                    $image_create_func = 'imagecreatefrompng';
	                    $image_save_func = 'imagepng';
	                    $new_image_ext = 'PNG';
	                    break;

	            case 'gif':
	                    $image_create_func = 'imagecreatefromgif';
	                    $image_save_func = 'imagegif';
	                    $new_image_ext = 'gif';
	                    break;

	            case 'GIF':
	                    $image_create_func = 'imagecreatefromgif';
	                    $image_save_func = 'imagegif';
	                    $new_image_ext = 'GIF';
	                    break;

	            default: 
	                    $image_create_func = 'imagecreatefromjpeg';
	                    $image_save_func = 'imagejpeg';
	                    $new_image_ext = 'jpg';
	                    break;
	    }

	    $img = $image_create_func($originalFile);
	    list($width, $height) = getimagesize($originalFile);

	    $newWidth = ($width / $height) * $newHeight;
	    $tmp = imagecreatetruecolor($newWidth, $newHeight);
	    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

	    if (file_exists($targetFile)) {
	            unlink($targetFile);
	    }
	    $image_save_func($tmp, "$targetFile.$new_image_ext");
	}

	// Crop the Image Width with 200px
	function cropWidth($newImagePath, $fileType) {
		switch ($fileType) {
	        case 'jpeg':
                $tn = imagecreatetruecolor(200, 200);
		        $newImage = imagecreatefromjpeg($newImagePath);
		        $size=getimagesize($newImagePath);
		        $width=$size[0];
		        $height=$size[1];
		        $resPosX = ($width - 200) / 2;
		        imagecopyresampled($tn, $newImage, -$resPosX, 0, 0, 0, $width, $height, 200, 200);
		        imagejpeg($tn, $newImagePath, 100);
                break;

            case 'jpg':
                $tn = imagecreatetruecolor(200, 200);
		        $newImage = imagecreatefromjpeg($newImagePath);
		        $size=getimagesize($newImagePath);
		        $width=$size[0];
		        $height=$size[1];
		        $resPosX = ($width - 200) / 2;
		        imagecopyresampled($tn, $newImage, -$resPosX, 0, 0, 0, $width, $height, 200, 200);
		        imagejpeg($tn, $newImagePath, 100);
                break;    

            case 'png':
	    		$tn = imagecreatetruecolor(200, 200);
		        $newImage = imagecreatefrompng($newImagePath);
		        imagesavealpha($newImage, true);
		        $size=getimagesize($newImagePath);
		        $width=$size[0];
		        $height=$size[1];
		        $resPosX = ($width - 200) / 2;
		        imagecopyresampled($tn, $newImage, -$resPosX, 0, 0, 0, $width, $height, 200, 200);
		        imagepng($tn, $newImagePath, 0);
                break;

            case 'PNG':
	    		$tn = imagecreatetruecolor(200, 200);
		        $newImage = imagecreatefrompng($newImagePath);
		        imagesavealpha($newImage, true);
		        $size=getimagesize($newImagePath);
		        $width=$size[0];
		        $height=$size[1];
		        $resPosX = ($width - 200) / 2;
		        imagecopyresampled($tn, $newImage, -$resPosX, 0, 0, 0, $width, $height, 200, 200);
		        imagepng($tn, $newImagePath, 0);
                break;


            case 'gif':
                $tn = imagecreatetruecolor(200, 200);
		        $newImage = imagecreatefromgif($newImagePath);
		        $size=getimagesize($newImagePath);
		        $width=$size[0];
		        $height=$size[1];
		        $resPosX = ($width - 200) / 2;
		        imagecopyresampled($tn, $newImage, -$resPosX, 0, 0, 0, $width, $height, 200, 200);
		        imagegif($tn, $newImagePath, 100);
                break;

            case 'GIF':
                $tn = imagecreatetruecolor(200, 200);
		        $newImage = imagecreatefromgif($newImagePath);
		        $size=getimagesize($newImagePath);
		        $width=$size[0];
		        $height=$size[1];
		        $resPosX = ($width - 200) / 2;
		        imagecopyresampled($tn, $newImage, -$resPosX, 0, 0, 0, $width, $height, 200, 200);
		        imagegif($tn, $newImagePath, 100);
                break;    

            default: 
	            $tn = imagecreatetruecolor(200, 200);
		        $newImage = imagecreatefromjpeg($newImagePath);
		        $size=getimagesize($newImagePath);
		        $width=$size[0];
		        $height=$size[1];
		        $resPosX = ($width - 200) / 2;
		        imagecopyresampled($tn, $newImage, -$resPosX, 0, 0, 0, $width, $height, 200, 200);
		        imagejpeg($tn, $newImagePath, 100);
                break;
        }
	}
?>