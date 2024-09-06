<?php
include_once('Connection Open.php');
session_start();
$direction = $_SESSION['direction'];

if (isset($_POST['ID_Produit']) && isset($_POST['quantite'])) {
    if (empty($_SESSION['UserName'])) {
        // Redirect to login page if the user is not logged in
        header('Location: index.php');
        exit();
    }

    $product_id = intval($_POST['ID_Produit']);
    $quantity = intval($_POST['quantite']);  // Get the selected quantity
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
        $updateCartQuery = "UPDATE panierproduit SET quantite_produit = quantite_produit + ? WHERE ID_Panier = ? AND ID_Produit = ?";
        $stmt = $conn->prepare($updateCartQuery);
        if ($stmt === false) {
            die("Error preparing query: " . $conn->error);
        }
        $stmt->bind_param("iii", $quantity, $idpanier, $product_id);
    } else {
        // If the product is not in the cart, add it with the chosen quantity
        // Get the cart ID for the user or create a new cart if it doesn't exist
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

        // Add the product to the cart with the selected quantity
        $addCartQuery = "INSERT INTO panierproduit (ID_Panier, ID_Produit, quantite_produit) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($addCartQuery);
        if ($stmt === false) {
            die("Error preparing query: " . $conn->error);
        }
        $stmt->bind_param("iii", $idpanier, $product_id, $quantity);
    }

    // Execute the prepared statement
    if ($stmt->execute()) {
        // Redirect back to the product page with a success message
        header("Location: $direction?status=success");
    } else {
        // Redirect back to the product page with an error message
        header("Location: $direction?status=error");
    }

    $stmt->close();
    mysqli_close($conn);
    exit();
}
?>
