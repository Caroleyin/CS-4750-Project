<!-- change password php to confirm -->

<?php
require("connect-db.php");
session_start();

// check if change password button was clicked / form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmNewPassword = $_POST['confirm_password'];
}

// make sure new password and confirm password are the same, if not exit
if ($newPassword != $confirmNewPassword) {
    echo "New password and confirm password are not the same";
    exit;
}

$hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

$stmt = $db->prepare("UPDATE Users SET password = ? WHERE username = ?");
$stmt->bindParam(1, $hashedNewPassword);
$stmt->bindParam(2, $_SESSION['username']);

// execute the statement
if ($stmt->execute()) {
    echo 'Password changed successfully!';
} else {
    echo 'Error updating password. Retry.';
}

// redirect to homepage after a few seconds of success or error message
header("refresh:3;url=profile.php");
exit;

?>