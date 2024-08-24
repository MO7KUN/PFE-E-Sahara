<?php
include_once('Connection Open.php');
session_start();

$UserName = $_SESSION['UserName'];
$_SESSION['direction']="Main-Client.php";

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

// Close the connection after fetching the item count
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
            color: #ffffff;
            /* Black color */
        }
        .dark-mode .card-title {
            color: #ffffff;
            /* Black color */
        }
        .dark-mode .card{
            background-color: #1f1f1f;
            box-shadow: 0px 4px 6px rgba(255, 255, 255, 0.1);
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
            include_once('Connection Open.php');
            $produit = "";
            if (isset($_GET['SrchPro'])) {
                $produit = $_GET['SrchPro'];
            }

            // Check the database connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = 'SELECT * FROM produit WHERE Libelle_produit LIKE "%' . $produit . '%"';
            $result = mysqli_query($conn, $sql);

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="col-md-4 col-sm-6 col-12 mb-4">
                        <div class="card shadow-sm h-100">
                            <img class="card-img-top" src="<?php echo $row['image_produit']; ?>" alt="Photo du produit">
                            <div class="card-body d-flex flex-column">
                                <h4 class="card-title font-weight-bold text-50"><?php echo $row['Libelle_produit']; ?></h4>
                                <p class="font-weight-bold card-text"><?php echo $row['prix_unitaire'] . " Dh"; ?></p>
                                <div class="btn-group mt-auto">
                                    <form action="add-to-cart.php" method="get" >
                                        <input type="hidden" name="ID_Produit" value="<?php echo $row['ID_Produit']; ?>">
                                        <button type="submit" class="btn btn-success btn-block mt-2 ">Ajouter au panier</button>
                                        <a href="produit.php?ID_Produit=<?php echo $row['ID_Produit']; ?>"><button type="button" class="btn mt-2  btn-primary btn-block">Voire le produit</button></a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }

            // Close the connection
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
