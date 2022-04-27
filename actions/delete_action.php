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

$id = $_GET['id'];
$user = get_user_by_id($id);

if (has_avatar($id)) {
    delete_avatar($id);
}

delete_user($id);

set_flash_message("success", "Пользователь ".$user['email']." удален");

if (is_admin()) {
    redirect_to("../users.php");
}

logout();
redirect_to("../page_register.php");
?>