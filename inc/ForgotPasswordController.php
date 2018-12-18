<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//autoloader
require './vendor/autoload.php';
include_once 'autoload.php';

date_default_timezone_set('Asia/Ho_Chi_Minh');

/**
 * Class ForgotPasswordController
 */
class ForgotPasswordController
{
    private $request;

    public function __construct()
    {
        db::connect();
    }

    public function GeneralToken($email)
    {

        // general token
        $token = '';
        $prepare = "0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
        $len = strlen($prepare) - 1;
        for ($c = 0; $c < 25; $c++)
        {
            $token .= $prepare[rand(0, $len)];
        }

        //prepare insert string
        $sql = "INSERT INTO forgot_password(token, experied, username) VALUES(?, NOW() + INTERVAL 1 DAY, ?)";
        $sqlSelect = "SELECT username FROM forgot_password WHERE username = ?";

        // check exists email in record
        $data = db::$connection->prepare($sqlSelect);
        if ($data->execute([$email])) {

            $row = $data->fetch();
            if ($row) {
                // prepare update string
                $sql = "UPDATE forgot_password SET token = ?, experied = NOW() + INTERVAL 1 DAY WHERE username = ?";
            }
        }

        // update or new record
        $data = db::$connection->prepare($sql);
        if ($data->execute([$token, $email])) {
            return $token;
        }
        return false;
    }

    public function SendPasswordToEmail($email)
    {
        // general token
        $token = $this->GeneralToken($email);
        if (!$token) {
            return "Có lỗi xảy ra, vui lòng thử lại";
        }

        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'phananhviet1660765@gmail.com';
            $mail->Password = 'abc123XYZ';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            //Recipients
            $mail->setFrom('phananhviet1660765@gmail.com', '1660765.Documents.asia');
            $mail->addAddress($email);

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Forgot Password from website';
            $mail->Body    = "Nhấn vào đường dẫn sau để đặt lại mật khẩu: <a href='http://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]?token=$token'>http://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]?token=$token</a>.<br/>Có hiệu lực trong vòng 1 ngày, kể từ ngày nhận.<br/><br/>Nếu không phải là bạn, vui lòng không thực hiện điều này.";

            $mail->send();
            return 'Gửi thành công, vui lòng kiểm tra email và làm theo hướng dẫn';
        } catch (Exception $e) {
            return 'Không thể gửi mail. Mailer Error: '. $mail->ErrorInfo;
        }
    }

    public function ValidateToken($token)
    {
        $experied = '';
        $message = "Token không hợp lệ hoặc đã hết hạn";

        //query email from token in forgot_password table
        $sqlSelect = "SELECT * FROM forgot_password WHERE token = ?";
        $data = db::$connection->prepare($sqlSelect);
        if ($data->execute([$token])) {
            $row = $data->fetch();

            // Haven't token
            if (!$row)  return $message;

            // token was experied
            $experied = strtotime($row['experied']);
            $now = strtotime(date("Y-m-d H:i:s"));
            if ($now > $experied) return $message;
        }
        return true;
    }

    public function ChangePassword($token, $password)
    {
        $isNotExperied = $this->ValidateToken($token);
        if ($isNotExperied !== true) return $isNotExperied;

        $sqlSelect = "SELECT * FROM forgot_password WHERE token = ?";
        $data = db::$connection->prepare($sqlSelect);
        if ($data->execute([$token])) {
            $row = $data->fetch();
            $user = $row['username'];
        }

        if (!isset($user)) {
            return "Token không hợp lệ";
        }
        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sqlUpdate = "UPDATE users SET password = ? WHERE username = ?";
        $data = db::$connection->prepare($sqlUpdate);
        if ($data->execute([$passwordHash, $user])) {
            return "Thành công, thay đổi password thành $password";
        }

        return "Có lỗi xảy ra, vui lòng thử lại";
    }
}