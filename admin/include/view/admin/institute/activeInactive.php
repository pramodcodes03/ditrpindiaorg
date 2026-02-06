<?php
echo $instId = $_POST['instId'];
echo $flag   = $_POST['flag'];
echo $dataConnection = $_POST['dataConnection'];
exit();

$sql1 = "UPDATE institute_details SET ACTIVE='$flag' WHERE INSTITUTE_ID='$instId'";
$sql2 = "UPDATE user_login_master SET ACTIVE='$flag' WHERE USER_ID='$instId' AND USER_ROLE=2";

$roshan = new mysqli('localhost',$dataConnection);
print_r($roshan); exit();
$result1 = $dataConnection->query($sql1);
$result2 = $dataConnection->query($sql2);

if ($result1 === TRUE && $result2 === TRUE) {
    echo "Updated Successfully";
} else {
    echo "Error: " . $sql1 . "<br>" . $dataConnection->error;
}

$conn->close();
?>