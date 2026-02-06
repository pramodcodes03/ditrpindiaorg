 <!-- Inner Page Banner Area Start Here -->
<div class="inner-page-banner-area" style="background-image: url('../../resources/img/banner/5.jpg');">
    <div class="container">
        <div class="pagination-area">
            <h1>Gallery View</h1>
            <ul>
                <li><a href="index.php">Home</a> -</li>
                <li>Gallery View</li>
            </ul>
        </div>
    </div>
</div>
<!-- Inner Page Banner Area End Here -->
<div class="research-page1-area">
<div class="container">
	<div class="row">
	 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h2 class="title-default-left title-bar-high">Gallery View -<span>DITRP</span></h2>	
    </div>
		<?php
		$gallery_id = isset($_GET['id'])?$_GET['id']:'';
		$imgres = $db->list_gallery_files_all($gallery_id, '');
		$html='';
		if($imgres!='')
		{
			while($img = $imgres->fetch_assoc())
			{
				$GALLERY_FILE_ID 	= $img['GALLERY_FILE_ID'];
				$GALLERY_ID 	= $img['GALLERY_ID'];
				$GALLERY_TITLE 	= $img['GALLERY_TITLE'];
				$FILE_NAME 			= $img['FILE_NAME'];
				$FILE_MIME 			= $img['FILE_MIME'];
				$ACTIVE 			= $img['ACTIVE'];
				$filePath = HTTP_HOST.'/'.GALLERY.'/'.$GALLERY_ID.'/thumb/'.$FILE_NAME;
				$fileLink = HTTP_HOST.'/'.GALLERY.'/'.$GALLERY_ID.'/'.$FILE_NAME;
				
		?>
				<div class="col-md-3 col-sm-3 col-xs-6 picture">
					<a href="<?= $fileLink ?>" title="" class="fancybox" rel="gallery1" target="_blank">
					<span class="photo_icon"><i class="icon-picture-4"></i></span>
					<img src="<?= $filePath ?>" alt="" class="img-responsive"  />
					</a>
				</div>
		
		<?php
			}
		}

		?>


      </div><!-- End row -->
</div><!-- End container -->
</div><!-- End main_content-->