<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once('Connection Open.php');
    session_start();

    // Fetch cart items for the current user
    $UserName = $_SESSION['UserName'];
    $sql = 'SELECT p.ID_Produit AS ID_Produit, p.Libelle_produit, p.prix_unitaire, pan.quantite_produit, (p.prix_unitaire * pan.quantite_produit) AS subtotal
            FROM produit p, panierproduit pan, panier pp
            WHERE p.ID_Produit = pan.ID_Produit AND pp.ID_Panier = pan.ID_Panier AND pp.UserName = "' . $UserName . '"';
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

        /* Dark mode */
        .dark-mode {
            background-color: #121212;
            color: #ffffff;
        }

        /* Other styles */
        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card img {
            border-radius: 10px 10px 0 0;
        }

        .btn {
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: rgba(0, 0, 255, 0.1);
        }

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
                <a href="Edit-client.php" class="btn btn-outline-warning mr-2">
                    <i class="fas fa-user-edit"></i>
                </a>
                <a href="index.php" class="btn btn-outline-danger mr-2">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
                <button id="dark-mode-toggle" class="btn btn-dark btn-dark-mode">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </header>

    <div class="container mt-5">
        <h2>Panier</h2>

        <!-- Delete All Button -->
        <div class="text-right mb-3">
            <form action="delete_all.php" method="post">
                <button type="submit" class="btn btn-danger">Supprimer tout</button>
            </form>
        </div>

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
                ?>
                    <td>
                        <!-- Remove one piece form -->
                        <form action="remove_one.php" method="post" class="d-inline">
                            <input type="hidden" name="ID_Produit" value="<?php echo $row['ID_Produit']; ?>">
                            <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-minus-circle"></i> Retirer 1</button>
                        </form>

                        <!-- Delete entire row form -->
                        <form action="delete.php" method="post" class="d-inline">
                            <input type="hidden" name="ID_Produit" value="<?php echo $row['ID_Produit']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Supprimer</button>
                        </form>
                    </td>
                <?php
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
            <a href="payer.php" class="btn btn-primary"><i class="fas fa-shopping-cart"></i> Payer</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const body = document.body;
            const btnDarkMode = document.getElementById('dark-mode-toggle');
            const currentMode = localStorage.getItem('theme') || 'light';
            body.classList.toggle('dark-mode', currentMode === 'dark');
            body.classList.toggle('light-mode', currentMode === 'light');
            btnDarkMode.classList.toggle('btn-dark', currentMode === 'dark');
            btnDarkMode.classList.toggle('btn-primary', currentMode === 'light');

            btnDarkMode.addEventListener('click', () => {
                body.classList.toggle('dark-mode');
                body.classList.toggle('light-mode');
                const newMode = body.classList.contains('dark-mode') ? 'dark' : 'light';
                localStorage.setItem('theme', newMode);
                btnDarkMode.classList.toggle('btn-dark', newMode === 'dark');
                btnDarkMode.classList.toggle('btn-primary', newMode === 'light');
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit-id.js" crossorigin="anonymous"></script>
</body>

</html>