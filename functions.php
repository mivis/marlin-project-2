<?php
session_start();

/*
поиск пользователя по email
возвращает массив с данными пользователя
*/
function get_user_by_email($email) {
    $pdo = new PDO("mysql:host=localhost;dbname=marlin-edu-2","root","");
    $sql = "SELECT * FROM users WHERE email=:email";
    $statement = $pdo->prepare($sql);
    $statement->execute(['email' => $email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);    
    return $user;
}

/*
поиск пользователя по id
возвращает массив с данными пользователя
*/
function get_user_by_id($id) {
    $pdo = new PDO("mysql:host=localhost;dbname=marlin-edu-2","root","");
    $sql = "SELECT * FROM users WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'id' => $id       
    ]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    return $user;
}

/*
получение всех пользователей 
возвращает массив с всеми пользователями
*/
function get_all_users() {
    $pdo = new PDO("mysql:host=localhost;dbname=marlin-edu-2","root","");
    $statement = $pdo->query("SELECT * FROM users");
    $all_users = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $all_users;
}

/*
добавление юзера в базу
возвращает id юзера $pdo->lastInsertId()
*/
function add_user($email, $password, $role="user") {
    $pdo = new PDO("mysql:host=localhost;dbname=marlin-edu-2","root","");
    $sql = "INSERT INTO users (email, password, role) VALUES (:email, :password, :role)";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => $role
    ]);
    return $pdo->lastInsertId();
}

/*
функция авторизации
возвращает bool
*/
function login($id, $email, $password, $bdemail, $bdpassword, $role) {
    if ($email == $bdemail && password_verify($password, $bdpassword)) {
        $_SESSION['login'] = $email;
        $_SESSION['role'] = $role;
        $_SESSION['id'] = $id;
        return true;
    } else {
        unset($_SESSION['login']);
        unset($_SESSION['role']);
        unset($_SESSION['id']);
        return false;
    }
}

/*
редактирование и добавленние данных о пользователе
*/
function edit_user_info($id, $name, $job, $phone, $address) {
    $pdo = new PDO("mysql:host=localhost;dbname=marlin-edu-2","root","");
    $sql = "UPDATE users SET name=:name, job=:job, phone=:phone, address=:address WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'id' => $id,
        'name' => $name,
        'job' => $job,
        'phone' => $phone,
        'address' => $address
    ]);
}

/*
редактирование email пользователя
*/
function edit_user_email($id, $email) {
    $pdo = new PDO("mysql:host=localhost;dbname=marlin-edu-2","root","");
    $sql = "UPDATE users SET email=:email WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'id' => $id,
        'email' => $email
    ]);
}

/*
редактирование password пользователя
*/
function edit_user_password($id, $password) {
    $pdo = new PDO("mysql:host=localhost;dbname=marlin-edu-2","root","");
    $sql = "UPDATE users SET password=:password WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'id' => $id,
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);
}

/*
редактирование статуса пользователя
*/
function edit_user_status($id, $status) {
    $pdo = new PDO("mysql:host=localhost;dbname=marlin-edu-2","root","");
    $sql = "UPDATE users SET status=:status WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'id' => $id,
        'status' => $status
    ]);
}

/*
редактирование социалок пользователя
*/
function edit_user_socials($id, $vk_link, $tg_link, $inst_link) {
    $pdo = new PDO("mysql:host=localhost;dbname=marlin-edu-2","root","");
    $sql = "UPDATE users SET vk_link=:vk_link, tg_link=:tg_link, inst_link=:inst_link WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'id' => $id,
        'vk_link' => $vk_link,
        'tg_link' => $tg_link,
        'inst_link' => $inst_link
    ]);
}

/*
проверка на наличие аватарки
*/
function has_avatar($id) {
    $user = get_user_by_id($id);
    if (!empty($user['avatar'])) {
        return true;
    } else {
        return false;
    }
}

/*
удаление файла аватарки при загрузке нового
*/
function delete_avatar($id) {
    $user = get_user_by_id($id);
    unlink ('../uploads/avatar/'.$user['avatar']);
}

/*
загрузка аватарки
*/
function upload_avatar($id, $tmp_name, $file_name) {
    $pdo = new PDO("mysql:host=localhost;dbname=marlin-edu-2","root","");
    $file_name = uniqid().'-'.$file_name;
    $sql = "UPDATE users SET avatar=:avatar WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'id' => $id,
        'avatar' => $file_name
    ]);    
    move_uploaded_file($tmp_name, "../uploads/avatar/".$file_name);
}

/*
удаление пользователя 
*/
function delete_user($id) {
    $pdo = new PDO("mysql:host=localhost;dbname=marlin-edu-2","root","");
    $sql = "DELETE FROM users WHERE id = :id";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'id' => $id
    ]);
}

/*
генерация флеш сообщения с статусом
*/
function set_flash_message($name, $message) {
    $_SESSION[$name] = $message;
}

/*
отображение флеш сообщения
возвращает html 
*/
function display_flash_message($name) {
    if (isset($_SESSION[$name])){
        echo "<div class=\"alert alert-".$name." text-dark\" role=\"alert\">".$_SESSION[$name]."</div>";
        unset($_SESSION[$name]);
    }
}

/*
редирект на страницу
*/
function redirect_to($url) {
    header("Location: ".$url."");
    exit;
}

/*
проверка на админа
$_SESSION['role'] присваивается в функции login
*/
function is_admin() {
    if ($_SESSION['role'] == "admin") {
        return true;
    } else {
        return false;
    }
}

/*
проверка на автора
$_SESSION['id'] присваивается в функции login
$_POST['id'] или $_GET['id'] - реализовано с ИЛИ т.к. функция отрабатывает и на странице users.php(ссылки на редактирования с GET), и на странице редактирований(id передается как hidden input через POST)
*/
function is_author() {
    if ($_SESSION['id'] == $_POST['id'] || $_SESSION['id'] == $_GET['id']) {
        return true;
    } else {
        return false;
    }
}

/*
проверка на авторизацию
возвращает bool
*/
function is_not_logged(){
    if (!isset($_SESSION['login'])) {
        return true;
    } else {
        return false;
    }
}

/*
функция выхода
*/
function logout(){
    unset($_SESSION['login']);
    unset($_SESSION['role']);
    unset($_SESSION['id']);
}
?>