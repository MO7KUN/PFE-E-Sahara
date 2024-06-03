<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('Connection Open.php'); ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>E-Sahara Admin</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        .light-mode {
            background-color: #f0f2f5;
            color: #000000;
        }

        .light-mode header {
            background-color: #ffffff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .dark-mode {
            background-color: #121212;
            color: #ffffff;
        }

        .dark-mode header {
            background-color: #1f1f1f;
            box-shadow: 0px 4px 6px rgba(255, 255, 255, 0.1);
        }

        .header-container {
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
        }

        .search-bar-container {
            margin: 10px 0;
            display: flex;
            align-items: stretch;
        }

        .input-group {
            width: 100%;
            margin-right: -1px;
        }

        .form-control {
            height: calc(100% - 2px);
            padding-top: 0.375rem;
            padding-bottom: 0.375rem;
            line-height: 1.5;
            height: 50px;
        }

        @media (max-width: 576px) {
            .search-bar-container {
                margin-top: 10px;
            }

            .nav-buttons {
                margin-left: 0;
            }
        }

        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.5rem;
            color: #333;
        }

        .list-unstyled li {
            margin-bottom: 10px;
        }

        .btn {
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: rgba(0, 0, 255, 0.1);
        }

        .btn-dark-mode {
            color: #ffffff;
        }

        .form-group {
            margin-bottom: 2rem;
        }
    </style>
</head>

<body class="light-mode">
    <header>
        <div class="container d-flex justify-content-between align-items-center header-container">
            <h1 class="font-weight-bold mb-0">E-Sahara</h1>
            <div class="search-bar-container">
                <form class="input-group" method="GET">
                    <input type="text" name="SrchPro" class="form-control" placeholder="Chercher un produit" aria-label="Chercher un produit" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="d-flex nav-buttons align-items-center">
                <a href="Main-Admin.php" class="btn btn-outline-primary mr-2">
                    <i class="fas fa-home"></i>
                </a>
                <a href="Clients.php" class="btn btn-outline-primary mr-2">
                    <i class="fas fa-users"></i>
                </a>
                <a href="Commandes-Admin.php" class="btn btn-outline-primary mr-2">
                    <i class="fas fa-box"></i>
                </a>
                <a href="Main-Client.php" class="btn btn-outline-secondary mr-2">
                    <i class="fas fa-user"></i>
                </a>
                <a href="add-Produit.php" class="btn btn-outline-warning mr-2">
                    <i class="fas fa-plus-square"></i>
                </a>
                <a href="Edit-client.php" class="btn btn-outline-warning mr-2">
                    <i class="fas fa-user-edit"></i>
                </a>
                <a href="index.php" class="btn btn-outline-danger mr-2">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
                <button class="btn btn-dark btn-dark-mode">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </header>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4">Ajouter un Produit</h2>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="Libelle" class="bb margin">Libelle :</label>
                        <input name="Libelle" id="Libelle" type="text" placeholder=" Libelle" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="Prix" class="bb margin">Prix :</label>
                        <input name="Prix" id="Prix" type="text" placeholder=" Prix" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="Quantite" class="bb margin">Quantite :</label>
                        <input name="Quantite" id="Quantite" type="text" placeholder=" Quantite" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="Description" class="bb margin">Description :</label>
                        <input name="Description" id="Description" type="text" placeholder=" Description" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="Photo" class="bb margin">Photo :</label>
                        <input id="Photo" name="Photo" type="file" placeholder=" Model" class="form-control" required>
                    </div>
                    <input type="submit" value=" Ajouter " class="text-dark btn btn-outline-secondary mr-2 col-12">
                </form>
                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (isset($_POST['Libelle']) && isset($_POST['Prix']) && isset($_POST['Quantite']) && isset($_POST['Description']) && isset($_FILES['Photo'])) {
                        $libelle = $_POST['Libelle'];
                        $prix = $_POST['Prix'];
                        $quantity = $_POST['Quantite'];
                        $desc = $_POST['Description'];
                        $image = $_FILES['Photo'];
                
                        $target_dir = "Images/Products/";
                        $imageFileType = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
                        $newFileName = $libelle . "." . $imageFileType;
                        $target_file = $target_dir . basename($newFileName);
                        $uploadOk = 1;
                
                        // Check if image file is an actual image or fake image
                        $check = getimagesize($image["tmp_name"]);
                        if ($check !== false) {
                            $uploadOk = 1;
                        } else {
                            echo "File is not an image.";
                            $uploadOk = 0;
                        }
                
                        // Check file size
                        if ($image["size"] > 5000000) {
                            echo "Sorry, your file is too large.";
                            $uploadOk = 0;
                        }
                
                        // Allow certain file formats
                        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                            $uploadOk = 0;
                        }
                
                        // Check if directory exists, if not, create it
                        if (!is_dir($target_dir)) {
                            mkdir($target_dir, 0777, true);
                        }
                
                        // Check if $uploadOk is set to 0 by an error
                        if ($uploadOk == 0) {
                            echo "Sorry, your file was not uploaded.";
                        } else {
                            if (move_uploaded_file($image["tmp_name"], $target_file)) {
                                // Store image path in the database
                                $sql = "INSERT INTO produit (Libelle_produit, image_produit, prix_unitaire, description_produit, quantite_stock) VALUES ('$libelle', '$target_file', $prix, '$desc', $quantity)";
                                if (mysqli_query($conn, $sql)) {
                                    echo "<p class='bb2 text-center margin'>Ajoutee avec succee</p>";
                                } else {
                                    echo "<p class='bb2 text-center margin'>Un erreur est survenue : " . mysqli_error($conn) . "</p>";
                                }
                            } else {
                                echo "Sorry, there was an error uploading your file.";
                            }
                        }
                    } else {
                        echo "<p class='bb2 bg-warning text-center margin'>Veuillez remplir tous les champs</p>";
                    }
                }
                mysqli_close($conn);
                ?>
            </div>
        </div>
    </div>
    <script>
        const btnDarkMode = document.querySelector('.btn-dark-mode');
        const body = document.body;

        btnDarkMode.addEventListener('click', () => {
            body.classList.toggle('light-mode');
            body.classList.toggle('dark-mode');
            if (body.classList.contains('dark-mode')) {
                btnDarkMode.classList.add('btn-dark');
                btnDarkMode.classList.remove('btn-primary');
            } else {
                btnDarkMode.classList.add('btn-primary');
                btnDarkMode.classList.remove('btn-dark');
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit-id.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>