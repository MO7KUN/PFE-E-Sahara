<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once('Connection Open.php');

    // Start session to access session variables
    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['UserName']) || $_SESSION['UserName'] == "") {
        // Redirect to login page if not logged in
        header("Location: index.php");
        exit();
    }

    // Fetch total price for the current user
    $UserName = $_SESSION['UserName'];
    $sql = 'SELECT SUM(p.prix_unitaire * pan.quantite_produit) AS total
            FROM produit p, panierproduit pan, panier pp
            WHERE p.ID_Produit = pan.ID_Produit AND pp.ID_Panier = pan.ID_Panier AND pp.UserName = ?';
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $error = "Error preparing statement for total price: " . $conn->error;
    } else {
        $stmt->bind_param("s", $UserName);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            $error = "Error executing statement for total price: " . $stmt->error;
        } else {
            $total = $result->fetch_assoc()['total'];
        }
        $stmt->close();
    }

    // Fetch existing payment information
    $paymentExists = false;
    $sql = "SELECT Card_Name, Date_expiration FROM payementmethode WHERE UserName = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $error = "Error preparing statement for fetching payment info: " . $conn->error;
    } else {
        $stmt->bind_param("s", $UserName);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $paymentExists = true;
            $paymentInfo = $result->fetch_assoc();
        }
        $stmt->close();
    }

    // Process form data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $useExisting = isset($_POST['useExisting']);
        $cardNumber = $_POST['cardNumber'] ?? null;
        $cardName = $_POST['cardName'] ?? null;
        $expDate = $_POST['expDate'] ?? null;
        $cvv = $_POST['cvv'] ?? null;

        if ($useExisting) {
            // Use existing payment info
            $sql = "SELECT CC_Num, CVC FROM payementmethode WHERE UserName = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                $error = "Error preparing statement for fetching existing payment details: " . $conn->error;
            } else {
                $stmt->bind_param("s", $UserName);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result && $result->num_rows > 0) {
                    $existingPayment = $result->fetch_assoc();
                    $cardNumber = $existingPayment['cardNumber'];
                    $cvv = $existingPayment['cvv'];
                }
                $stmt->close();
            }
        } else {
            // Hash sensitive payment information
            $hashedCardNumber = password_hash($cardNumber, PASSWORD_DEFAULT);
            $hashedCvv = password_hash($cvv, PASSWORD_DEFAULT);

            // Insert new payment method into the database
            $sql = "INSERT INTO payementmethode (UserName, CC_Num, Card_Name, CVC, Date_expiration) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                $error = "Error preparing statement for new payment method: " . $conn->error;
            } else {
                $stmt->bind_param("sssss", $UserName, $hashedCardNumber, $cardName, $hashedCvv, $expDate);
                if (!$stmt->execute()) {
                    $error = "Error executing statement for new payment method: " . $stmt->error;
                }
                $stmt->close();
            }
        }

        if (!isset($error)) {
            // Insert order into the database
            $sql = "INSERT INTO commande (UserName, status_livraison) VALUES (?, 'En cours de livraison')";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                $error = "Error preparing statement for commandes: " . $conn->error;
            } else {
                $stmt->bind_param("s", $UserName);
                if (!$stmt->execute()) {
                    $error = "Error executing statement for commandes: " . $stmt->error;
                } else {
                    $commandeID = $stmt->insert_id; // Get the last inserted commande ID

                    // Get all products in the user's cart
                    $sql = 'SELECT pan.ID_Produit, pan.quantite_produit
                            FROM panierproduit pan, panier pp
                            WHERE pp.ID_Panier = pan.ID_Panier AND pp.UserName = ?';
                    $stmt = $conn->prepare($sql);
                    if ($stmt === false) {
                        $error = "Error preparing statement for products in cart: " . $conn->error;
                    } else {
                        $stmt->bind_param("s", $UserName);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if (!$result) {
                            $error = "Error executing statement for products in cart: " . $stmt->error;
                        } else {
                            while ($row = $result->fetch_assoc()) {
                                $productID = $row['ID_Produit'];
                                $quantity = $row['quantite_produit'];
                                $sql = "INSERT INTO prodcommande (ID_Produit, ID_Commande, quantite_produit) VALUES (?, ?, ?)";
                                $stmt = $conn->prepare($sql);
                                if ($stmt === false) {
                                    $error = "Error preparing statement for inserting product in order: " . $conn->error;
                                    break;
                                } else {
                                    $stmt->bind_param("iii", $productID, $commandeID, $quantity);
                                    if (!$stmt->execute()) {
                                        $error = "Error executing statement for inserting product in order: " . $stmt->error;
                                        break;
                                    }
                                }
                            }
                        }
                        $stmt->close();
                    }
                }
            }
        }

        if (isset($error)) {
            echo "<script>alert('$error');</script>";
        } else {
            echo "<script>alert('Payment successful! Redirecting to main page...'); window.location.href = 'Main-Client.php';</script>";
            exit();
        }
    }

    // Close connection
    $conn->close();
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>E-Sahara Payment</title>
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

        /* Dark mode button */
        .btn-dark-mode {
            color: #fff;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        .payment-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }

        .payment-form label {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .dark-mode .payment-form label {
            color: #000000;
        }

        .payment-form .form-control {
            margin-bottom: 20px;
        }

        .payment-form .btn-primary {
            width: 100%;
            padding: 10px;
            font-size: 1.2rem;
        }

        .h-50 {
            height: 50px;
        }
    </style>
</head>

<body class="light-mode">
    <header>
        <div class="container d-flex justify-content-between align-items-center header-container">
            <h1 class="font-weight-bold mb-0">E-Sahara</h1>
            <div class="search-bar-container">
                <form class="input-group" method="GET" hidden>
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
        <h2>Paiement</h2>
        <?php if ($paymentExists) { ?>
            <div class="alert alert-info" role="alert">
                <p>Payment information already exists:</p>
                <ul>
                    <li>Card Name: <?php echo htmlspecialchars($paymentInfo['Card_Name']); ?></li>
                    <li>Expiration Date: <?php echo htmlspecialchars($paymentInfo['Date_expiration']); ?></li>
                </ul>
                <form action="" method="POST" class="payment-form">
                    <input type="hidden" name="useExisting" value="1">
                    <button type="submit" class="btn btn-primary">Use Existing Payment Info</button>
                </form>
                <button class="btn btn-secondary mt-3" onclick="document.getElementById('newPaymentForm').style.display='block';">Update/Add New Payment Info</button>
            </div>
        <?php } ?>
        <form action="" method="POST" class="payment-form" id="newPaymentForm" <?php if ($paymentExists) echo 'style="display:none;"'; ?>>
            <div class="form-group">
                <label for="cardNumber">Numéro de carte</label>
                <input type="text" class="form-control" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456" required>
            </div>
            <div class="form-group">
                <label for="cardName">Nom sur la carte</label>
                <input type="text" class="form-control" id="cardName" name="cardName" placeholder="NOM PRENOM" required>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="expDate">Date d'expiration</label>
                    <input type="text" class="form-control h-50" id="expDate" name="expDate" placeholder="MM/YY" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="cvv">CVV</label>
                    <input type="text" class="form-control h-50" id="cvv" name="cvv" placeholder="123" required>
                </div>
            </div>
            <div class="form-group">
                <label for="totalAmount">Montant total</label>
                <input type="text" class="form-control" id="totalAmount" name="totalAmount" value="<?php echo $total; ?> Dh" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Payer</button>
        </form>
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
