<?php
session_start();
include('functions.php');

//изменение номера сраницы
if (!isset($_SESSION['pageSettings'])) {
    $_SESSION['pageSettings'] = 1;
}
if (queryPostTrue(['page'])) {
    $_SESSION['pageSettings'] = $_POST['page'];
}

$change = array();

if (isset($_POST['submit'])) {
//страницы
//работает только если заполнены все поля
//____________________________________________________________________
//безопастность
    if (queryPostTrue(['pass','newpass1','newpass2'])) {
//получаем значения с $_пост, даже пустые
        $change['pass'] = ($_POST['pass']);
        $change['newpass1'] = ($_POST['newpass1']);
        $change['newpass2'] = ($_POST['newpass2']);

        $login = $_SESSION['login'];
        $result = $mysql->query("SELECT * FROM `users` WHERE `login` = '$login'");
        $user = $result->fetch_assoc();
        $realpass = $user['password'];

        if ($realpass == $change['pass']) {

            if ($change['newpass2'] == $change['newpass1']) {
                $mysql->query("UPDATE `users` SET `password` = '$change[newpass1]' WHERE `users`.`login`= '$login'");
                error(0);
            } else {
                error(2);
            }
        } else {
            error(3);
        }

    } //персональная инф
    else if (queryPostTrue(['login','day','year','month','status','gender'])) {
        $birthday = $_POST['day'] . " " . $_POST['month'] . " " . $_POST['year'];
        $login = $_SESSION['login'];
        $mysql->query("UPDATE `users` SET `login` = '$_POST[login]', `birthday` = '$birthday',
                    `status` = '$_POST[status]', `gender` = '$_POST[gender]' WHERE `users`.`login`= '$login'");
        if ($change['login'] !== NULL or $_POST['login'] !== "") {
            $_SESSION['login'] = $_POST['login'];
            error(0);
        }
    } else {
        error(1);
    }
}
//____________________________________________________________________________
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

<body style="background-color: rgb(147,135,135);">

<?php include('includes/header.php'); ?>


<!--выскакивающие ошибки-->
<p><?php if ($_SESSION['error'] == 1) {
        echo "Заполнитие все поля";
    } ?></p>
<p><?php if ($_SESSION['error'] == 2) {
        echo "Новые пароли не совпадают";
    } ?></p>
<p><?php if ($_SESSION['error'] == 3) {
        echo "Не верный пароль";
    } ?></p>

<!--основной контент страницы настроек-->
<form action="SettingsAccount.php" method="post">
    <!--        форма вокруг свитча!-->
    <?php
    switch ($_SESSION['pageSettings']) {
        case 2:
            ?>
            <!--безопастность-->
            <div class="container float-left" style="width: 700px;background-color: #7a6666;">
                <div class="form-group">
                    <label>пароль текущий</label>
                    <input name="pass" type="text"></div>
                <div class="form-group">
                    <div>
                        <label>пароль новый&nbsp;</label>
                        <input name="newpass1" class="d-block" type="text" placeholder="сначала сюда"
                               style="width: 300px;">
                        <input name="newpass2" class="d-block" type="text" placeholder="потом сюда"
                               style="width: 300px;">
                    </div>
                </div>
                <br>
                <button type="submit" name="submit">Сохранить</button>

            </div>
            <?php break; ?>

        <?php case 1: ?>
        <!--персональная инф-->
        <div class="container float-left" style="width: 700px;background-color: #806b6b;">
            <div class="form-group">
                <label>Ник</label>
                <input type="text" name="login">
            </div>

            <div class="form-group"><label>Пол</label>
                <div class="form-check d-inline-block">
                    <input class="form-check-input" type="radio" id="formCheck-1" name="gender" value="male">
                    <label class="form-check-label" for="formCheck-1">мжуской</label>
                </div>
                <div class="form-check d-inline-block">
                    <input class="form-check-input" type="radio" id="formCheck-1" name="gender" value="female">
                    <label class="form-check-label" for="formCheck-1">женский</label>
                </div>
                <div class="form-check d-inline-block">
                    <input class="form-check-input" type="radio" id="formCheck-1" name="gender" value="none">
                    <label class="form-check-label" for="formCheck-1">не указан</label>
                </div>
            </div>

            <div class="form-group">
                <div>
                    <label>Дата рождения</label>
                    <input name="month" type="text" placeholder="месяц" style="width: 100px;">
                    <input name="day" type="text" placeholder="число" style="width: 100px;">
                    <input name="year" type="text" placeholder="год" style="width: 100px;">
                </div>

                <label>Приветственное сообщение</label>
                <textarea name="status" style="width: 300px;"></textarea>
                <br>
                <button type="submit" name="submit">Сохранить</button>
            </div>
        </div>
        <?php break; ?>
    <?php } ?>

</form>


<!--меню страниц-->
<div class="container float-right" style="width: 285px;background-color: #746565;">
    <form action="SettingsAccount.php" method="post">
        <button class="btn btn-primary d-block" type="submit" style="width: 200px;">персональная инф</button>
        <input class="form-control invisible" type="text" name="page" value="1">
    </form>
    <form action="SettingsAccount.php" method="post">
        <button class="btn btn-primary shadow-sm d-block" type="submit" style="width: 200px;">безопастность</button>
        <input class="form-control invisible" type="text" name="page" value="2">
    </form>
</div>


<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>