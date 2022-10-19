<?php
require_once('./vendor/autoload.php');
use Firebase\JWT\JWT;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

header('Content-Type: application/json');
// cek methodnya GET ga?
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit();
}

// verif token yang uda dibuat....

// ngambil semua headernya
$headers = getallheaders();
// cek ada ga authorizationnya di header
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    exit();
}

// ambil token
// list itu assign ke banyak variabel dari suatu array
// explode disini dipake buat mecah headers bagian authorization, patokannya ' '
// jadi nanti muncul ['Bearer', '<token>']
// nah si list ini ngeassign 'Bearer' ke var kosong, sementara si $token ngambil <token>
list(,$ssotok) = explode(' ',$headers['Authorization']);
echo $ssotok;
/*

ini buat baca cookie wkkwkw

foreach ($_COOKIE as $key=>$val)
  {
    echo $key.' is '.$val."<br>\n";
  }
*/
//echo $_COOKIE['ssotok'];

// test verif tokennya pake try-catch
try {
    // decode tokennya
    JWT::decode($ssotok, $_ENV['ACCESS_TOKEN_SECRET']);
    echo '1';
    // data game yang dikirim kalo success
    $listofgames = [
        [
            'title' => 'Dota 2',
            'genre' => 'RTS',
        ],
        [
            'title' => 'Ragnarok Online',
            'genre' => 'MMORPG',
        ]
    ];
    echo '2';
    echo json_encode($listofgames);
    echo '3';
} catch (Exception $e) {
    // response error
    http_response_code(401);
    exit();
}