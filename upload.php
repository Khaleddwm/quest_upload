<?php

// upload.php

// chemin vers un dossier sur le serveur qui va recevoir les fichiers uploadés
// (attention ce dossier doit être accessible en écriture)
$uploadDir = 'uploads/';
// retour du nom du fichier
$filename = $_FILES['avatar']['name'];
// taille de fichier accéptés
$taille_maxi = 1000000;

// Extension accéptées
$extensions = ['jpg', 'jpeg', 'png', 'gif'];

// Début des vérifications de sécurité...
for($i=0; $i < count($_FILES['avatar']['name']); $i++) {
    
    // obtenir uniquement l'extension (sans le point)
    $extension = pathinfo($_FILES['avatar']['name'][$i], PATHINFO_EXTENSION);
    // taile du fichier uploadé
    $taille = filesize($_FILES['avatar']['tmp_name'][$i]);
    
    // Si l'extension n'est pas dans le tableau
    if(!in_array($extension, $extensions)) { 
        $error =  'Votre fichier "' . $filename[$i] . '" n\'est pas conforme' .'<br>' .
        'Vous devez uploader un fichier de type png, gif, jpg, txt ou doc...';
    }
    
    // Si la taille est trop volumineuse
    if($taille > $taille_maxi) {
        $error = 'Votre fichier "' . $filename[$i] . '" n\'est pas conforme' .'<br>' .
        'Le fichier est trop gros...';
    }
    
    //S'il n'y a pas d'erreur, on upload
    if(!isset($erreur)) { 
        
        // A unique name is concatenated with a dot and the $extention avec l'extension récupérée
        $filenameUniq = uniqid() . '.' .$extension;
        
        // Si la fonction renvoie TRUE, c'est que ça a fonctionné...
        if(move_uploaded_file($_FILES['avatar']['tmp_name'][$i], $uploadDir . $filenameUniq)) {
            echo 'Upload du fichier "' . $filename[$i] . '" effectué avec succès !' .'<br>';
        
        // Sinon (la fonction renvoie FALSE).
        } else {   
            echo 'Echec de l\'upload "' . $filename[$i] . '" !'  .'<br>';
        }
    } else {
        echo $error;
    }
}

// liste image upload
$file = new FilesystemIterator('uploads');

// Option Delete
if (!empty($_POST['submit'])) {
    if(unlink($_POST['submit'])) {
        header("Location:upload.php");
    }
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
<body style="background-color:darkblue; color:white">
    <div>
        <h1 style="background-color:black; text-align:center">Quest Upload</h1>
    </div>
    <div class="d-flex justify-content-center">
        <form class="" method="POST" action="upload.php" enctype="multipart/form-data">
            <!-- On limite le fichier à 100Ko -->
            <h2 style="text-align:center; color:yellow" for="imageUpload">Upload image</h2></br>
            <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
            <input class="btn btn-light" type="file" name="avatar[]" multiple="multiple" /></br>
            <button type="submit" class="btn btn-success">Upload</button>
            <div>
                <a style="color:red" href="upload.php">Rafraichir</a>
            </div>
        </form>
    </div>
    <div>
        <br><h2 style="text-align:center; color:yellow">Avatar en stock :</h2>
    </div>
    <div style="">
        <form  style="" method="post" action="upload.php">
        <?php
        foreach ($file as $fileinfo) {
            echo '<figure class="col-3" style="border:thin #c0c0c0 solid; display:inline-flex; flex-flow:column; padding: 5px; max-width: 220px; margin: auto;">';
            echo '<img style="max-width:220px; max-height:150px;" src="uploads/' . $fileinfo->getFilename() . '" />';
            echo '<figcaption style="background-color:#222; color:#fff; font:italic smaller sans-serif; padding:3px; text-align:center;">' . $fileinfo->getFilename() . '</figcaption>';
            if (file_exists($fileinfo)) {
                echo '<button class="btn btn-warning" type="submit"  name="submit" value="' . $fileinfo . '">Delete</button>';
                echo '</figure>';
            } 
        }
        ?>
        </form>
    </div>        
</body>
</html>