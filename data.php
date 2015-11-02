<?php
    // kõik mis seotud andmetabeliga, lisamine ja tabeli kujul esitamine
    require_once("functions.php");
    
    //kui kasutaja ei ole sisse logitud, suuna teisele lehele
    //kontrollin kas sessiooni muutuja olemas
    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
        //ära enne suunamist midagi tee 
        exit();
    }
    
    // aadressireale tekkis ?logout=1
    if(isset($_GET["logout"])){
        //kustutame sessiooni muutujad
        session_destroy();
        header("Location: login.php");
    }
 
    
?>

Tere, <?=$_SESSION['user_email'];?> <a href="?logout=1">Logi välja</a>

<br>

<?php if(isset($_SESSION['login_message'])):?>
<p style="color:green"><?=$_SESSION['login_message']?></p>
<?php 
//kustutan, et rohkem seda ei näidataks, ainult ühe korra, peale sisselogimist
unset($_SESSION['login_message']);
endif;?>

