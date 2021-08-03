<?php session_start();
include('functions.php');



if (isset($_GET['whosePage']) and $_GET['whosePage'] !== ""){
    $_SESSION['whosePage'] = $_GET['whosePage'];
}
$whosePage = $_SESSION['whosePage'];


// формы
if (isset($_POST['submit'])) {
    if (queryPostTrue(['themeText'])) {
        $mysql->query("INSERT INTO `posts` ( `themeTitle`, `themeText`, `likes`, `WherePosted`, `account`)
             VALUES ('$whosePage', '$_POST[themeText]', '0', 'account', '$_SESSION[login]')");
        error(0);
    } else if (queryPostTrue(['newComment','newCommentId'])){
        $mysql->query("INSERT INTO `comments` ( `account`, `comment`, `likes`,`parentComment`, `parentTheme`)
      VALUES ('$_SESSION[login]', '$_POST[newComment]', '0',  '0', '$_POST[newCommentId]')");
        error(0);
    }
    else {
        error(1);
    }
}
//лайки, добавляем лайки комменту без добавления в simp в базе users
if (queryPostTrue(['likeComm'])){
    addLike('comments','likeComm', false);
    error(0);
}
if (queryPostTrue(['likeTheme'])){
    addLike('posts','likeTheme', false);
    error(0);
}


//get useful info about...
$resultAboutAcc = $mysql->query("SELECT * FROM `users` WHERE `login` = '$whosePage'");
$AboutAccUsers = $resultAboutAcc->fetch_assoc();
$resultAboutAcc = $mysql->query("SELECT * FROM `posts` WHERE `account` = '$whosePage'");
$AboutAccTheme = $resultAboutAcc->fetch_assoc();
$resultAboutAcc = $mysql->query("SELECT * FROM `comments` WHERE `account` = '$whosePage'");
$commentsUser = 0;
if ($resultAboutAcc) {
    while ($AboutAccComments = $resultAboutAcc->fetch_assoc()) {
        $commentsUser++;
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

<body style="background-color: rgb(188,183,183);">
<?php include('includes/header.php'); ?>
<div class="container float-left" style="width: 350px;background-color: rgba(113,98,98,0);">
<!--лого, кнопки под лого-->
    <div class="row" style="background-color: #4f4747;">
        <div class="col"><img src="assets/img/aldain-austria-316143-unsplash.jpg" style="width: 250px;height: 300px;">
            <button class="btn btn-primary" type="button">подпписаться</button>
            <button class="btn btn-primary" type="button">написать</button>
        </div>
    </div>
<!--подписки-->
    <div class="row" style="margin-top: 30px;background-color: #4f4747;">
        <div class="col-lg-12">
            <p style="color: rgb(255,255,255);">21 подпписок</p>
        </div>
        <div class="col-lg-4"><img class="rounded-circle float-left" src="assets/img/aldain-austria-316143-unsplash.jpg"
                                   style="width: 60px;height: 50px;"></div>
        <div class="col-lg-8">
            <p class="d-block" style="color: rgb(255,255,255);">Пользователь</p>
            <p class="d-block" style="color: rgb(255,255,255);width: 100px;">статус</p>
        </div>
        <div class="col-lg-4"><img class="rounded-circle float-left" src="assets/img/aldain-austria-316143-unsplash.jpg"
                                   style="width: 60px;height: 50px;"></div>
        <div class="col-lg-8">
            <p class="d-block" style="color: rgb(255,255,255);">Пользователь</p>
            <p class="d-block" style="color: rgb(255,255,255);width: 100px;">статус</p>
        </div>
    </div>
<!--подписчики-->
    <div class="row" style="margin-top: 30px;background-color: #4f4747;">
        <div class="col-lg-12">
            <p style="color: rgb(255,255,255);">21 подписчик</p>
        </div>
        <div class="col-lg-4"><img class="rounded-circle float-left" src="assets/img/aldain-austria-316143-unsplash.jpg"
                                   style="width: 60px;height: 50px;"></div>
        <div class="col-lg-8">
            <p class="d-block" style="color: rgb(255,255,255);">Пользователь</p>
            <p class="d-block" style="color: rgb(255,255,255);width: 100px;">статус</p>
        </div>
        <div class="col-lg-4"><img class="rounded-circle float-left" src="assets/img/aldain-austria-316143-unsplash.jpg"
                                   style="width: 60px;height: 50px;"></div>
        <div class="col-lg-8">
            <p class="d-block" style="color: rgb(255,255,255);">Пользователь</p>
            <p class="d-block" style="color: rgb(255,255,255);width: 100px;">статус</p>
        </div>
    </div>
</div>


<div class="container float-right" style="width: 600px;">
<!--    info about account-->
    <div class="row d-block float-right" style="background-color: #786d6d;">
        <div class="col d-block float-right" style="width: 600px;background-color: #8b7f7f;">
            <p class="d-inline-block">Пользователь <?=$AboutAccUsers['login']?></p>
            <p class="d-inline-block float-right">Заходил <?=$AboutAccUsers['time']?></p>
            <p class="d-block"><?=$AboutAccUsers['status']?></p>
        </div>
    </div>
    <div class="row d-block float-right" style="background-color: #786d6d;">
        <div class="col d-block" style="width: 600px;">
            <p>Дата рождения    <?=$AboutAccUsers['birthday']?></p>
            <p>Пол   <?=$AboutAccUsers['gender']?></p>
            <a href="#" style="background-color: #20d086;">темы от пользователя</a></div>
    </div>
    <div class="row float-right">
        <div class="col float-right" style="background-color: #786d6d;width: 600px;margin-bottom: 30px;">
            <p class="d-inline-block" style="margin: 10px;color: rgb(49,190,37);font-size: 20px;"><?=$AboutAccUsers['simp']?> симпатий</p>
            <p class="d-inline-block" style="margin: 10px;color: rgb(49,190,37);font-size: 20px;">0 розыгрышей</p>
            <p class="d-inline-block" style="margin: 10px;color: rgb(49,190,37);font-size: 20px;"><?=$commentsUser?> сообщений</p>
        </div>
    </div>

<!--change content of page-->
    <div class="row float-right">
        <div class="col float-right" style="background-color: #786d6d;width: 600px;margin-bottom: 0px;">
            <p class="d-inline-block"
               style="margin: 10px;color: rgb(49,190,37);font-size: 20px;background-color: #ae2929;"><a href="#"
                                                                                                        style="color: rgb(50,213,125);">Стена</a>
            </p>
            <p class="d-inline-block"
               style="margin: 10px;color: rgb(49,190,37);font-size: 20px;background-color: #ae2929;"><a href="#"
                                                                                                        style="color: rgb(40,195,56);">Недавние
                    сообщения</a></p>
            <p class="d-inline-block"
               style="margin: 10px;color: rgb(49,190,37);font-size: 20px;background-color: #ae2929;"><a href="#"
                                                                                                        style="color: rgb(37,218,55);">История
                    блокировок</a></p>
        </div>
    </div>
<!--create post-->
    <form action="Account.php" method="post">
    <div class="row float-right">
        <div class="col float-right" style="background-color: #786d6d;width: 600px;margin-bottom: 20px;"><img
                    class="rounded-circle float-left" src="assets/img/aldain-austria-316143-unsplash.jpg"
                    style="height: 60px;width: 60px;">
            <button class="btn btn-primary" type="button">Ссыл</button>
            <button class="btn btn-primary" type="button">Скрыт</button>
            <button class="btn btn-primary"
                    type="button">УдалССыл
            </button>
            <button class="btn btn-primary" type="button">IMG</button>
            <button class="btn btn-primary" type="button">ВыравСлев</button>
            <button class="btn btn-primary" type="button">Назад</button>
            <button class="btn btn-primary"
                    type="button">СМАЙЛ
            </button>
            <textarea name="themeText" style="width: 500px;height: 150px;" placeholder="пиши..."></textarea>
            <button class="btn btn-primary" name="submit" type="submit">Ответить</button>
        </div>
    </div>
    </form>

<!--pages-->
    <div class="row float-right">
        <div class="col float-right" style="background-color: #786d6d;width: 600px;margin-bottom: 0px;">
<!--    узнаем сколько всего надо страниц-->
    <?php
    $resultPosts = $mysql->query("
    SELECT * FROM `posts` WHERE
     `WherePosted` = 'account' and `themeTitle`='$whosePage' 
     ORDER BY `id` DESC");
    $posts = 0;
    while ($post = $resultPosts->fetch_assoc()) {
        $posts++;
    }
    $pages = ceil($posts/5);

    for($i = 1; $i<($pages>=5 ? 5:$pages+1); $i++){
    ?>
        <p class="d-inline-block" style="margin: 0px;color: rgb(49,190,37);font-size: 20px;background-color: #ae2929;padding: 6PX;">
            <a href="Account.php?whosePage=<?=$whosePage?>&page=<?=$i?>" style="color: rgb(252,252,252);"><?=$i?></a
        </p>

    <?php
    }
    if ($pages>=5){ ?>
        <p class="d-inline-block" style="margin: 0px;color: rgb(49,190,37);font-size: 20px;background-color: #ae2929;padding: 6PX;">
            <a href="Account.php?whosePage=<?=$whosePage?>&page=<?=$pages?>" style="color: rgb(252,252,252);"> ... <?=$pages?></a
            </p>
    <?php } ?>
        </div>
    </div>

<!--posts-->
    <?php
    $resultPosts = $mysql->query("SELECT * FROM `posts` WHERE `WherePosted` = 'account' and `themeTitle`='$whosePage' ORDER BY `id` DESC");
    $count = 0;
    $uselessPages = 5*$_GET['page']-5;
        //проходим не нужные посты, начиная с первой страницы
    $post = $resultPosts->fetch_assoc();
    while ($post !=null and $count<$uselessPages) {
            $count++;
            $post = $resultPosts->fetch_assoc();
        }
    $count = 0;
        while ($post !=null and $count<5) {
            addPostForAccount($post);
            $count++;
            $post = $resultPosts->fetch_assoc();
        }
    ?>
</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script>
    $(window).on("scroll", function(){
        $('input[name="scroll"]').val($(window).scrollTop());
    });

    <?php if (!empty($_REQUEST['scroll'])): ?>
    $(document).ready(function(){
        window.scrollTo(0, <?php echo intval($_REQUEST['scroll']); ?>);
    });
    <?php endif; ?>
</script>
</body>

</html>