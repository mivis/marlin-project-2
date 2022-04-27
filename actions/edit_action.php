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
$name = $_POST['name'];
$job = $_POST['job'];
$phone = $_POST['phone'];
$address = $_POST['address'];

edit_user_info($id, $name, $job, $phone, $address);

set_flash_message("success", "Пользователь отредактирован");
redirect_to("../edit.php?id=".$id."");

?>