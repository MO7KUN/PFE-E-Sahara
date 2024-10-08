<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once('Connection Open.php');
    session_start();

    // Check if user is logged in
    if ($_SESSION['UserName'] == "") {
        header("Location: index.php");
        exit();
    }

    // Fetch command data based on user role
    $sql = 'SELECT c.Id_Commande, c.UserName, (p.Prix_Unitaire * pc.quantite_produit) AS Montant_Total, c.status_livraison, c.date_livre 
            FROM commande c
            INNER JOIN prodcommande pc ON c.Id_Commande = pc.Id_Commande
            INNER JOIN produit p ON pc.Id_Produit = p.Id_Produit';
    
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>E-Sahara Commandes</title>
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
        }

        .card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        @media only screen and (max-width: 600px) {
            table {
                border: 0;
            }

            th, td {
                display: block;
                width: 100%;
                text-align: left;
            }

            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            tr {
                border: 1px solid #ccc;
            }

            td {
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 50%;
            }

            td:before {
                content: attr(data-label);
                position: absolute;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
            }
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

        /* Dark mode button */
        .btn-dark-mode {
            color: #fff;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
        
        .dark-mode td {
            color: #ffffff;
        }
        
        .dark-mode th {
            color: #ffffff;
        }
    </style>
</head>

<body class="light-mode">
<header>
    <nav class="navbar navbar-expand-md navbar-light bg-light">
        <a class="navbar-brand font-weight-bold" href="#">E-Sahara</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="d-flex nav-buttons align-items-center nav-bar-shrink ">
        <form class="input-group" method="GET">
                    <input type="text" name="SrchPro" class="form-control" placeholder="Chercher un produit" aria-label="Chercher un produit" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
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
    </nav>
</header>

<div class="container mt-5">
    <h2>Historique des commandes</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID Commande</th>
                <th>UserName</th>
                <th>Montant Total</th>
                <th>Statut</th>
                <th>Date Livrée</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Display command data
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['Id_Commande']}</td>";
                echo "<td>{$row['UserName']}</td>";
                echo "<td>{$row['Montant_Total']} Dh</td>";
                echo "<td>{$row['status_livraison']}</td>";
                if ($row['status_livraison'] == "Livrée") {
                    echo "<td>{$row['date_livre']}</td>";
                } else {
                    echo "<td>N/A</td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>
