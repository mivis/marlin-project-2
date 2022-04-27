<?php
session_start();
require("../functions.php");

$email = $_POST['email'];
$password = $_POST['password'];

$user = get_user_by_email($email);

if (empty($user)) {
    set_flash_message("danger", "Email или пароль не совпадают");
    redirect_to("../page_login.php");
}

if (login($user['id'], $email, $password, $user['email'], $user['password'], $user['role'])) {
    set_flash_message("success", "Вход выполнен");
    redirect_to("../users.php");
}
set_flash_message("danger", "Email или пароль не совпадают");
redirect_to("../page_login.php");
?>