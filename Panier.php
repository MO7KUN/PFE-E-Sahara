<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once('Connection Open.php');

    // Start session to access session variables
    session_start();

    // Check if user is logged in
    if ($_SESSION['UserName']=="") {
        // Redirect to login page if not logged in
        header("Location: index.php");
        exit();
    }

    // Fetch cart items for the current user
    $UserName = $_SESSION['UserName'];
    $sql = 'SELECT p.Libelle_produit, p.prix_unitaire, pan.quantite_produit, (p.prix_unitaire * pan.quantite_produit) AS subtotal
            FROM produit p, panierproduit pan, panier pp
            WHERE p.ID_Produit = pan.ID_Produit AND pp.ID_Panier = pan.ID_Panier AND pp.UserName = "'.$UserName.'"';
    $result = mysqli_query($conn, $sql);

    // Check for SQL errors
    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        exit();
    }
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
        .dark-mode td {
            color: #fff;}
        .dark-mode th {
            color: #fff;}

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

        /* Dark mode button */
        .btn-dark-mode {
            color: #fff;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.2rem;
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
                <a href="index.php" class="btn btn-outline-danger mr-2">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
                <button class="btn btn-dark btn-dark-mode">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </header>
    <div class="container mt-5">
        <h2>Panier</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th>Sous-total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display cart items
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['Libelle_produit']}</td>";
                    echo "<td>{$row['prix_unitaire']} Dh</td>";
                    echo "<td>{$row['quantite_produit']}</td>";
                    echo "<td>{$row['subtotal']} Dh</td>";
                    echo "<td><button class='btn btn-danger'><i class='fas fa-trash'></i></button></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="text-right">
            <h4>Total: <?php
                        // Calculate total price
                        $total = 0;
                        $result->data_seek(0); // Reset result pointer
                        while ($row = $result->fetch_assoc()) {
                            $total += $row['subtotal'];
                        }
                        echo $total;
                        ?> Dh</h4>
            <a href="payer.php" class="btn btn-primary"><button class="btn btn-primary"><i class="fas fa-shopping-cart"></i></button></a> 
        </div>
    </div>
    <script>
        const btnDarkMode = document.getElementById('dark-mode-toggle');
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