<?php

require('/home4/kzqhujmy/public_html/ditrpselfstudy/admin/include/classes/database_results.class.php');
require('/home4/kzqhujmy/public_html/ditrpselfstudy/admin/include/classes/access.class.php');

//$MERCHANT_KEY = "KnT61oXY";
//$SALT = "DTLwfcebBm";
// Merchant Key and Salt as provided by Payu.

$MERCHANT_KEY = "KnT61oXY";
$SALT = "DTLwfcebBm";

$action = '';

$server_url="https://ditrpself-study.com/admin/WebService/payment/";


if(isset($_POST['amount']) && $_POST['amount']!="" && isset($_POST['firstname']) && $_POST['firstname']!="" 
&& isset($_POST['email']) && $_POST['email']!="" && isset($_POST['phone']) && $_POST['phone']!="" && isset($_POST['user_id']) && $_POST['user_id']!=""
&& isset($_POST['course_id']) && $_POST['course_id']!="")
{
   // error_log(print_r('if',true));
    
    $_POST['key']=$MERCHANT_KEY;
    //$_POST['productinfo']='test';
    //$_POST['service_provider']='payu_paisa';
    
    $key=$MERCHANT_KEY;
    
    $productinfo=$_POST['productinfo'];

    $surl=$server_url.'success.php';
    $furl=$server_url.'failure.php?msg=Something went wrong';
    
    $_POST['surl']=$surl;
    $_POST['furl']=$furl;
    
    $user_id        = $_POST['user_id'];
    $course_id      = $_POST['course_id'];
    $referal_code   = $_POST['referal_code'];
    $inst_id        = $_POST['inst_id'];
    
    $couponcode     = $_POST['couponcode'];

    $_POST['udf1']  =   $user_id;
    $_POST['udf2']  =   $course_id;
    $_POST['udf3']  =   $referal_code;
    $_POST['udf4']  =   $inst_id;
    $_POST['udf5']  =   $couponcode;
    
   // error_log(print_r($_POST, TRUE));

    //$PAYU_BASE_URL = "https://sandboxsecure.payu.in";		// For Sandbox Mode /Test mode
    //$PAYU_BASE_URL = "https://secure.payu.in";			// For Production Mode
    
    $PAYU_BASE_URL = "https://secure.payu.in";		// For Sandbox Mode /Test mode
    
    $action = '';
    
    $posted = array();
    if(!empty($_POST)) {
        
      foreach($_POST as $key => $value) {    
        $posted[$key] = $value; 
      }
    }
    
   //error_log(print_r($posted,true));
    
    $formError = 0;
    
    if(empty($posted['txnid'])) {
      // Generate random transaction id
      $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
    } else {
      $txnid = $posted['txnid'];
    }
    
    $hash = '';
    // Hash Sequence
    $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
    if(empty($posted['hash']) && sizeof($posted) > 0) {
      if(
              empty($posted['key'])
              || empty($posted['txnid'])
              || empty($posted['amount'])
              || empty($posted['firstname'])
              || empty($posted['email'])
              || empty($posted['phone'])
              || empty($posted['productinfo'])
              || empty($posted['surl'])
              || empty($posted['furl'])
    		  || empty($posted['service_provider'])
      ) 
      {
        $formError = 1; 
      } 
      else 
      {
        //$posted['productinfo'] = json_encode(json_decode('[{"name":"tutionfee","description":"","value":"500","isRequired":"false"},{"name":"developmentfee","description":"monthly tution fee","value":"1500","isRequired":"false"}]'));
    	$hashVarsSeq = explode('|', $hashSequence);
        $hash_string = '';	
    	foreach($hashVarsSeq as $hash_var) {
          $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
          $hash_string .= '|';
        }
    
        $hash_string .= $SALT;
    
        $hash = strtolower(hash('sha512', $hash_string));
        $action = $PAYU_BASE_URL . '/_payment';
        
      }
    } elseif(!empty($posted['hash'])) {
        
      $hash = $posted['hash'];
      
      $action = $PAYU_BASE_URL . '/_payment';
      
    }

?>    
<html>
  <head>

  </head>
  <body onload="submitPayuForm()">
    <!--<h2>PayU Form</h2>-->
    <form action="<?php echo $action; ?>" method="post" name="payuForm" style="display:none;">
      <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
      <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
      <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
      <table>
        <tr>
          <td><b>Mandatory Parameters</b></td>
        </tr>
        <tr>
          <td>Amount: </td>
          <td><input type="hidden" name="amount" value="<?php echo (empty($posted['amount'])) ? '' : $posted['amount'] ?>" /></td>
          <td>First Name: </td>
          <td><input type="hidden" name="firstname" id="firstname" value="<?php echo (empty($posted['firstname'])) ? '' : $posted['firstname']; ?>" /></td>
        </tr>
        <tr>
          <td>Email: </td>
          <td><input name="email" id="email" value="<?php echo (empty($posted['email'])) ? '' : $posted['email']; ?>" /></td>
          <td>Phone: </td>
          <td><input name="phone" value="<?php echo (empty($posted['phone'])) ? '' : $posted['phone']; ?>" /></td>
        </tr>
        <tr>
          <td>Product Info: </td>
          <td colspan="3"><textarea name="productinfo"><?php echo (empty($posted['productinfo'])) ? '' : $posted['productinfo'] ?></textarea></td>
        </tr>
        <tr>
          <td>Success URI: </td>
          <td colspan="3"><input type="hidden" name="surl" value="<?=$surl;?>" size="64" /></td>
        </tr>
        <tr>
          <td>Failure URI: </td>
          <td colspan="3"><input type="hidden" name="furl" value="<?=$furl;?>" size="64" /></td>
        </tr>

        <tr>
          <td colspan="3"><input type="hidden" name="service_provider" value="payu_paisa" size="64" /></td>
        </tr>
        
        <tr>
          <td colspan="3"><input type="hidden" name="user_id" value="<?=$_POST['user_id'];?>" size="64" /></td>
        </tr>
        
        <tr>
          <td colspan="3"><input type="hidden" name="course_id" value="<?=$_POST['course_id'];?>" size="64" /></td>
        </tr>
        
        <tr>
          <td colspan="3"><input type="hidden" name="referal_code" value="<?=$_POST['referal_code'];?>" /></td>
        </tr>
        
        <tr>
          <td colspan="3"><input type="hidden" name="couponcode" value="<?=$_POST['couponcode'];?>" /></td>
        </tr>
        
        <tr>
          <td colspan="3"><input type="hidden" name="inst_id" value="<?=$_POST['inst_id'];?>" /></td>
        </tr>
  
        <tr>
          <td>UDF1: </td>
          <td><input name="udf1" value="<?php echo (empty($posted['udf1'])) ? '' : $posted['udf1']; ?>" /></td>
          <td>UDF2: </td>
          <td><input name="udf2" value="<?php echo (empty($posted['udf2'])) ? '' : $posted['udf2']; ?>" /></td>
        </tr>
        <tr>
          <td>UDF3: </td>
          <td><input name="udf3" value="<?php echo (empty($posted['udf3'])) ? '' : $posted['udf3']; ?>" /></td>
          <td>UDF4: </td>
          <td><input name="udf4" value="<?php echo (empty($posted['udf4'])) ? '' : $posted['udf4']; ?>" /></td>
        </tr>
        <tr>
          <td>UDF5: </td>
          <td><input name="udf5" value="<?php echo (empty($posted['udf5'])) ? '' : $posted['udf5']; ?>" /></td>
          <td>PG: </td>
          <td><input name="pg" value="<?php echo (empty($posted['pg'])) ? '' : $posted['pg']; ?>" /></td>
        </tr>
       
        <tr>
          <?php if(!$hash) { ?>
            <td colspan="4"><input type="submit" value="Submit" /></td>
          <?php } ?>
        </tr>
      </table>
    </form>
  </body>
    <script type='text/javascript'>
        function submitPayuForm()
        {
            document.payuForm.submit();    
        }
        
        </script>
    <script>
    // var hash = '<?php echo $hash ?>';
    // alert(hash);
    
    // var payuForm = document.forms.payuForm;
    // payuForm.submit();
    
    // function submitPayuForm() {
    //   if(hash == '') {
    //     return;
    //   }
    //   var payuForm = document.forms.payuForm;
    //   payuForm.submit();
    // }
  </script>
</html>

<?php
}
else
{
    $data['response'] = "n";
    $data['error'] = true;
    $data['message'] = "All field required";
    echo json_encode($data);
}
?>
