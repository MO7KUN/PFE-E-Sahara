<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once('Connection Open.php');
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>E-Sahara Admin</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        /* Light mode */
        .light-mode {
            background-color: #f0f2f5;
            color: #000000;
        }

        .light-mode header {
            background-color: #ffffff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Dark mode */
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
            /* Blue color */
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
            <div class="d-flex nav-buttons align-items-center">
                <a href="Main-Admin.php" class="btn btn-outline-primary mr-2">Main</a>
                <a href="Clients.php" class="btn btn-outline-primary mr-2">Clients</a>
                <a href="Commandes.php" class="btn btn-outline-primary mr-2">Commandes</a>
                <a href="index.php" class="btn btn-outline-danger mr-2">Log Out</a>
                <button class="btn btn-dark btn-dark-mode">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-moon" viewBox="0 0 16 16">
                        <path d="M6 0a6 6 0 1 0 0 12 5.96 5.96 0 0 0 3.9-1.484 6.993 6.993 0 0 1-1.528-.164A5 5 0 0 1 7 1 5.977 5.977 0 0 0 6 0zM4 2a4 4 0 1 1-1 7.93c.29-.33.561-.684.805-1.063A3 3 0 1 0 3 4a4 4 0 0 1 1-2z" />
                    </svg>
                </button>
            </div>
        </div>
    </header>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <h2 class="mb-4">Ajouter un Produit</h2>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="Libelle" class="bb margin">Libelle :</label>
                        <input name="Libelle" id="Libelle" type="text" placeholder=" Libelle" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="Prix" class="bb margin">Prix :</label>
                        <input name="Prix" id="Prix" type="text" placeholder=" Prix" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="Quantite" class="bb margin">Quantite :</label>
                        <input name="Quantite" id="Quantite" type="text" placeholder=" Quantite" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="Description" class="bb margin">Description :</label>
                        <input name="Description" id="Description" type="text" placeholder=" Description" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="Photo" class="bb margin">Photo :</label>
                        <input id="Photo" name="Photo" type="file" placeholder=" Model" class="form-control">
                    </div>
                    <input type="submit" value=" Ajouter " class="text-dark btn btn-outline-secondary mr-2">
                </form>
                <?php
                if (!(isset($_POST['Libelle']) & isset($_POST['Prix']) & isset($_POST['Photo']) & isset($_POST['Description']) & isset($_POST['Quantite']))) {
                ?>
                    <p class="bb2 bg-warning text-center margin">Veuillez remplir tous les champs</p>
                    <?php
                    
                } else {
                    $libelle = $_POST['Libelle'];
                    $prix = $_POST['Prix'];
                    $desc = $_POST['Description'];
                    $image = $_POST['Photo'];
                    $quantity = $_POST['Quantite'];
                    $sql = "INSERT INTO produit (Libelle_produit, image_produit, prix_unitaire, description_produit, quantite_stock) VALUES ('$libelle','$image',$prix,'$desc',$quantity)";
                    if (mysqli_query($conn, $sql)) {
                    ?>
                        <p class="bb2 text-center margin">Ajoutee avec succee</p>
                    <?php
                    } else {
                    ?>
                        <p class="bb2 text-center margin">Un erreur est survenue : <?php echo mysqli_error($conn); ?></p>
                <?php
                    }
                }
                mysqli_close($conn);
                ?>
            </div>

            <div class="col-md-6">
                <h2 class="mb-4">Modifier un Produit</h2>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="Libelle" class="bb margin">Libelle :</label>
                        <input name="Libelle" id="Libelle" type="text" placeholder=" Libelle" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="Prix" class="bb margin">Prix :</label>
                        <input name="Prix" id="Prix" type="text" placeholder=" Prix" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="Quantite" class="bb margin">Quantite :</label>
                        <input name="Quantite" id="Quantite" type="text" placeholder=" Quantite" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="Description" class="bb margin">Description :</label>
                        <input name="Description" id="Description" type="text" placeholder=" Description" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="Photo" class="bb margin">Photo :</label>
                        <input id="Photo" name="Photo" type="file" placeholder=" Model" class="form-control">
                    </div>
                    <input type="submit" value=" Ajouter " class="text-dark btn btn-outline-secondary mr-2">
                </form>
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
</body>

</html>