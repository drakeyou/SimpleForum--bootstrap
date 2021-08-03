<div class="row" style="background-color: #0eb752;margin-bottom: 30px;">

    <div class="col offset-lg-0" style="height: 50px; padding-left:50px">
        <a href="/"><i class="fa fa-star" style="font-size: 40px;"></i></a>
    </div>

    <div class="col-lg-3 offset-lg-0" style="height: 50px;">
        <input type="text">
    </div>

<!--    если пользователь не зареган-->
    <?php if(isset($_SESSION['login']) === FAlSE) {?>
    <div class="col-lg-3 offset-lg-0" style="height: 50px;">
        <button class="btn btn-primary" type="button">
            <a href="Login.php" style="color: rgb(239,243,247);">Вход</a>
        </button>
        <button class="btn btn-primary" type="button">
            <a href="Register.php" style="color: rgb(239,243,247);">Регистрация</a>
        </button>
    </div>
    <?php } ?>

    <!--    если пользователь зареган-->
    <?php if(isset($_SESSION['login'])) {?>
    <div class="col-lg-2 offset-lg-0" style="height: 50px;">
        <p class="d-inline-block float-left">
            <i class="fa fa-comments-o float-left" style="font-size: 25px;"></i>18
        </p>
        <p class="d-inline-block float-left">
            <i class="fa fa-bell-o float-left" style="font-size: 25px;"></i>2
        </p>
        <img class="rounded-circle float-left" src="assets/img/aldain-austria-316143-unsplash.jpg" style="width: 50px;height: 50px;">
    </div>

    <div class="col-lg-2">
        <div class="dropdown" style="width: 160px;height: 35px;"><button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button"><?= $_SESSION['login'] ?></button>
            <div class="dropdown-menu" role="menu">`
                <a class="dropdown-item" role="presentation" href="SettingsAccount.php">настройки</a>
                <a class="dropdown-item" role="presentation" href="Account.php?whosePage=<?=$_SESSION['login']?>&page=1">мой профиль</a>
                <a class="dropdown-item" role="presentation" href="logout.php">выйти</a>
            </div>
        </div>
    </div>
    <?php } ?>

</div>






