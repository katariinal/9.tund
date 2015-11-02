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
 
    //profiilipildi ülesalaadimine 
    //http://www.w3schools.com/php/php_file_upload.asp
    
    $target_dir = "profile_pics/";
    //faili nimi oleks kasutaja id .jpg
    
    
    $target_file = $target_dir . $_SESSION['user_id'].".jpg";
        //kas kasutajal on profiilipilt olemas
    if(file_exists($target_file)){
        $profile_image_url = $target_file;
    }
    
    $uploadOk = 1;
    
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 1024000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                header("Location: data.php");
                exit();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
    
    if(isset($_GET["delete_image"])){
        //kustutan pildi/faili
        unlink($profile_image_url);
        header("Location: data.php");
        exit();
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

<br>
<h3>Profiilipilt</h3>

<?php if(isset($profile_image_url)): ?>

    <div style="width:200px; height:200px; background-image: url(<?=$profile_image_url;?>); background-size: cover; background-position-x: center; background-position-y: center;"></div>
    <br><a href="?delete_image=1">Delete image</a>
        
<?php else: ?>

<form action="data.php" method="post" enctype="multipart/form-data">
    Vali profiilipilt:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>

<?php endif; ?>
<h2>Galerii</h2>
<?php

    //saada kätte kõik failid, mis on kaustas 
    $images = scandir("profile_pics/");
    //var_dump($images);
    
    //iga pildi kohta, tekitame lingi 
    for($i = 0; $i< count($images); $i++){
        
        if($images[$i] != "." && $images[$i]! = ".."){
            
            $link = "profile_pics/".$images[$i];
            $name = $images[$i];
            
            echo '<a href="'.$link.'">'.$name.'</a><br>';
        }
    }

?>