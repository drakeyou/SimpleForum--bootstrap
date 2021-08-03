<?php
session_start();
include('functions.php');
if (isset($_GET['theme']) and $_GET['theme']!==""){
    $_SESSION['theme']=$_GET['theme'];
    //обнуляем, если были утсановлены в прошлый раз
    unset($_SESSION['parentComment']);
    unset($_SESSION['idCommToAnswer']);
}


//add new comment

//addres to someone
if (queryPostTrue(['answerComm'])){
    //получаем id кому будем отвечать
    $_SESSION['idCommToAnswer'] = $_POST['answerComm'];
    //для ввода в textarea
    $commentsResult6 = $mysql->query("SELECT * FROM `comments` WHERE `id` = '$_POST[answerComm]'");
    $commentToAnswer = $commentsResult6->fetch_assoc();

    $_SESSION['parentComment'] = $commentToAnswer['account'];
}
//create post
if (queryPostTrue(['newComment'])){
    if (!(isset($_SESSION['parentComment']))){
        $_SESSION['idCommToAnswer']=0;
    }
    $mysql->query("INSERT INTO `comments` ( `account`, `comment`, `likes`,`parentComment`, `parentTheme`)
    VALUES ('$_SESSION[login]', '$_POST[newComment]', '0',  '$_SESSION[idCommToAnswer]', '$_SESSION[theme]')");
    error(0);
}

//лайки, добавляем лайки комменту и в simp в базе users
if (queryPostTrue(['likeComm'])){
    addLike('comments','likeComm',true);
    error(0);
}
if (queryPostTrue(['likeTheme'])){
    addLike('posts','likeTheme',true);
    error(0);
}


//take info about page
$idTheme = $_SESSION['theme'];
$themeResult = $mysql->query("SELECT * FROM `posts` WHERE `id` = '$idTheme'");
$theme = $themeResult->fetch_assoc();
$commentsResult1 = $mysql->query("SELECT * FROM `comments` WHERE `parentTheme` = '$idTheme'");
$commentsResult3 = $mysql->query("SELECT DISTINCT `account` FROM `comments` WHERE `parentTheme` = '$idTheme'");

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

<body style="background-color: rgb(130,113,113);">

<?php include('includes/header.php'); ?>

<p class="d-block" style="background-color: #a48a8a;width: 800px;font-size: 30px;"><?=$theme['WherePosted']?></p>

<div class="container">
<!--    theme of post and buttons-->
    <div class="row">
        <div class="col d-block">
            <p class="d-block" style="background-color: #a48a8a;width: 800px;font-size: 30px;"><?=$theme['themeTitle']?></p>
        </div>
        <div class="col-lg-12 d-block">
            <button class="btn btn-primary" type="button">1</button>
            <button class="btn btn-primary" type="button">2</button>
            <button class="btn btn-primary" type="button">3</button>
            <button class="btn btn-primary" type="button">15</button>
        </div>
    </div>

<?php
//получаем инфу о количестве вложенных комментов в кокнретных комментах
$commentNesting = array();
$count = 0;
while($comment = $commentsResult1->fetch_assoc()){
    if ($comment['parentComment']!=0){
        $commentNesting[$comment['parentComment']]++;
    }
}
//получаем инфу об аккаунтах
$accountsInTheme = array();
//добавляем сразу создателя темы
$resultAccounts = $mysql->query("SELECT * FROM `users` WHERE `login` = '$theme[account]'");
$accountsInTheme[$theme['account']] = $resultAccounts->fetch_assoc();
//добавляем комментаторов
while($comment = $commentsResult3->fetch_assoc()){
   $resultAccounts = $mysql->query("SELECT * FROM `users` WHERE `login` = '$comment[account]'");
   $accountsInTheme[$comment['account']] = $resultAccounts->fetch_assoc();
}
?>

<!--    main theme-->
    <div class="row">
        <div class="col-lg-1 d-inline-block align-self-center" style="width: 300px;">
            <img class="rounded-circle" src="assets/img/aldain-austria-316143-unsplash.jpg"  style="width: 60px;height: 60px;">
        </div>
        <div class="col d-inline-block align-self-center" style="width: 300px;">
            <p class="d-inline-block" style="background-color: #a48a8a;width: auto;">
                <a href="Account.php?whosePage=<?=$theme['account']?>">Пользователь <?=$theme['account']?></a>
            </p>
            <p class="d-inline-block" style="background-color: #a48a8a;width: auto;"><?=$accountsInTheme[$theme['account']]['simp']?> simp</p>
            <p class="d-inline-block" style="background-color: #a48a8a;width: auto;">&nbsp;<?=$accountsInTheme[$theme['account']]['birthday']?></p>
            <p class="d-block" style="background-color: #a48a8a;width: 800px;"><?=$theme['themeText']?></p>

            <?php if (isset($_SESSION['login']) and $_SESSION['login']!== $theme['account']){?>
            <div class="d-inline-block">
            <form action="ViewPost.php" method="post" >
            <button class="btn btn-primary d-inline-block" name="likeTheme" value="<?=$theme['id']?>" type="sumbit">
                <i class="fa fa-heart"></i><?=$theme['likes']?>
            </button>
                </form >
            </div>
            <?php } ?>

            <?php if (isset($_SESSION['login']) and $_SESSION['login']!== $theme['account']){?>
            <div class="dropdown d-inline-block">
                <button class="btn btn-primary dropdown-toggle d-inline-block" data-toggle="dropdown" aria-expanded="false" type="button"><i class="fa fa-ellipsis-h"></i>
                </button>
                <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item" role="presentation" href="CreatePost.php?badTheme=<?=$theme['id']?>">Пожаловаться</a>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>


    <?php
    //create comments
    //берем комменты темы(вложенность 0)
    $commentsNestingTheme = array();
    $commentsResult2 = $mysql->query("SELECT * FROM `comments` WHERE `parentTheme` = '$idTheme' AND `parentComment` = 0");
    while($comment = $commentsResult2->fetch_assoc()){
        $commentsNestingTheme[] = $comment;
    }

    //теперь создаем комменты
    for($i=0;$i<count($commentsNestingTheme);$i++){
        //берем нужные вложенные комменты
        $commentsNestingComment = array();
        $commentsResult4 = $mysql->query("SELECT * FROM `comments` WHERE `parentTheme` = '$idTheme' AND `parentComment` = {$commentsNestingTheme[$i]['id']} ");
        while($comment = $commentsResult4->fetch_assoc()){
            $commentsNestingComment[] = $comment;
        }
        //теперь у нас есть коомент под $i индексом и его под комменты
        //также мы можем легко понять, что так как коммент появляется раньше, чем его подкомменты, то
        // создав массив $comments из коммента вначале и комментов внутри этого подмассива мы получим нужный результат
        $comments = array();
        $comments[] = $commentsNestingTheme[$i];
        //теперь создаем массив comments, просто соеденить его плюсом не можем, так как там пересекаются ключи(0,1,2 итд)
        for($f=0;$f<count($commentsNestingComment);$f++) {
            $comments[] = $commentsNestingComment[$f];
        }
        //выводим комменты
        for($f=0;$f<count($comments);$f++) {
            createComment($comments[$f], $commentNesting, $accountsInTheme);
        }
        //в данной позиции мы уже прогнали все комменты(для темы и для самого коммента)
        //теперь идем на новый заход за новым комментом для темы
    }
    ?>


<!--    create comment-->
    <?php if (isset($_SESSION['login'])){?>
    <form action="ViewPost.php" method="post">
    <div class="row">
        <div class="col float-right" style="background-color: #786d6d;width: 600px;margin-bottom: 20px;">
            <img class="rounded-circle float-left" src="assets/img/aldain-austria-316143-unsplash.jpg" style="height: 60px;width: 60px;">
            <button class="btn btn-primary" type="button">Ссыл</button>
            <button class="btn btn-primary" type="button">Скрыт</button>
            <button class="btn btn-primary" type="button">УдалССыл</button>
            <button class="btn btn-primary" type="button">IMG</button>
            <button class="btn btn-primary" type="button">ВыравСлев</button>
            <button class="btn btn-primary" type="button">Назад</button>
            <button class="btn btn-primary" type="button">СМАЙЛ </button>
            <textarea name="newComment" style="width: 500px;height: 50px;"
            placeholder="пиши ответ..."><?php if (isset($_SESSION['parentComment']) and $_SESSION['parentComment']!==""){ echo $_SESSION['parentComment'] . ", ";}?></textarea>
            <button class="btn btn-primary" type="submit"><i class="fa fa-chevron-right"></i></button>
        </div>
    </div>
        <input type="hidden" name="scroll" value="">
    </form>
    <?php } ?>

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