<div class="card-body">
    <h4 class="card-title">List Institute
        <!-- <a href="addPlans" class="btn btn-primary" style="float: right">Add Institute Plans</a> -->
    </h4>
    <?php
    include_once 'new_db_conection.php';

    $result = mysqli_query($conn, "SELECT A.* FROM institute_details A LEFT JOIN user_login_master B ON A.INSTITUTE_ID = B.USER_ID WHERE B.USER_ROLE =2  ");

    ?>

    <?php

    $result11 = mysqli_query($conn, "SELECT  count(*) as total from student_details");
    $data = mysqli_fetch_assoc($result11);

    ?>


    <?php


    ?>

    <?php
    if (mysqli_num_rows($result) > 0) {
    ?>
        <div class="table-responsive pt-3">
            <div id="order-listing_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="row">
                    <div class="col-sm-12 col-md-6">

                    </div>
                    <div class="col-sm-12 col-md-6">

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table id="order-listing" class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Sr.: activate to sort column descending" style="width: 41.5312px;">
                                        CENTER_NAME .</th>
                                    <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 74.4531px;">CONTACT </th>
                                    <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 74.4531px;"> STATE NAME</th>
                                    <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 74.4531px;">CITY</th>
                                    <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 74.4531px;">PIN CODE</th>
                                    <th class="sorting" tabindex="0" aria-controls="order-listing" aria-label="Action: activate to sort column ascending" style="width: 121.875px;">USER_ID </th>
                                    <th class="sorting" tabindex="0" aria-controls="order-listing" aria-label="Action: activate to sort column ascending" style="width: 121.875px;">USER_PASSWORD </th>
                                    <th class="sorting" tabindex="0" aria-controls="order-listing" aria-label="Action: activate to sort column ascending" style="width: 121.875px;">DOMIN_DATE</th>
                                    <th class="sorting" tabindex="0" aria-controls="order-listing" aria-label="Action: activate to sort column ascending" style="width: 121.875px;"> DOMIN EXPIRE DATE</th>
                                    <th class="sorting" tabindex="0" aria-controls="order-listing" aria-label="Action: activate to sort column ascending" style="width: 121.875px;">NUMBER OF ADMISION</th>
                                    <th class="sorting" tabindex="0" aria-controls="order-listing" aria-label="Action: activate to sort column ascending" style="width: 121.875px;"> NUMBER OF CENTER</th>
                                    <th class="sorting" tabindex="0" aria-controls="order-listing" aria-label="Action: activate to sort column ascending" style="width: 121.875px;">NUMBER OF FRANCHISE</th>
                                    <th class="sorting" tabindex="0" aria-controls="order-listing" aria-label="Action: activate to sort column ascending" style="width: 121.875px;"></th>
                                    <th class="sorting" tabindex="0" aria-controls="order-listing" aria-label="Action: activate to sort column ascending" style="width: 121.875px;">ACTION </th>
                                </tr>
                            </thead>
                            <?php
                            $i = 0;
                            while ($row = mysqli_fetch_array($result)) {
                            ?>
                                <tbody>


                                    <tr id="row-1" class="odd">

                                        <td class="sorting_1"><?php echo $row["INSTITUTE_NAME"]; ?></td>
                                        <td><?php echo $row["MOBILE"]; ?></td>
                                        <td id=""><?php echo $row["STATE"]; ?></td>
                                        <td id=""><?php echo $row["CITY"]; ?></td>
                                        <td id=""><?php echo $row["POSTCODE"]; ?></td>
                                        <td id=""><?php echo $row[" "]; ?></td>
                                        <td id=""><?php echo $row[" "]; ?></td>
                                        <td id=""><?php echo $row[" "]; ?></td>
                                        <td id=""><?php echo $row[" "]; ?></td>
                                        <td id=""><?php echo $row[" "]; ?></td>
                                        <td id=""><?php echo $row[" "]; ?></td>
                                        <td id=""><?php echo $row[" "]; ?></td>
                                        <td id=""><?php echo $row[" "]; ?></td>
                                        <!-- <td>
                                        <a href="student_list" class="btn btn-primary table-btn"
                                            title="view"><i class="mdi mdi-eye"></i></a><a
                                            href="javascript:void(0)" 
                                            onclick="deleteInstitutePlan(1)"></a></td>
                                            -->
                                        <td>
                                            <a href="page.php?page=student_list" class="btn btn-primary table-btn" title="view"><i class="mdi mdi-eye"></i></a>
                                            <a class=" btn btn-primary  text-white mt-2 p-2">Total student</a>
                                            <!-- <a class="btn btn-primary  mt-2 p-1  " title="view"><span class="text-white btn    p-1 p-1 ">< ?php echo $data["total"]; ?></span></a> -->
                                            <a href=""> <button class="btn btn-primary mt-2 p-2" title="TOTAL STUDENT"> <?php echo $data["total"]; ?></button></a>
                                        </td>

                                    </tr>

                        </table>
                    <?php
                                $i++;
                            }
                    ?>
                <?php
            } else {
                echo "No result found";
            }
                ?>
                </tbody>

                </table>
                    </div>
                </div>

            </div>
        </div>
</div>