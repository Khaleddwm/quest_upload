<?php

// upload.php

// chemin vers un dossier sur le serveur qui va recevoir les fichiers uploadés
// (attention ce dossier doit être accessible en écriture)
$uploadDir = 'uploads/';
// retour du nom du fichier
$filename = $_FILES['avatar']['name'];
// taille de fichier accéptés
$sizeMax = 1048576;
// Extension accéptées
$extensions = ['jpg', 'jpeg', 'png', 'gif'];

// Début des vérifications de sécurité...
if (isset($_POST['upload'])) {
    for($i = 0; $i < count($_FILES['avatar']['name']); $i++) {
        // obtenir uniquement l'extension (sans le point)
        $extension = pathinfo($_FILES['avatar']['name'][$i], PATHINFO_EXTENSION);
        // taile du fichier uploadé
        $size = $_FILES['avatar']['size'][$i];
        // Si l'extension n'est pas dans le tableau
        if(!in_array($extension, $extensions)) {
            $error =  'Votre fichier "' . $filename[$i] . '" n\'est pas conforme' . '<br>' .
            'Vous devez uploader un fichier de type png, gif, jpg, txt ou doc...';
        }
        // Si la taille est trop volumineuse
        if($size > $sizeMax) {
            $error = 'Votre fichier "' . $filename[$i] . '" n\'est pas conforme' . '<br>' .
            'Le fichier est trop gros...';
        }
        //S'il n'y a pas d'erreur, on upload
        if(!isset($error)) { 
            // A unique name is concatenated with a dot and the $extention avec l'extension récupérée
            $filenameUniq = uniqid() . '.' .$extension;
            // Si la fonction renvoie TRUE, c'est que ça a fonctionné...
            if(move_uploaded_file($_FILES['avatar']['tmp_name'][$i], $uploadDir . $filenameUniq)) {
                $message[] = 'Upload du fichier "' . $filename[$i] . '" effectué avec succès !' .'<br>';
            // Sinon (la fonction renvoie FALSE).
            } else {   
                $message[] = 'Echec de l\'upload "' . $filename[$i] . '" !'  .'<br>';
            }
        } else {
            echo $error;
            return $message;
        }
    }
}

// liste image upload
$file = new FilesystemIterator('uploads');
// Option Delete
if (isset($_POST['delete'])) {
    unlink($_POST['delete']);
    header('Location:upload.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Quest Upluoad</title>
</head>

<body style="background-image:url('https://jeromeobiols.com/wordpress/wp-content/uploads/photo-montagne-vallee-blanche-chamonix-mont-blanc.jpg'); background-repeat:no-repeat; background-attachment:fixed; background-position:center;">
    <div>
        <h1 style="background-color:black; color:white; text-align:center">Quest Upload</h1>
        <h2 style="text-align:center; background-color:darkturquoise; color:yellow" for="imageUpload">Upload image</h2></br>
    </div>
    <div class="d-flex justify-content-center">
        <form style="display:flex; flex-direction:column" class="" method="POST" action="upload.php" enctype="multipart/form-data">
            <!-- On limite le fichier à 100Ko -->
            <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
            <input class="btn btn-light" type="file" name="avatar[]" multiple="multiple" required/></br>
            <button class="btn btn-success" type="submit" name="upload">Upload</button>
            <a class="btn btn-light" style="margin-top:20px; color:red" href="upload.php">Rafraichir</a>
        </form>
    </div>
    <br><h3 style="background-color:white; text-align:center">
        <?php 
        if (isset($message)) {
            foreach ($message as $value) {
            echo $value;
            }
        }
        ?>
        </h3><br>
    <div>
    
        <br><h2 style="text-align:center; background-color:darkturquoise; color:yellow">Avatar en stock :</h2><br>
    </div>
    <div style="">
        <form  style="" method="post" action="upload.php">
        <?php
        foreach ($file as $fileinfo) {
            echo '<figure class="col-3" style="border:thin #c0c0c0 solid; display:inline-flex; flex-flow:column; padding: 5px; max-width: 220px; margin: auto;">';
            echo '<img style="max-width:220px; max-height:150px;" src="' . $fileinfo . '" />';
            echo '<figcaption style="background-color:#222; color:#fff; font:italic smaller sans-serif; padding:3px; text-align:center;">' . $fileinfo->getFilename() . '</figcaption>';
            if (file_exists($fileinfo)) {
                echo '<button class="btn btn-warning" type="submit"  name="delete" value="' . $fileinfo . '">Delete</button>';
                echo '</figure>';
            } 
        }
        
        ?>
        </form>
    </div>        
</body>
</html>