<?php session_start(); ?>
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

<body style="background-color: rgb(167,167,167);">
<DIV>HI</DIV>
<div class="container" style="height: 1000px;width: 1000px;background-color: #999999;">

    <?php include('includes/header.php');
    //lkjlkjlkjlkjlkj?>
    <?php include('functions.php');?>

    <!--    боковая панель-->
    <div class="row" style="margin: 20px;">
        <div class="col-md-4 col-lg-4" style="background-color: #30a034;">

            <!--            право на создание поста только зареганным пользователям-->
            <?php if (isset($_SESSION['login'])) { ?>
                <button class="btn btn-primary d-block" type="button">
                    <a href="CreatePost.php" style="color: rgb(245,246,247);">Создать пост</a>
                </button>
            <?php } else { ?>
                <button class="btn btn-primary d-block" type="button">
                    <a href="Login.php" style="color: rgb(245,246,247);">Войдите и обсудите</a>
                </button>
            <?php } ?>


<!--            создаем боковое меню-->
            <?php
                    getMenu();
            ?>

        </div>

        <!--        меню сортировки-->
        <div class="col-md-4 col-lg-8 offset-lg-0" style="height: auto;background-color: #23933c;">
            <div class="row">
                <div class="col">
                    <select>
                        <optgroup label="This is a group">
                            <option value="12" selected>This is item 1</option>
                            <option value="13">This is item 2</option>
                            <option value="14">This is item 3</option>
                        </optgroup>
                    </select>
                </div>
            </div>


            <!--            для рекламы-->
            <div class="row">
                <div class="col"><a class="d-block" href="#" style="color: rgb(180,37,28);font-size: 20px;">Реклама
                        нашего сайт 1<i class="fa fa-star"></i></a><a class="d-block" href="#"
                                                                      style="color: rgb(180,37,28);font-size: 20px;">Реклама
                        2 нашего сайт 2<i class="fa fa-star"></i></a></div>
            </div>

            <!--            вывод постов на страницу-->
            <?php

                $catalog = $_GET['catalog'];
                if ($catalog == null){
                    $catalog = "all";
                }
                ViewPosts($catalog);
            ?>

        </div>
    </div>
</div>


<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>