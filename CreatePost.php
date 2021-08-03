<?php
session_start();

//сколько раз обновлял эту страницу пользователь
if (!isset($_COOKIE['count']))
{
    $cookie = 1;
    setcookie("count", $cookie);
    $_SESSION['modifyText'] = array();
}
else {
    $cookie = ++$_COOKIE['count'];
    setcookie("count", $cookie);
}


include('functions.php');

//если пишется жалоба, то..
if (isset($_GET['badTheme']) and $_GET['badTheme'] !== "") {
    $resulteTheme = $mysql->query("SELECT * FROM `posts` WHERE `id` = '$_GET[badTheme]'");
    $theme = $resulteTheme->fetch_assoc();
    $titleTheme = "Жалоба на тему |{$theme['themeTitle']}| пользователя |{$theme['account']}| ";
    $_SESSION['titleTheme'] = $titleTheme;
} else if (isset($_GET['badComm']) and $_GET['badComm'] !== "") {
    $resulteComment = $mysql->query("SELECT * FROM `comments` WHERE `id` = '$_GET[badComm]'");
    $comment = $resulteComment->fetch_assoc();
    $titleTheme = "Жалоба на комментарий |{$comment['comment']}| пользователя |{$comment['account']}| ";
    $_SESSION['titleTheme'] = $titleTheme;
}


//формы
if (isset($_POST['submit'])) {
    if (isset($_SESSION['titleTheme'])) {
        $_POST['catalog'] = 'Арбитражи';
        $_POST['titleTheme'] = $_SESSION['titleTheme'];
    }
    if (queryPostTrue(['titleTheme', 'textTheme', 'catalog'])) {
        $mysql->query("INSERT INTO `posts` ( `themeTitle`, `themeText`, `likes`, `WherePosted`, `account`)
             VALUES ('$_POST[titleTheme]', '$_POST[textTheme]', '0', '$_POST[catalog]', '$_SESSION[login]')");
        $_SESSION['error'] = 0;
        $result = $mysql->query("SELECT * FROM `posts` WHERE `themeTitle`='$_POST[titleTheme]' AND `themeText` = '$_POST[textTheme]' AND `account` = '$_SESSION[login]'");
        $user = $result->fetch_assoc();
        header("Location: ViewPost.php?theme={$user['id']}");

    } else {
        error(1);
    }
}
//кнопки
if(queryPostTrue(['TextLayot'])){
    $_SESSION['TextLayot'] = $_POST['TextLayot'];
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

<body style="background-color: rgb(133,115,115);">
<div class="container">

    <?php include('includes/header.php'); ?>

    <form action="CreatePost.php" method="post">
        <div class="form-group">

            <!--ошибки-->
            <p><?php if ($_SESSION['error'] == 1) {
                    echo "Заполните все поля";
                } ?></p>

            <p style="background-color: #c13d3d;">о чем пост? Тема</p>

            <?php if ((isset($_GET['badTheme']) and $_GET['badTheme'] !== "") or (isset($_GET['badComm']) and $_GET['badComm'] !== "")) { ?>
                <p class="d-lg-flex align-items-lg-start" style="width: 800px;"><?= $titleTheme ?></p>
            <?php } else { ?>
                <textarea name="titleTheme" class="d-lg-flex align-items-lg-start" style="width: 800px;"></textarea>
            <?php } ?>


            <p style="background-color: #c23d3d;">Сам пост:</p>

            <div class="form-group">
                <div class="d-inline-block">
                    <form action="CreatePost.php" method="post">
                        <button class="btn btn-primary" value="" name="" type="submit">Cсыл</button>
                        <button class="btn btn-primary" value="" name="" type="submit" type="button">УдалССЫЛ</button>
                        <button class="btn btn-primary" value="" name="" type="submit" type="button">Смайл</button>
                        <button class="btn btn-primary" value="text-left" name="TextLayot" type="submit" type="button">
                            Вырав l
                        </button>
                        <button class="btn btn-primary" value="text-center" name="TextLayot" type="submit"
                                type="button">Вырав c
                        </button>
                        <button class="btn btn-primary" value="text-right" name="TextLayot" type="submit" type="button">
                            Вырав r
                        </button>
                        <button class="btn btn-primary" value="" name="" type="submit" type="button">IMG</button>
                        <button class="btn btn-primary" value="" name="" type="submit" type="button">Скрыт</button>

                        <textarea name="textTheme" class="d-lg-flex align-items-lg-start <?= $_SESSION['TextLayot'] ?>"
                                  style="width: 800px; height: 200px; "><?=$_POST['textTheme']?></textarea>
                    </form>
                </div>
        </div>
        <?php if (!(((isset($_GET['badTheme']) and $_GET['badTheme'] !== "") or (isset($_GET['badComm']) and $_GET['badComm'] !== "")))) { ?>
            <?php selectCatalog(); ?>
        <?php } ?>
        <br>
        <button class="btn btn-primary" name="submit" type="submit">Опубликовать</button>
    </form>

</div>


<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>