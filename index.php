<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">	
    <link href="css/style.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>

<body>
	<div class="data-area container">
		<form action="" class="data-info" method="post">
			<p class="main-title">Image Download Interface</p>
			<div class="developer">
				<img src="MingLi.png" alt="" class="img-developer">
				<p class="developer-name">Ming Li</p>
				<p class="developer-info">aslike0613@hotmail.com</p>
			</div>
			<div class="url-area sub-area">
				<span class="sub-title">Image URL:</span>
				<input type="text" id="image-text" class="image-text input-text" placeholder="Please enter the image url." value="">
				<p id="url-alert"></p>
			</div>

			<div class="size-area sub-area">
				<div class="image-width">
					<span class="sub-title">Min Width:</span>
					<input type="number" id="width-text" class="width-text input-text" min="200" placeholder="200">
					<p id="minWidth-alert"></p>
				</div>

				<div class="image-height">
					<span class="sub-title">Min Height:</span>
					<input type="number" id="height-text"  class="height-text input-text" min="200" placeholder="200">
					<p id="minHeight-alert"></p>
				</div>
	
				<div class="text-area">
					<span class="sub-title">Text for image:</span>
					<input type="text" id="info-text" class="info-text input-text" placeholder="Hello World!">
				</div>

				<div class="alert-area">
					<span class="alert-text">waiting...</span>
				</div>
			</div>

			<div class="submit-area  sub-area">
				<button type="button" id="download-button" name="download-button" class="download-button">Download</button>
			</div>

			<div class="show-images" id="show-images">
				<img src="" alt="">
				<?php
					$imagesDirectory = "images/";

					if(is_dir($imagesDirectory))
					{
						$opendirectory = opendir($imagesDirectory);
					  
					    while (($image = readdir($opendirectory)) !== false)
						{
							if(($image == '.') || ($image == '..'))
							{
								continue;
							}
							
							$imgFileType = pathinfo($image,PATHINFO_EXTENSION);
							
							if(($imgFileType == 'jpg') || ($imgFileType == 'jpeg') || ($imgFileType == 'png') || ($imgFileType == 'PNG') || ($imgFileType == 'gif'))
							{
								echo "<img src='images/".$image."' width='200' class='folder-images'> ";
							}
					    }
						
					    closedir($opendirectory);
					 
					}
				?>
			</div>

			<div class="requirements">
				<span>Requirements</span>
				<p>- This interface downloads  images from a link to a page from the Internet, indicating the minimum size for the height and width of the images that will be downloaded from the specified page, images below these sizes should not be loaded.</p>

				<p>-And reduce the found pictures in height to 200px and crop them in width by 200px to a square and put text on the picture.</p>

				<p>-And the form should be processed through AJAX with the subsequent output of these images to the screen, without reloading the page, from the directory, when reloading the page, the already saved images should also be displayed.</p>
			</div>


		</form>
	</div>
<script>
	jQuery(function($){
		$('.alert-text').hide();
	    $('#download-button').click(function () {
	        var urlImage = $('#image-text').val();
  			var minWidth = $('#width-text').val();
  			var minHeight = $('#height-text').val();
  			var infoText = $('#info-text').val();

  			if (urlImage == "") {
  				$('#url-alert').text("Please enter the image url.");
  			} else {
  				$('#url-alert').text("");
  			}

  			if (minWidth < 200) {
          		$('#minWidth-alert').text("Please enter width bigger than 200.");
          	} else {
          		$('#minWidth-alert').text("");
          	}

          	if (minHeight < 200) {
          		$('#minHeight-alert').text("Please enter height bigger than 200.");
          	} else {
          		$('#minHeight-alert').text("");
          	}

          	if (minWidth >= 200 && minHeight >= 200) {
          		$('.alert-text').show();
		        jQuery.ajax({

		        	url:"downloadImage.php", //the page containing php script
		          	type: "post", //request type,
		          	dataType: 'json',
		         	data: {url_Image: urlImage, min_Width: minWidth, min_Height: minHeight, info_Text: infoText},

		          	success:function(result){
		          		$('.folder-images').hide();
		         		$('.alert-text').hide();
		               	$dirname = "images/" + result.imageName;
		               	var image = $('<img></img>');
		               	image.attr('src', $dirname);
		               	$('#show-images').append(image);
		            },
		            error: function(errorThrown){
		                console.log(errorThrown);
		                $('.alert-text').hide();
		            }
		        });
		    }     
	    });  
	    $('.alert-text').hide();
	});
</script>
</body>
</html>