<?php
include('config.php');

//получаем список тем по категориям на начальной странице форума
function getMenu()
{
    $groupNameBefore =0 ;
    $result = $GLOBALS['mysql']->query("SELECT * FROM `dataforum`");
    while ($user = $result->fetch_assoc()) {

        if ($groupNameBefore !== $user['groupName']) {
            ?>
            <br><p class="d-block"><?= $user['groupName'] ?></p>
        <?php } ?>

        <a class="d-block" href="index.php?catalog=<?=$user['catalog']?>" style="color: rgb(250,253,255);"><?= $user['catalog'] ?></a>

        <?php

        $groupNameBefore = $user['groupName'];
    }
}

//менюшка с выбором каталога
function selectCatalog(){ ?>
    <select class="d-block" name="catalog">
        <?php
        $groupNameBefore = 0;
        $result = $GLOBALS['mysql']->query("SELECT * FROM `dataforum`");
        while ($user = $result->fetch_assoc()) {

            //открываем тег группы названия каталогов
            if ($groupNameBefore !== $user['groupName']) {
                ?>
                <optgroup label=<?= $user['groupName'] ?>>
            <?php } ?>

            <option value="<?=$user['catalog']?>"><?= $user['catalog'] ?></option>

            <?php

            $groupNameBefore = $user['groupName'];

            //закрываем тег названия группы каталогов
            if ($groupNameBefore !== $user['groupName']) {
                ?>
                </optgroup>
                <?php
            }
        } ?>
    </select>
    <?php
}

