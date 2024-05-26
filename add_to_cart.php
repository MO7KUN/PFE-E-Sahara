<?php
include_once('Connection Open.php');
session_start();

if ($_SESSION['UserName'] == '') {
    // Redirect to login page if client is not logged in
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['ID_Produit'])) {
        $product_id = intval($_SESSION['ID_Produit']);
        $UserName ='"'. $_SESSION['UserName'].'"';
        // Check if the product is already in the cart
        $checkCartQuery = "SELECT * FROM panier WHERE UserName = ? AND ID_Produit = ?";
        $stmt = $conn->prepare($checkCartQuery);
        if ($stmt === false) {
            die("Error preparing query: " . $conn->error);
        }
        $stmt->bind_param("si", $UserName, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If the product is already in the cart, update the quantity
            $updateCartQuery = "UPDATE panier SET Qautite_produit = Qautite_produit + 1 WHERE UserName = ? AND ID_Produit = ?";
            $stmt = $conn->prepare($updateCartQuery);
            if ($stmt === false) {
                die("Error preparing query: " . $conn->error);
            }
            $stmt->bind_param("si", $UserName, $product_id);
        } else {
            // If the product is not in the cart, add it with quantity 1
            $addCartQuery = "INSERT INTO panier (ID_Produit, Qautite_produit) VALUES (?, 1) where UserName = ?";
            $stmt = $conn->prepare($addCartQuery);
            if ($stmt === false) {
                die("Error preparing query: " . $conn->error);
            }
            $stmt->bind_param("is", $product_id, $UserName);
        }

        if ($stmt->execute()) {
            // Redirect back to the main page or product page with success message
            header('Location: Main-Client.php?status=success');
        } else {
            // Redirect back to the main page or product page with error message
            header('Location: Main-Client.php?status=error');
        }
        
        $stmt->close();
    } else {
        header('Location: Main-Client.php?status=error');
    }
} else {
    header('Location: Main-Client.php');
}

$conn->close();
?>
