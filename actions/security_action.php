<?php
session_start();
require("../functions.php");

if (is_not_logged()){
	set_flash_message("danger", "Вы не залогинены");
	redirect_to("../page_login.php");
}
if (!is_admin()) {
	if (!is_author()) {
		set_flash_message("danger", "Недостаточно прав");
		redirect_to("../users.php");
	}
}

$id = $_POST['id'];
$email = $_POST['email'];
$password = $_POST['password'];

$user = get_user_by_id($id);

if (!empty(get_user_by_email($email)) && $email !== $user['email']) {
    set_flash_message("danger", "Пользователь с таким электронным адресом уже существует");
    redirect_to("../security.php?id=".$id."");
}

edit_user_email($id, $email); 

if (!empty($password)) {
	edit_user_password($id, $password);
}

set_flash_message("success", "Данные пользователя успешно изменены");
redirect_to("../security.php?id=".$id."");
?>