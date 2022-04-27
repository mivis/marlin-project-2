<?php
session_start();
require("../functions.php");

//проверка на логин и права админа
if (!is_not_logged() && is_admin()) {

    $name = $_POST['name'];
    $job = $_POST['job'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $status = $_POST['status'];
    $vk_link = $_POST['vk_link'];;
    $tg_link = $_POST['tg_link'];
    $inst_link = $_POST['inst_link'];

    //проверка email
    $user = get_user_by_email($email);  
    if(!empty($user)) {
        set_flash_message("danger", "Пользователь с таким электронным адресом уже существует");
        redirect_to("../create_user.php");
    }

    $id = add_user($email, $password);

    edit_user_info($id, $name, $job, $phone, $address);
    edit_user_status($id, $status);
    edit_user_socials($id, $vk_link, $tg_link, $inst_link);



    // проверка на передачу аватарки через форму и ее загрузка
    if(!empty($_FILES['avatar']['tmp_name'])) {

        $tmp_name = $_FILES['avatar']['tmp_name'];        
        $file_name = $_FILES['avatar']['name'];

        upload_avatar($id, $tmp_name, $file_name);
    }
    
    set_flash_message("success", "Пользователь добавлен");
    redirect_to("../users.php");

// если не залогинен и не админ
} else {
    set_flash_message("danger", "Недостаточно прав");
    redirect_to("page_login.php");
   
}
?>