//проверям отправил ли пользователь нам данные
function queryPostTrue($queryPost){
    for ($i = 0;$i<count($queryPost);$i++){
        if (!(isset($_POST[$queryPost[$i]])) or $_POST[$queryPost[$i]] === ""){
            return False;
        }
    }
    return True;
}
//разные ошибки
function error($number){
    $_SESSION['error'] = $number;
    $_POST=array();
}
//комменты для тем на форуме
function createComment($comment,$commentNesting,$accountsInTheme){
    //если родитель - сама тема
    if ($comment['parentComment'] == 0){
        ?>
        <div class="row">
            <div class="col-lg-1 d-inline-block align-self-center" style="width: 300px;">
                <img class="rounded-circle" src="assets/img/aldain-austria-316143-unsplash.jpg" style="width: 60px;height: 60px;">
            </div>
            <div class="col d-inline-block align-self-center" style="width: 300px;">
                <p class="d-inline-block" style="background-color: #a48a8a;width: auto;">
                    <a href="Account.php?whosePage=<?=$comment['account']?>&page=1">Пользователь <?=$comment['account']?></a>
                </p>
                <p class="d-inline-block" style="background-color: #a48a8a;width: auto;">&nbsp;<?=$accountsInTheme[$comment['account']]['simp']?>&nbsp;simp</p>
                <p class="d-inline-block" style="background-color: #a48a8a;width: auto;">&nbsp;<?=$accountsInTheme[$comment['account']]['birthday']?>&nbsp;</p>
                <p class="d-block" style="background-color: #a48a8a;width: 600px;"><?=$comment['comment']?></p>

                 <?php if (isset($_SESSION['login']) and $_SESSION['login']!== $comment['account']){?>
                <div class="d-inline-block">
                <form action="ViewPost.php" method="post" >
                <button class="btn btn-primary" name="likeComm" value="<?=$comment['id']?>" type="submit">
                    <i class="fa fa-heart"></i><?=$comment['likes']?>
                </button>
                    <input type="hidden" name="scroll" value="">
                </form>
                </div>
                <?php } ?>


                <div class="d-inline-block">
                <form action="ViewPost.php" method="post" >
                <button class="btn btn-primary" name="answerComm" value="<?=$comment['id']?>" type="sumbit">
                    <i class="fa fa-comment-o"></i><?=$commentNesting[$comment['id']]?>
                </button>
                    <input type="hidden" name="scroll" value="">
                </form>
                </div>

        <?php if (isset($_SESSION['login']) and $_SESSION['login']!== $comment['account']){?>
                <div class="dropdown d-inline-block">
                    <button class="btn btn-primary dropdown-toggle d-inline-block" data-toggle="dropdown" aria-expanded="false" type="button">
                        <i class="fa fa-ellipsis-h"></i>
                    </button>
                    <div class="dropdown-menu" role="menu">
                        <a class="dropdown-item" role="presentation" href="CreatePost.php?badComm=<?=$comment['id']?>">Пожаловаться</a>
                    </div>
                </div>
            <?php } ?>

            </div>
        </div>
    <?php

    }
    //если родитель - предыдущий коммент
    else if ($comment['parentComment'] > 0){ ?>

        <div class="row">
            <div class="col-lg-1 offset-lg-1 d-inline-block align-self-center" style="width: 300px;height: auto;"><img
                        class="rounded-circle float-left" src="assets/img/aldain-austria-316143-unsplash.jpg"
                        style="width: 60px;height: 60px;"></div>
            <div class="col-lg-9 offset-lg-0 d-inline-block align-self-center" style="width: 300px;">
                <p class="d-inline-block" style="background-color: #a48a8a;width: auto;">
                    <a href="Account.php?whosePage=<?=$comment['account']?>&page=1">Пользователь <?=$comment['account']?></a>
                </p>
                <p class="d-inline-block" style="background-color: #a48a8a;width: auto;">&nbsp; <?=$accountsInTheme[$comment['account']]['simp']?>&nbsp;simp</p>
                <p class="d-inline-block" style="background-color: #a48a8a;width: auto;">&nbsp; <?=$accountsInTheme[$comment['account']]['birthday']?></p>
                <p class="d-block" style="background-color: #a48a8a;width: 600px;"><?=$comment['comment']?></p>

                <?php if (isset($_SESSION['login']) and $_SESSION['login']!== $comment['account']){?>
                <div class="d-inline-block">
                <form action="ViewPost.php" method="post" >
                <button class="btn btn-primary"  name="likeComm" value="<?=$comment['id']?>" type="sumbit">
                    <i class="fa fa-heart"></i>
                    <?=$comment['likes']?>
                </button>
                    <input type="hidden" name="scroll" value="">
                </form>
                </div>
                <?php } ?>

                <?php if (isset($_SESSION['login'])){?>
                <div class="d-inline-block">
                <form action="ViewPost.php" method="post">
                <button class="btn btn-primary" name="answerComm" value="<?=$comment['parentComment']?>" type="submit">
                    ответить
                </button>
                    <input type="hidden" name="scroll" value="">
                </form>
                </div>
                <?php } ?>

        <?php if (isset($_SESSION['login']) and $_SESSION['login']!== $comment['account']){?>
                <div class="dropdown d-inline-block">
                    <button class="btn btn-primary dropdown-toggle d-inline-block" data-toggle="dropdown"
                            aria-expanded="false" type="button">
                        <i class="fa fa-ellipsis-h"></i>
                    </button>
                    <div class="dropdown-menu" role="menu">
                        <a class="dropdown-item" role="presentation" href="CreatePost.php?badComm=<?=$comment['id']?>">Пожаловаться</a>
                    </div>
                </div>
            <?php } ?>

            </div>
        </div>
        <?php
    }
}
//показать посты на главной странице форума
function ViewPosts($catalog){
    //выбираем нужный каталог
    if ($catalog == "all"){
        $postsReturn = $GLOBALS['mysql']->query("SELECT * FROM `posts` WHERE `WherePosted` != 'account' ORDER BY `id` DESC");
    } else {
        $postsReturn = $GLOBALS['mysql']->query("SELECT * FROM `posts` WHERE `WherePosted` = '$catalog' ORDER BY `id` DESC");
    }


    while($posts = $postsReturn->fetch_assoc()){
        //находим последний коммент
        $commentReturn = $GLOBALS['mysql']->query("SELECT * FROM `comments` WHERE `parentTheme` = '$posts[id]' ORDER BY `id` DESC");
        $lastComment = $commentReturn -> fetch_assoc();
    ?>
    <div class="row" style="background-color: rgba(198,50,50,0.12);padding-top: 5px;padding-bottom: 5px;">
        <div class="col">
            <p class="d-block" style="color:orange;">
                <a href="ViewPost.php?theme=<?=$posts['id']?>" style="color: rgb(241,197,39);background-color: #4a7d49;" ><?=$posts['themeTitle']?></a>
            </p>
            <p class="float-left" style="padding: 2px;"><?=$posts['account']?>&nbsp;</p>
            <p class="float-left" style="padding: 2px;"><?=$posts['time']?></p>
            <p class="float-left" style="padding: 2px;">&nbsp; &nbsp;<i class="fa fa-comment"></i>&nbsp;4&nbsp;
            </p>
        </div>
        <div class="col-lg-2"><img class="rounded-circle float-right"
                                   src="assets/img/aldain-austria-316143-unsplash.jpg"
                                   style="width: 50px;height: 50px;"></div>
        <div class="col-lg-3">
            <p class="d-block float-none" style="margin: 0px;width: 136.4px;"><?=$lastComment['account']?></p>
            <p class="d-block float-none" style="margin: 0px;min-width: 120px;">&nbsp;<?=$lastComment['time']?></p>
        </div>
    </div>

    <?php
    }
}
//добавлям лайк к комменатрию/посту, обновляем кол во симпу тому, кто владеет постом/комментом
//from - лайк к комменту/посту
function addLike($from,$namePost, $addSimp){
    $mysql = $GLOBALS['mysql'] ;
    $resultLikes = $mysql->query("SELECT * FROM `$from` WHERE `id` = '$_POST[$namePost]'");
    $LikesBefore = $resultLikes->fetch_assoc();
    $newLike = $LikesBefore['likes'] + 1;
    $accountToIncrease = $LikesBefore['account'];

    $resultLikes = $mysql->query("SELECT * FROM `comments` WHERE `account` = '$accountToIncrease'");
    $simp = 0;
    while ($LikesBefore = $resultLikes->fetch_assoc()){
        $simp += $LikesBefore['likes'];
    }
    $resultLikes = $mysql->query("SELECT * FROM `posts` WHERE `account` = '$accountToIncrease'");
    while ($LikesBefore = $resultLikes->fetch_assoc()){
        $simp += $LikesBefore['likes'];

    }
    $simp++;

    $mysql->query("UPDATE `$from` SET `likes` = '$newLike', `time` = `time` WHERE `id` = '$_POST[$namePost]'");
    if ($addSmip) {
        $mysql->query("UPDATE `users` SET `simp` = '$simp', `time` = `time` WHERE `login` = '$accountToIncrease'");
    }
}
function addPostForAccount($post){ ?>

    <div class="row float-right d-lg-flex align-items-lg-start">
        <div class="col float-right" style="background-color: #786d6d;width: 600px;margin-bottom: 0px;"><img
                    class="rounded-circle float-left" src="assets/img/aldain-austria-316143-unsplash.jpg"
                    style="height: 60px;width: 60px;">
            <p class="text-break d-lg-flex align-items-lg-start" style="margin-bottom: 0px;"><?=$post['account']?></p>
            <p class="text-break d-lg-flex align-items-lg-start"><?=$post['time']?></p>
            <p class="text-break d-lg-flex align-items-lg-start"><?=$post['themeText']?></p>

            <?php if (isset($_SESSION['login']) and $_SESSION['login']!== $post['account']){?>
            <div class="d-inline-block">
            <form action="Account.php?whosePage=<?=$_SESSION['whosePage']?>&page=<?=$_GET['page']?>" method="post" >
                <button class="btn btn-primary" type="submit" name="likeTheme" value="<?=$post['id']?>">
                    <i class="fa fa-thumbs-up"></i>
                    <?=$post['likes']?>
                 </button>
                <input type="hidden" name="scroll" value="">
                </form>
            </div>
            <?php } ?>

        </div>
    </div>

    <!--    берем имя комментатора, которому отвечаем и id поста в котором находится этот коммент-->
    <?php
    if (queryPostTrue(['answerComm'])){
        $mas = array();
        $mas = explode(" ", $_POST['answerComm']);
        $_SESSION['parentComment'] = $mas[0];
        $_SESSION['parentPost'] = $mas[1];
    }
    ?>

    <!--comemnts before-->
    <?php

    $comments = 0;
    $resultComments = $GLOBALS['mysql']->query("SELECT * FROM `comments` WHERE `ParentTheme` = '$post[id]' ");
    while($comment = $resultComments->fetch_assoc()) {
        $comments++;
    }


    if (!queryPostTrue(['seeAll'])){
    //before 'посмотреть предыдущие'
    if ($comments>3){
    ?>
        <form action="Account.php?whosePage=<?=$_SESSION['whosePage']?>&page=<?=$_GET['page']?>" method="post" >
    <div class="row float-right d-lg-flex align-items-lg-start">
        <div class="col float-right" style="background-color: #786d6d;width: 600px;margin-bottom: 0px;">
            <p class="d-inline-block" style="margin: 0px;color: rgb(49,190,37);font-size: 20px;background-color: #ae2929;padding: 6PX;">
                <button class="btn btn-primary" type="submit" name="seeAll" value="<?=$post['id']?>" style="background-color: #ae2929">
                    посмотреть предыдщуие <?=$comments-3?> комменатрия(й)
                </button>
            </p>
        </div>
        <input type="hidden" name="scroll" value="">
    </div>
        </form>
        <?php }
    //получаем последние 3 коммента от старого к новому
    $resultComments = $GLOBALS['mysql']->query
    ("SELECT * FROM 
              (SELECT * FROM `comments` 
              WHERE `ParentTheme` = '$post[id]' 
              ORDER BY `id` DESC LIMIT 3) a 
    ORDER BY `id` asc");
    } else if (queryPostTrue(['seeAll'])){ ?>
    <!--after seeAll-->
    <?php
    $resultComments = $GLOBALS['mysql']->query("
    SELECT * FROM `comments` WHERE `ParentTheme` = '$post[id]'
    ");
    }

    //now we add comment
    while($comment = $resultComments->fetch_assoc()){ ?>
    <div class="row float-right d-lg-flex align-items-lg-start">
        <div class="col float-right" style="background-color: #786d6d;width: 600px;margin-bottom: 0px;"><img
                    class="rounded-circle float-left" src="assets/img/aldain-austria-316143-unsplash.jpg"
                    style="height: 60px;width: 60px;">
            <p class="text-break d-lg-flex align-items-lg-start" style="margin-bottom: 0px;"><?=$comment['account'] ?></p>
            <p class="text-break d-lg-flex align-items-lg-start"><?=$comment['comment'] ?></p>
            <p class="text-break d-lg-flex align-items-lg-start"><?=$comment['time'] ?></p>


            <?php if (isset($_SESSION['login'])){?>
                <div class="d-inline-block">
                    <form action="Account.php?whosePage=<?=$_SESSION['whosePage']?>&page=<?=$_GET['page']?>" method="post">
                        <button class="btn btn-primary" name="answerComm" value="<?=$comment['account'] . " " . $comment['parentTheme']?>" type="submit">
                            Ответить
                        </button>
                    </form>
                </div>
            <?php } ?>

                        <?php if (isset($_SESSION['login']) and $_SESSION['login']!== $post['account']){?>
            <div class="d-inline-block">
                <form action="Account.php?whosePage=<?=$_SESSION['whosePage']?>&page=<?=$_GET['page']?>" method="post" >
                    <button class="btn btn-primary" type="submit" name="likeComm" value="<?=$comment['id']?>">
                        <i class="fa fa-thumbs-up"></i>
                        <?=$comment['likes']?>
                    </button>
                    <input type="hidden" name="scroll" value="">
                </form>
            </div>
                        <?php } ?>

        </div>
    </div>
    <?php }
    ?>


    <!--    //добавить коммент под пост-->
    <form action="Account.php?whosePage=<?=$_SESSION['whosePage']?>&page=<?=$_GET['page']?>" method="post">
    <div class="row float-right d-lg-flex align-items-lg-start">
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
            <textarea name="newComment" style="width: 500px;height: 50px;" placeholder="пиши..."><?php
                if (
                        (isset($_SESSION['parentComment']) and $_SESSION['parentComment']!=="" )
                        and
                        (isset($_SESSION['parentPost']) and $_SESSION['parentPost']!=="" )
                        and
                        ($post['id']==$_SESSION['parentPost'])
                )
                {
                    echo $_SESSION['parentComment'] . ", ";
                }
                ?></textarea>
            <input type="hidden" name="newCommentId" value="<?=$post['id']?>">
            <button class="btn btn-primary" name="submit" type="submit"><i class="fa fa-chevron-right"></i></button>
        </div>
    </div>
        <input type="hidden" name="scroll" value="">
    </form>
    <?php

}


?>