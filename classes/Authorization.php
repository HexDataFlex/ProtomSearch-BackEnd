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

    public static function checkUserType()
    {
        if (Auth::getUserType(Token::getUserId(Token::getFromHeaders())) < 2) {
            echo json_encode(array("error" => "Unauthorized"));
            http_response_code(401);
            exit();
        }
    }

    public static function register($username, $email, $password1, $password2)
    {
        // form validation: ensure that the form is correctly filled ...
        // by adding (array_push()) corresponding error unto $errors array
        if (empty($username)) {echo json_encode(array("error" => "Username is required"));
            http_response_code(400);exit();}
        if (empty($email)) {echo json_encode(array("error" => "Email is required"));
            http_response_code(400);exit();}
        if (empty($password1)) {echo json_encode(array("error" => "Password is required"));
            http_response_code(400);exit();}
        if ($password1 != $password2) {
            echo json_encode(array("error" => "Passwords do not match"));
            http_response_code(400);
            exit();
        }

        // first check the database to make sure
        // a user does not already exist with the same username and/or email
        @$user = DB::query("SELECT * FROM users WHERE username=:username OR email=:email LIMIT 1", array(':username' => $username, ':email' => $email))[0];

        if ($user) { // if user exists
            if (@$user['username'] === $username) {
                echo json_encode(array("error" => "Username already exists"));
                http_response_code(400);
                exit();
            }

            if (@$user['email'] === $email) {
                echo json_encode(array("error" => "Email already exists"));
                http_response_code(400);
                exit();
            }
        }
        $password = md5($password1); //encrypt the password before saving in the database

        DB::query("INSERT INTO users (username, email, password)
                          VALUES(:username, :email, :password)",
            array(':username' => $username, ':email' => $email, ':password' => $password));
        $thisUserId = DB::query("SELECT id FROM users WHERE username = :username AND email = :email", array(':username' => $username, ':email' => $email))[0]['id'];
        $token = Token::generate($thisUserId);
        echo json_encode(array("token" => $token));
    }

    public static function getUsernameWithEmail($email)
    {
        $user = DB::query("SELECT * FROM users WHERE email=:email", array(':email' => $email))[0];
        return $user['username'];
    }

    public static function getEmailWithUsername($username)
    {
        $user = DB::query("SELECT * FROM users WHERE username=:username", array(':username' => $username))[0];
        return $user['email'];
    }

    public static function getUsernameWithId($id)
    {
        $user = DB::query("SELECT * FROM users WHERE id=:id", array(':id' => $id))[0];
        return $user['username'];
    }

    public static function getEmailWithId($id)
    {
        $user = DB::query("SELECT * FROM users WHERE id=:id", array(':id' => $id))[0];
        return $user['email'];
    }

    public static function loginWithUsername($username, $password)
    {
        $pwd = md5($password);
        $user = DB::query("SELECT * FROM users WHERE username=:username AND password=:password", array(':username' => $username, ':password' => $pwd));
        if (!$user) {
            echo json_encode(array("error" => "Invalid username or password"));
            http_response_code(400);
            exit();
        }

        $token = Token::generate($user[0]['id']);
        echo json_encode(array("token" => $token));
    }

    public static function loginWithEmail($email, $password)
    {
        $pwd = md5($password);
        $user = DB::query("SELECT * FROM users WHERE email=:email AND password=:password", array(':email' => $email, ':password' => $pwd));
        if (!$user) {
            echo json_encode(array("error" => "Invalid username or password"));
            http_response_code(400);
            exit();
        }

        $token = Token::generate($user[0]['id']);
        echo json_encode(array("token" => $token));
    }

    public static function getUserType($id)
    {
        // 0 = user
        // 1 = moderator
        // 2 = administrator
        // 3 = owner
        $type = DB::query("SELECT * FROM users WHERE id = :id", array(':id' => $id))[0]['type'];
        return $type;
    }
}
