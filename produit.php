<?php
include_once('Connection Open.php');
session_start();

$UserName = $_SESSION['UserName'];


// Get the count of items in the user's cart
$countQuery = "SELECT SUM(quantite_produit) AS itemCount FROM panierproduit pp JOIN panier p ON pp.ID_Panier = p.ID_Panier WHERE p.UserName = '$UserName'";
$countResult = mysqli_query($conn, $countQuery);
$itemCount = 0;
if ($countResult) {
    $countRow = mysqli_fetch_assoc($countResult);
    $itemCount = $countRow['itemCount'] ? $countRow['itemCount'] : 0;
} else {
    echo "Error: " . mysqli_error($conn);
}

// Get product ID from URL
$productID = $_GET['ID_Produit'] ?? null;
$_SESSION['direction']="produit.php?ID_Produit=$productID";

// Fetch product information
$productQuery = "SELECT * FROM produit WHERE ID_Produit = '$productID'";
$productResult = mysqli_query($conn, $productQuery);
$product = null;
if ($productResult) {
    $product = mysqli_fetch_assoc($productResult);
} else {
    echo "Error: " . mysqli_error($conn);
}

// Close the connection after fetching the product data
// mysqli_close($conn);
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
    <title><?php echo $product['Libelle_produit'] ?? 'Product'; ?></title>
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

        .badge {
            position: absolute;
            top: -5px;
            right: -10px;
            padding: 5px 10px;
            border-radius: 50%;
            background-color: red;
            color: white;
        }

        .product-details {
            margin-top: 20px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dark-mode .product-details {
            background-color: #1f1f1f;
        }

        .product-image {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .product-title {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff;
            margin-top: 10px;
        }

        .product-description {
            font-size: 1rem;
            color: #666;
            margin-top: 10px;
        }

        .add-to-cart-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .add-to-cart-btn:hover {
            background-color: #218838;
        }

        .dark-mode .product-title, .dark-mode .product-price, .dark-mode .product-description {
            color: #ffffff;
        }
    </style>
</head>

<body class="light-mode">
    <header>
        <div class="container d-flex justify-content-between align-items-center header-container">
            <h1 class="font-weight-bold mb-0">E-Sahara</h1>
            <div class="search-bar-container">
                <form class="input-group" method="GET" action="search.php">
                    <input type="text" name="SrchPro" class="form-control" placeholder="Chercher un produit" aria-label="Chercher un produit" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="d-flex nav-buttons align-items-center position-relative">
                <a href="Main-Client.php" class="btn btn-outline-primary mr-2">
                    <i class="fas fa-home"></i>
                </a>
                <a href="Panier.php" class="btn btn-outline-primary mr-2 position-relative">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if ($itemCount > 0) { ?>
                        <span class="badge"><?php echo $itemCount; ?></span>
                    <?php } ?>
                </a>
                <a href="Commandes-Client.php" class="btn btn-outline-primary mr-2">
                    <i class="fas fa-box"></i>
                </a>
                <?php if ($_SESSION['role_user'] == 'admin') { ?>
                    <a href="Main-Admin.php" class="btn btn-outline-secondary mr-2">
                        <i class="fas fa-user-shield"></i>
                    </a>
                <?php } ?>
                <a href="Edit-client.php?UserName=<?php echo $_SESSION['UserName']; ?>" class="btn btn-outline-warning mr-2">
                    <i class="fas fa-user-edit"></i>
                </a>
                <a href="logout.php" class="btn btn-outline-danger mr-2">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
                <button class="btn btn-dark btn-dark-mode">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </header>

    <div class="container mt-4">
        <?php if ($product) { ?>
            <div class="row">
                <div class="col-md-6">
                    <img src="<?php echo $product['image_produit']; ?>" alt="Photo du produit" class="product-image img-fluid">
                </div>
                <div class="col-md-6">
                    <div class="product-details">
                        <h2 class="product-title"><?php echo $product['Libelle_produit']; ?></h2>
                        <p class="product-price"><?php echo $product['prix_unitaire'] . " Dh"; ?></p>
                        <p class="product-description"><?php echo $product['description_produit']; ?></p>
                        <form action="add-to-cart.php" method="get">
                            <div class="form-group">
                                <label for="quantity">Quantité:</label>
                                <p id="quantity" name="quantity"><?php echo $product['quantite_stock']; ?></p>
                            </div>
                            <input type="hidden" name="ID_Produit" value="<?php echo $product['ID_Produit']; ?>">
                            <button type="submit" class="btn btn-success btn-block add-to-cart-btn">Ajouter au panier</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="alert alert-danger" role="alert">
                Produit non trouvé.
            </div>
        <?php } ?>
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
