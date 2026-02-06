
<!-- Inner Page Banner Area Start Here -->
        <div class="inner-page-banner-area" style="background-image: url('resources/img/banner/5.jpg');">
            <div class="container">
                <div class="pagination-area">
                    <h1>Terms Of Offer</h1>
                    <ul>
                        <li><a href="index.php">Home</a> -</li>
                        <li>Terms Of Offer</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Inner Page Banner Area End Here -->

	<!-- services -->
	<div class="about-page1-area">
		<div class="container">
		    <div class="row about-page1-inner">
		        <div class="about-page-content-holder">
                    <div class="content-box text-justify">
                    	<h2>Terms Of Offer</h2>
            			<div class="wthree_services_grids">	
            				<div class="col-md-12 wthree_services_grid_left">					
            					<?php
            						$page = isset($_GET['pg'])?$_GET['pg']:'home';
            						//get page content
            						$pageres = $db->list_pages(" WHERE PAGE_LINK='$page' AND ACTIVE=1 LIMIT 0,1");
            						if($pageres!='')
            						{
            							while($pagedata = $pageres->fetch_assoc())
            							{
            								$PAGE_ID = $pagedata['PAGE_ID'];
            								$PAGE_NAME = $pagedata['PAGE_NAME'];
            								$PAGE_DATA = $pagedata['PAGE_DATA'];
            								$PAGE_LINK = $pagedata['PAGE_LINK'];
            								$META_TAGS = $pagedata['META_TAGS'];
            								$META_DESCRIPTION = $pagedata['META_DESCRIPTION'];
            								$PAGE_TITLE = $pagedata['PAGE_TITLE'];
            								
            								
            								echo $PAGE_DATA;
            							}
            						}
            					?>		
            				</div>
            				
            				<div class="clearfix"> </div>
            			</div>
	                </div>
	           </div>
	       </div>
	    </div>
	</div>
<!-- //services -->
