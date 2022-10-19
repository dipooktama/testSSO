<?php
// autoload buat import lib
require_once('vendor/autoload.php');

// import lib
use Firebase\JWT\JWT;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

header('Content-Type: application/json');
// cek methodnya POST ga?
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit();
}

// ambil JSON yg dikirim user(?)
$json = file_get_contents('php://input');
// decode JSONnya
// $input->identity itu NIM
// $input->password ya passwordnya
$input = json_decode($json);

// validasi data di JSONnya
if (!isset($input->identity) || !isset($input->password)) {
    http_response_code(400);
    exit();
}

// mock/dummy data
$user = [
    'identity' => '171401095',
    'password' => '123456',
];

// kapan tokennya expired
$expired_time = time() + (15 * 60);

// validate input sama yg di db (in this case make mock/dummy data)
if ($input->identity !== $user['identity'] || $input->password !== $user['password']) {
    echo json_encode([
        'message' => 'NIM atau password tidak sesuai'
    ]);
    exit();
}

// payload yang bakal diencode jadi token
$payload = [
    'identity' => $input->identity,
    'exp' => $expired_time
];

// string tipe algo encodernya
$alg = "HS256";

$ssotok = JWT::encode($payload, $_ENV['ACCESS_TOKEN_SECRET'], $alg);
echo json_encode($ssotok);

// nambah refresh token?
$payload['exp'] = time() + (60*60);
// refresh token
$refresh_token = JWT::encode($payload, $_ENV['REFRESH_TOKEN_SECRET'], $alg);
// simpen di http-only cookie
setcookie('ssotok',$ssotok,$payload['exp'],'','',false,true);
setcookie('refreshToken',$refresh_token,$payload['exp'],'','',false,true);