<?php
include_once '../config.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

if (Token::check(Token::getFromHeaders())) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $count = DB::count("SELECT * FROM sites");
        $sites = DB::query("SELECT * FROM sites");

        $array = array(
            'count' => $count,
            'sites' => array()
        );
        $number = 0;
        foreach ($sites as $site) {
            $array['sites'][$number] = array(
                'id' => $site['id'],
                'url' => $site['url'],
                'name' => $site['name'],
                'description' => $site['description'],
                'tags' => $site['tags'],
                'owner' => $site['owner']
            );
            $number++;
        }
        
        echo json_encode($array);
    }
} else {
    trigger_error('Not authorized', E_USER_WARNING);
    http_response_code(401);
}