<?php
include_once('Connection Open.php');
session_start();

// Check for form submission and handle accordingly
if (isset($_POST['ID_Produit'])) {
    if (empty($_SESSION['UserName'])) {
        // Redirect to login page if client is not logged in
        header('Location: index.php');
        exit();
    }

    $product_id = intval($_POST['ID_Produit']);
    $username = $_SESSION['UserName'];
    $username = '"' . $username . '"';
    // Check if the product is already in the cart
    $checkCartQuery = "SELECT pan.ID_Panier FROM panier pan
                       INNER JOIN panierproduit pp ON pan.ID_Panier = pp.ID_Panier 
                       WHERE pan.UserName = $username AND pp.ID_Produit = $product_id";
    try {
        $result = mysqli_query($conn, $checkCartQuery);
        $row = mysqli_fetch_assoc($result);
        $idpanier = intval($row['ID_Panier']);
    } catch (Exception $e) {
        die("Error executing query: " . $e->getMessage());
    }

    if (isset($idpanier)) {
        // If the product is already in the cart, update the quantity
        $updateCartQuery = "UPDATE panierproduit SET quantite_produit = quantite_produit + 1 WHERE ID_Panier = ? AND ID_Produit = ?";
        $stmt = $conn->prepare($updateCartQuery);
        if ($stmt === false) {
            die("Error preparing query: " . $conn->error);
        }
        $stmt->bind_param("ii", $idpanier, $product_id);
    } else {
        // If the product is not in the cart, add it with quantity 1
        $addCartQuery = "INSERT INTO panierproduit (ID_Panier, ID_Produit, quantite_produit) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($addCartQuery);
        if ($stmt === false) {
            die("Error preparing query: " . $conn->error);
        }
        // Assuming ID_Panier is obtained elsewhere or default to a value
        $stmt->bind_param("ii", $idpanier, $product_id);
    }

    if ($stmt->execute()) {
        // Redirect back to the main page or product page with success message
        header('Location: Main-Client.php?status=success');
    } else {
        // Redirect back to the main page or product page with error message
        header('Location: Main-Client.php?status=error');
    }

    $stmt->close();
    mysqli_close($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>E-Sahara Client</title>
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

        .card img {
            border-radius: 10px 10px 0 0;
            /* Match the card border radius */
        }

        .card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
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
            color: #fff;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }

        /* Email text color in dark mode */
        .dark-mode .card-text {
            color: #000000;
            /* Black color */
        }

        .w-9 {
            width: 90%;
            margin: auto auto;
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
                <a href="Main-Client.php" class="btn btn-outline-primary mr-2">
                    <i class="fas fa-home"></i>
                </a>
                <a href="Panier.php" class="btn btn-outline-primary mr-2">
                    <i class="fas fa-shopping-cart"></i>
                </a>
                <a href="Commandes-Client.php" class="btn btn-outline-primary mr-2">
                    <i class="fas fa-box"></i>
                </a>
                <?php if ($_SESSION['role_user'] == 'admin') { ?>
                    <a href="Main-Admin.php" class="btn btn-outline-secondary mr-2">
                        <i class="fas fa-user-shield"></i>
                    </a>
                <?php } ?>
                <a href="Edit-client.php" class="btn btn-outline-warning mr-2">
                    <i class="fas fa-user-edit"></i>
                </a>
                <a href="edit-product.php" class="btn btn-outline-info mr-2">
                    <i class="fas fa-edit"></i>
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
        <div class="row" id="products">
            <?php
            $produit = "";
            if (isset($_GET['SrchPro'])) {
                $produit = $_GET['SrchPro'];
            }
            $sql = 'SELECT * FROM produit WHERE Libelle_produit LIKE "%' . $produit . '%"';
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="col-md-4 col-sm-6 col-12 mb-4">
                    <div class="card shadow-sm h-100">
                        <img class="card-img-top" src="<?php echo $row['image_produit']; ?>" alt="Photo du produit">
                        <div class="card-body d-flex flex-column">
                            <h4 class="card-title font-weight-bold text-black-50"><?php echo $row['Libelle_produit']; ?></h4>
                            <p class="font-weight-bold card-text"><?php echo $row['prix_unitaire'] . " Dh"; ?></p>
                            <p class="card-text"><?php echo $row['description_produit']; ?></p>
                            <div class="btn-group mt-auto">
                                <form action="" method="post" class="mt-2">
                                    <input type="hidden" name="ID_Produit" value="<?php echo $row['ID_Produit']; ?>">
                                    <button type="submit" class="btn btn-success btn-block">Ajouter au panier</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }

            // Close connection
            mysqli_close($conn); ?>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit-id.js" crossorigin="anonymous"></script>
</body>
</html>
