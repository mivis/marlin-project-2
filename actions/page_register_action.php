<?php
session_start();
require("../functions.php");

$email = $_POST['email'];
$password =$_POST['password'];

$user = get_user_by_email($email);

if(!empty($user)) {
    set_flash_message("danger", "Пользователь с таким электронным адресом уже существует");
    redirect_to("../page_register.php");
}

add_user($email, $password);

set_flash_message("success", "Регистрация прошла успешно");

redirect_to("../page_login.php");
?>