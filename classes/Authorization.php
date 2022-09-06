<?php

/**
 * File for Authorization functions.
 *
 * This file contains a class with functions used for Authorization.
 *
 * PHP version 8.1.9
 *
 * Copyright (c) 2022 Protopixel
 *
 * LICENSE: This source file is subject to version 3 of the GNU GPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/gpl-3.0.html.
 *
 * @category   Core
 * @author     Protopixel <protopixel06@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl-3.0.html  GNU GPL v3
 * @version    1.0
 * @since      File available since v1.0.0-pre1
 */

include_once getcwd() . "/../config.php";

class Auth
{
    public static function checkToken()
    {
        if (!(Token::check(Token::getFromHeaders())) && !(DISABLE_AUTH)) {
            echo json_encode(array("error" => "Unauthorized"));
            http_response_code(401);
            exit();
        }
    }
}
