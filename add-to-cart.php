<?php
include_once('Connection Open.php');
session_start();

if (isset($_GET['ID_Produit'])) {
    if (empty($_SESSION['UserName'])) {
        // Redirect to login page if client is not logged in
        header('Location: index.php');
        exit();
    }

    $product_id = intval($_GET['ID_Produit']);
    $username = $_SESSION['UserName'];

    // Check if the product is already in the cart
    $checkCartQuery = "SELECT pan.ID_Panier FROM panier pan
                       INNER JOIN panierproduit pp ON pan.ID_Panier = pp.ID_Panier 
                       WHERE pan.UserName = ? AND pp.ID_Produit = ?";
    $stmt = $conn->prepare($checkCartQuery);
    if ($stmt === false) {
        die("Error preparing query: " . $conn->error);
    }
    $stmt->bind_param("si", $username, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    $idpanier = isset($row['ID_Panier']) ? intval($row['ID_Panier']) : null;

    if ($idpanier !== null) {
        // If the product is already in the cart, update the quantity
        $updateCartQuery = "UPDATE panierproduit SET quantite_produit = quantite_produit + 1 WHERE ID_Panier = ? AND ID_Produit = ?";
        $stmt = $conn->prepare($updateCartQuery);
        if ($stmt === false) {
            die("Error preparing query: " . $conn->error);
        }
        $stmt->bind_param("ii", $idpanier, $product_id);
    } else {
        // If the product is not in the cart, add it with quantity 1
        // Assuming we get the ID_Panier from the username or create a new cart if it doesn't exist
        $getCartIdQuery = "SELECT ID_Panier FROM panier WHERE UserName = ?";
        $stmt = $conn->prepare($getCartIdQuery);
        if ($stmt === false) {
            die("Error preparing query: " . $conn->error);
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        $idpanier = isset($row['ID_Panier']) ? intval($row['ID_Panier']) : null;

        if ($idpanier === null) {
            // Create a new cart if none exists
            $createCartQuery = "INSERT INTO panier (UserName) VALUES (?)";
            $stmt = $conn->prepare($createCartQuery);
            if ($stmt === false) {
                die("Error preparing query: " . $conn->error);
            }
            $stmt->bind_param("s", $username);
            if ($stmt->execute()) {
                $idpanier = $stmt->insert_id;
            } else {
                die("Error executing query: " . $stmt->error);
            }
            $stmt->close();
        }

        $addCartQuery = "INSERT INTO panierproduit (ID_Panier, ID_Produit, quantite_produit) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($addCartQuery);
        if ($stmt === false) {
            die("Error preparing query: " . $conn->error);
        }
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
