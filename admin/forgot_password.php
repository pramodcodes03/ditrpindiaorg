<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// //ini_set('display_errors', 1);
header('Content-Type: application/json');

// Include necessary classes
include('include/classes/connection.class.php');

$response = [];
$conn = (new connection())->getDbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'send_otp') {
        $email = $_POST['email'] ?? '';
        $role = $_POST['role'] ?? '';

        // Query to check if email exists in institute_details table
        $stmt = $conn->prepare("
            SELECT user_login_master.USER_ID, institute_details.email 
            FROM user_login_master 
            INNER JOIN institute_details 
            ON institute_details.INSTITUTE_ID = user_login_master.USER_ID 
            WHERE institute_details.email = ? AND user_login_master.USER_ROLE = ?
        ");
        $stmt->bind_param('si', $email, $role);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch USER_ID from the result
            $row = $result->fetch_assoc();
            $userId = $row['USER_ID'];

            // Generate OTP and expiry time
            $otp = rand(100000, 999999);
            $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

            // Update OTP and expiry in user_login_master table
            $updateStmt = $conn->prepare("UPDATE user_login_master SET otp = ?, otp_expiry = ? WHERE USER_ID = ?");
            $updateStmt->bind_param('ssi', $otp, $expiry, $userId);
            $updateStmt->execute();

            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtpout.secureserver.net'; // SMTP server
                $mail->SMTPAuth   = true;
                $mail->Username   = 'admin@ditrpindia.com'; // SMTP username
                $mail->Password   = 'Ditrp#5678'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Encryption method
                $mail->Port       = 465;

                // Recipients
                $mail->setFrom('admin@ditrpindia.com', 'DITRP INDIA');
                $mail->addAddress($email); // Add recipient

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP';
                $mail->Body = "
                <p>Dear User,</p>
                <p>We received a request to reset your password for your DITRP account.</p>
                <p>Your OTP (One-Time Password) for resetting your password is: <strong>$otp</strong></p>
                <p>Please note, this OTP is valid for 10 minutes only. Do not share it with anyone for security reasons.</p>
                <br>
                <p>Regards,</p>
                <p>The DITRP Team</p>
            ";

                $mail->send();
                $response['status'] = 'success';
                $response['message'] = 'OTP sent to your email.';
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = 'Failed to send OTP. Mailer Error: ' . $mail->ErrorInfo;
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Email not found.';
        }
    } elseif ($action === 'verify_otp') {
        $email = $_POST['email'] ?? '';
        $otp = $_POST['otp'] ?? '';

        // Query to verify OTP
        $stmt = $conn->prepare("
            SELECT user_login_master.USER_ID 
            FROM user_login_master 
            INNER JOIN institute_details 
            ON institute_details.INSTITUTE_ID = user_login_master.USER_ID 
            WHERE institute_details.email = ? AND user_login_master.otp = ?");
        $stmt->bind_param('ss', $email, $otp);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response['status'] = 'success';
            $response['message'] = 'OTP verified. Proceed to reset your password.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Invalid or expired OTP.';
        }
    } elseif ($action === 'reset_password') {
        $email = $_POST['email'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Check if passwords match
        if ($new_password !== $confirm_password) {
            $response['status'] = 'error';
            $response['message'] = 'Passwords do not match.';
        } else {
            // Hash the new password
            $hashed_password = md5($new_password);

            // Update password in user_login_master table
            $stmt = $conn->prepare("
                UPDATE user_login_master 
                INNER JOIN institute_details 
                ON institute_details.INSTITUTE_ID = user_login_master.USER_ID 
                SET user_login_master.PASS_WORD = ?, user_login_master.otp = NULL, user_login_master.otp_expiry = NULL 
                WHERE institute_details.email = ?
            ");
            $stmt->bind_param('ss', $hashed_password, $email);
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Password updated successfully.';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Failed to update password. Please try again.';
            }
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid action.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
exit;
