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

    // Daftar komisi — sesuaikan dengan komisi yang ada
    public static $komisiList = [
        'Komisi 1',
        'Komisi 2',
        'Komisi 3',
        'Komisi 4',
        'Komisi 5'
    ];

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
                if (isset($user['is_active']) && (int)$user['is_active'] === 0) {
                    $_SESSION['error'] = 'Akun Anda belum aktif. Silakan cek email Anda untuk melakukan aktivasi.';
                    header('Location: /auth/login');
                    exit;
                }

                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_nama']  = $user['nama'];
                $_SESSION['user_role']  = $user['role'];
                $_SESSION['user_komisi']= $user['komisi'];

                if ($user['role'] === 'admin') {
                    header('Location: /dashboard/admin');
                } else {
                    header('Location: /dashboard/client');
                }
                exit;
            }

            $_SESSION['error'] = 'Email atau password salah.';
            header('Location: /auth/login');
            exit;
        }

        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama            = trim($_POST['nama'] ?? '');
            $email           = trim($_POST['email'] ?? '');
            $password        = trim($_POST['password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');
            $komisi          = trim($_POST['komisi'] ?? '');

            if (empty($nama) || empty($email) || empty($password) || empty($confirmPassword) || empty($komisi)) {
                $_SESSION['error'] = 'Semua field wajib diisi.';
                header('Location: /auth/register');
                exit;
            }

            if ($password !== $confirmPassword) {
                $_SESSION['error'] = 'Password dan konfirmasi password tidak cocok.';
                header('Location: /auth/register');
                exit;
            }

            if ($this->model->findByEmail($email)) {
                $_SESSION['error'] = 'Email sudah terdaftar.';
                header('Location: /auth/register');
                exit;
            }

            // Create user (defaults to inactive)
            $this->model->create([
                'nama'     => $nama,
                'email'    => $email,
                'password' => hash('sha256', $password),
                'role'     => 'user',
                'komisi'   => $komisi,
            ]);

            // Generate activation token
            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

            try {
                $stmt = $this->pdo->prepare("INSERT INTO auth_tokens (email, token, expires_at) VALUES (?, ?, ?)");
                $stmt->execute([$email, $token, $expiresAt]);

                $activationUrl = APP_URL . "/auth/verify?token=" . $token;
                $subject = "Aktivasi Akun WebDPM";
                $body = "
                    <div style='font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e5e7eb; border-radius: 8px; background-color: #ffffff;'>
                        <h2 style='color: #2563eb; margin-bottom: 10px;'>Selamat Datang di WebDPM!</h2>
                        <p style='color: #374151; font-size: 16px; line-height: 1.5;'>Halo <strong>" . htmlspecialchars($nama) . "</strong>,</p>
                        <p style='color: #374151; font-size: 16px; line-height: 1.5;'>Terima kasih telah mendaftar di WebDPM. Silakan aktifkan akun Anda dengan mengklik tombol di bawah ini:</p>
                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='" . $activationUrl . "' style='background-color: #2563eb; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: bold; display: inline-block;'>Aktifkan Akun</a>
                        </div>
                        <p style='color: #6b7280; font-size: 14px; line-height: 1.5;'>Link ini akan kedaluwarsa dalam 24 jam. Jika tombol di atas tidak berfungsi, Anda dapat menyalin dan menempelkan link berikut ke browser Anda:</p>
                        <p style='word-break: break-all; font-size: 14px; color: #2563eb;'>" . $activationUrl . "</p>
                        <hr style='border: 0; border-top: 1px solid #e5e7eb; margin: 20px 0;'>
                        <p style='color: #9ca3af; font-size: 12px;'>Jika Anda tidak merasa mendaftar di WebDPM, abaikan email ini.</p>
                    </div>
                ";

                if (self::kirimEmail($email, $nama, $subject, $body)) {
                    $_SESSION['success'] = 'Registrasi berhasil! Silakan periksa email Anda (inbox/spam) untuk melakukan aktivasi akun.';
                } else {
                    $_SESSION['error'] = 'Registrasi berhasil, tetapi gagal mengirim email aktivasi. Silakan hubungi admin.';
                }
            } catch (\PDOException $e) {
                error_log('Database error during token generation: ' . $e->getMessage());
                $_SESSION['error'] = 'Registrasi berhasil, tetapi terjadi kesalahan sistem saat membuat token aktivasi.';
            }

            header('Location: /auth/login');
            exit;
        }

        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function verify() {
        $token = trim($_GET['token'] ?? '');

        if (empty($token)) {
            $_SESSION['error'] = 'Token aktivasi tidak boleh kosong.';
            header('Location: /auth/login');
            exit;
        }

        try {
            // Check if token exists, is unused, and not expired
            $stmt = $this->pdo->prepare("SELECT * FROM auth_tokens WHERE token = ? AND used = 0 AND expires_at > NOW() LIMIT 1");
            $stmt->execute([$token]);
            $authToken = $stmt->fetch();

            if (!$authToken) {
                $_SESSION['error'] = 'Link aktivasi tidak valid, telah digunakan, atau telah kedaluwarsa.';
                header('Location: /auth/login');
                exit;
            }

            // Mark token as used
            $stmtUpdate = $this->pdo->prepare("UPDATE auth_tokens SET used = 1 WHERE id = ?");
            $stmtUpdate->execute([$authToken['id']]);

            // Activate user
            $email = $authToken['email'];
            $this->model->activate($email);

            // Fetch user and log them in
            $user = $this->model->findByEmail($email);
            if ($user) {
                $_SESSION['user_id']     = $user['id'];
                $_SESSION['user_nama']   = $user['nama'];
                $_SESSION['user_role']   = $user['role'];
                $_SESSION['user_komisi'] = $user['komisi'];
                $_SESSION['success']     = 'Akun Anda berhasil diaktifkan!';

                if ($user['role'] === 'admin') {
                    header('Location: /dashboard/admin');
                } else {
                    header('Location: /dashboard/client');
                }
                exit;
            }

            $_SESSION['error'] = 'User tidak ditemukan.';
            header('Location: /auth/login');
            exit;
        } catch (\PDOException $e) {
            error_log('Verification error: ' . $e->getMessage());
            $_SESSION['error'] = 'Terjadi kesalahan sistem saat memverifikasi link aktivasi.';
            header('Location: /auth/login');
            exit;
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /auth/login');
        exit;
    }

    public function profile() {
        $userId = $_SESSION['user_id'];
        $user = $this->model->findById($userId);

        if (!$user) {
            header('Location: /auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama'] ?? '');
            $komisi = trim($_POST['komisi'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');

            if (empty($nama)) {
                $_SESSION['error'] = 'Nama tidak boleh kosong.';
                header('Location: /auth/profile');
                exit;
            }

            // Update profile info
            $this->model->updateProfile($userId, [
                'nama' => $nama,
                'komisi' => $user['komisi']
            ]);

            // Update session values
            $_SESSION['user_nama'] = $nama;

            // Update password if requested
            if (!empty($password)) {
                if ($password !== $confirmPassword) {
                    $_SESSION['error'] = 'Password baru dan konfirmasi password tidak cocok.';
                    header('Location: /auth/profile');
                    exit;
                }

                $hashedPassword = hash('sha256', $password);
                $this->model->updatePassword($userId, $hashedPassword);
            }

            $_SESSION['success'] = 'Profil berhasil diperbarui.';
            header('Location: /auth/profile');
            exit;
        }

        require_once __DIR__ . '/../views/auth/profile.php';
    }

    // Helper kirim email — dipakai controller lain juga
    public static function kirimEmail($toEmail, $toNama, $subject, $body) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
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