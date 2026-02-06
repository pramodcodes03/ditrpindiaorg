<?php
include_once 'new_db_conection.php';

$domain_id = $_GET['domain'];
$sql = "SELECT A.* FROM domain_management A WHERE A.id = $domain_id ";
$result = $conn1->query($sql);
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($data = mysqli_fetch_assoc($result)) {
            //extract($data);
            $id                         = $data['id'];
            $domain_name                 = $data['domain_name'];
            $domain_purchase_date     = $data['domain_purchase_date'];
            $domain_expire_date         = $data['domain_expire_date'];
            $remark                     = $data['remark'];
            $admission_point             = $data['admission_point'];
        }
    }
}


if (isset($_POST['submit'])) {
    //getting the post values
    //$domain_name = $_POST['domain'];
    $domain_purchase = $_POST['domain_purchase_date'];
    $domain_expire = $_POST['domain_expire_date'];
    $remark = $_POST['remark'];
    $admision_point = $_POST['admision_point'];

    $updateSql = "UPDATE domain_management SET domain_purchase_date='$domain_purchase', domain_expire_date='$domain_expire', remark='$remark', admission_point='$admision_point' WHERE id= $domain_id";
    $updateResult = $conn1->query($updateSql);

    if ($updateResult) {
        echo "<script type='text/javascript'> document.location ='listInstitute'; </script>";
    } else {
        echo "<script>alert('Something Went Wrong. Please try again');</script>";
    }
}
?>
<div class="container  ">
    <div class="card-body">
        <h4 class="card-title">Edit Domain Details </h4>
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-12 form-outline mb-4">
                    <label class="form-label" for="domain">Domain Name</label>
                    <input type="text" id="domain" name="domain" value="<?= $domain_name ?>" readonly
                        class="form-control">
                </div>
                <div class="col-md-6 form-outline mb-4">
                    <label class="form-label" for="domain_purchase_date">Domain Purchase Date</label>
                    <input type="date" id="domain_purchase_date" name="domain_purchase_date" required="true"
                        class="form-control" value="<?= $domain_purchase_date ?>">
                </div>
                <div class="col-md-6 form-outline mb-4">
                    <label class="form-label" for="domain_expire_date">Domain Expire Date</label>
                    <input type="date" id="domain_expire_date" required="true" name="domain_expire_date"
                        class="form-control" value="<?= $domain_expire_date ?>" />
                </div>
                <div class="col-md-6 form-outline mb-4">
                    <label class="form-label" for="remark">Remarks</label>
                    <input type="text" id="remark" name="remark" class="form-control" value="<?= $remark ?>" />
                </div>
                <div class="col-md-6 form-outline mb-4">
                    <label class="form-label" for="admision_point">Admission Point</label>
                    <input type="number" id="admision_point" title="Only Number"
                        name="admision_point" class="form-control" value="<?= $admission_point ?>" />
                </div>

                <div class="col-md-12 form-outline mb-4">
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>