<?php
session_start();

if (isset($_SESSION['login'])) {
    header('Location: index.php');
}

include('functions.php');

if (isset($_POST['submit'])) {
    if (queryPostTrue(['login','password'])) {
        $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
        $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
        $result = $mysql->query("SELECT * FROM `users` WHERE `login`='$login' AND `password` = '$password'");
        $user = $result->fetch_assoc();
        if ($user === null) {
            error(1);
        } else {
            $_SESSION['error'] = 0;
            $_SESSION['login'] = $login;
            header('Location: index.php');
        }
    } else {
        error(2);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>LolzBS</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/-Login-form-Page-BS4-.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body style="background-color: rgb(187,182,182);">

<?php include('includes/header.php'); ?>

<!--основная форма-->
<form action="Login.php" method="post">
    <div class="container d-lg-flex justify-content-lg-center"
         style="width: 400px;margin-top: 90px;min-width: 400px;background-color: #1dba52;max-width: auto;height: auto;min-height: 400px;max-height: auto;">


        <div class="col" style="width: 400px;"><i class="fa fa-star d-block d-lg-flex justify-content-lg-center"
                                                  style="font-size: 69px;"></i>
            <div class="row text-center d-block d-lg-flex justify-content-lg-center">
                <div class="col">
                    <p>Войти</p>
                </div>
            </div>

            <!--ошибки-->
            <p><?php if ($_SESSION['error'] == 1) {
                    echo "Не верный пароль или логин";
                } ?></p>
            <p><?php if ($_SESSION['error'] == 2) {
                    echo "Заполните все поля";
                } ?></p>


            <div class="form-group">
                <div class="col">
                    <input class="align-content-center" type="text" placeholder="имя" name="login">
                    <input type="password" placeholder="password" name="password">
                    <button
                            class="btn btn-primary  d-block" type="submit" name="submit">Войти
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>