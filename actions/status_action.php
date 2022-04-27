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

$status = $_POST['status'];
$id = $_POST['id'];

$user = get_user_by_id($id);
$current_status = $user['status'];

if ($status !== $current_status) {
    edit_user_status($id, $status);
}

set_flash_message("success", "Статус успешно изменен");
redirect_to("../status.php?id=".$id."");

?>