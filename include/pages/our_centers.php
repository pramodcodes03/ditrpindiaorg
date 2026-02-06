<div class="rs-check-out sec-spacer">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 form1">
				<h3 class="title-bg">Our Registered Center's</h3>
			
				<div class="check-out-box">
                    <table class="table table-bordered">
                        <thead>
                            <th>Center Name</th>
                            <th>City</th>
                            <th>State</th>
                        </thead>
                        <tbody>
                            <?php
                                include_once('include/classes/account.class.php');
                                $account = new account();
                                $res = $account->list_institute('', ' AND A.SHOW_ON_WEBSITE = 1');           
                                if($res!='')
                                {
                                    while($data = $res->fetch_assoc())
                                    {
                                        extract($data);
                                        //print_r($data);
                                        $inst_name = "";
                                        if($INSTITUTE_ID == '1'){
                                            $inst_name = "Main Center - ".$INSTITUTE_NAME;
                                        }else{
                                            $inst_name = "ATC Center - ".$INSTITUTE_NAME;
                                        }
                            ?>
                            <tr>
                                <td><?= $inst_name ?></td>
                                <td><?= $CITY ?></td>
                                <td><?= $STATE_NAME ?></td>
                               
                            </tr>
                            <?php
                                    }
                                }
                            ?>

                        </tbody>
                    </table>
				</div>						
			</div>
		</div>
	</div>
</div>