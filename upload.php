<?php

// upload.php

// chemin vers un dossier sur le serveur qui va recevoir les fichiers uploadés
// (attention ce dossier doit être accessible en écriture)
$uploadDir = 'uploads/';
// retour du nom du fichier
$filename = $_FILES['avatars']['name'];
// taille de fichier accéptés
$sizeMax = 1048576;
// Extension accéptées
$extensions = ['jpg', 'jpeg', 'png', 'gif'];

// Début des vérifications de sécurité...
if (isset($_POST['upload'])) {
    for ($i = 0; $i < count($_FILES['avatars']['name']); $i++) {
        // obtenir uniquement l'extension (sans le point)
        $extension = pathinfo($_FILES['avatars']['name'][$i], PATHINFO_EXTENSION);
        // taile du fichier uploadé
        $size = $_FILES['avatars']['size'][$i];
        // Si l'extension n'est pas dans le tableau
        if (!in_array($extension, $extensions)) {
            $errors[] = 'Fichier "' . $filename[$i] . '" non conforme: ' . '<br>' .
            'vous devez uploader un fichier de type png, gif, jpg, txt ou doc...' . '<br>';
        } else {
            // Si la taille est trop volumineuse
            if ($size == 0) {
                $errors[] = 'Fichier "' . $filename[$i] . '" non conforme: ' . '<br>' .
                'le fichier est trop gros...' . '<br>';
            } else {
                //S'il n'y a pas d'erreur, on upload
                // A unique name is concatenated with a dot and the $extention avec l'extension récupérée
                $filenameUniq = uniqid() . '.' .$extension;
                // Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                if (move_uploaded_file($_FILES['avatars']['tmp_name'][$i], $uploadDir . $filenameUniq)) {
                    $messages[] = 'Upload du fichier "' . $filename[$i] . '" effectué avec succès !' .'<br>';
                // Sinon (la fonction renvoie FALSE).
                } else {   
                    $messages[] = 'Echec de l\'upload "' . $filename[$i] . '" !'  .'<br>';
                } 
            }
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
            <input class="btn btn-light" type="file" name="avatars[]" multiple="multiple" required/></br>
            <button class="btn btn-success" type="submit" name="upload">Upload</button>
            <a class="btn btn-light" style="margin-top:20px; color:red" href="upload.php">Rafraichir</a>
        </form>
    </div>
    
        <?php
        if (isset($messages)) {
            echo '<br><h3 class="bg-success text-white" style="text-align:center">';
            foreach ($messages as $value) {
            echo $value;
            }
        }   echo '</h3><br>';
        if (isset($errors)) {
            echo '<br><h3 class="bg-danger text-white" style="text-align:center">';
            foreach ($errors as $value) {
            echo $value;
            }
        }   echo '</h3><br>';
        ?>
        
    <div>
    
        <br><h2 style="text-align:center; background-color:darkturquoise; color:yellow">Avatar en stock :</h2><br>
    </div>
    <div style="">
        <form  style="margin-bottom:50px" method="post" action="upload.php">
        <?php
        foreach ($file as $fileinfo) {
            echo '<figure class="col-3" style="border:thin #c0c0c0 solid; display:inline-flex; flex-flow:column; padding:5px; max-width:220px; margin:auto;">';
            echo '<img style="max-width:220px; height:150px" src="' . $fileinfo . '" />';
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