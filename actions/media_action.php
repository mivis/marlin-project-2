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
$tmp_name = $_FILES['avatar']['tmp_name'];        
$file_name = $_FILES['avatar']['name'];

if (has_avatar($id)){
    delete_avatar($id);
}

upload_avatar($id, $tmp_name, $file_name);

set_flash_message("success", "Аватарка успешно изменена");
redirect_to("../media.php?id=".$id."");
?>