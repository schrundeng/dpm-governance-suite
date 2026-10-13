<?php
require_once __DIR__ . '/Controller.class.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/UserModel.class.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController extends Controller {
    private $model;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo   = $pdo;
        $this->model = new UserModel($pdo);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Email dan password wajib diisi.';
                header('Location: /auth/login');
                exit;
            }

            $user = $this->model->findByEmail($email);

            if ($user && hash('sha256', $password) === $user['password']) {
                if ($user['role'] !== 'admin') {
                    $_SESSION['error'] = 'Hanya admin yang dapat mengakses sistem ini.';
                    header('Location: /auth/login');
                    exit;
                }

                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_nama']  = $user['nama'];
                $_SESSION['user_role']  = $user['role'];

                header('Location: /dashboard/admin');
                exit;
            }

            $_SESSION['error'] = 'Email atau password salah.';
            header('Location: /auth/login');
            exit;
        }

        require_once __DIR__ . '/../views/auth/login.php';
    }



    public function logout() {
        session_destroy();
        header('Location: /auth/login');
        exit;
    }

    // Helper kirim email — dipakai controller lain juga
    public static function kirimEmail($toEmail, $toNama, $subject, $body) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = (!empty(MAIL_PASSWORD)) ? true : false;
            $mail->Username   = MAIL_USERNAME;
            $mail->Password   = MAIL_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = MAIL_PORT;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
            $mail->addAddress($toEmail, $toNama);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Email gagal: ' . $mail->ErrorInfo);
            return false;
        }
    }
}