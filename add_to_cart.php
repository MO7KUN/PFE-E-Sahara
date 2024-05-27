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
        $checkCartQuery = "SELECT ID_Panier FROM panier pan,
        INNER JOIN panierproduit pp ON pan.ID_Panier = pp.ID_Panier 
        WHERE pan.UserName = $username AND pp.ID_Produit = $product_id";
        $result=mysqli_query($conn, $checkCartQuery);
        $row=mysqli_fetch_assoc($result);
        $idpanier = intval($row['ID_Panier']);

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
            $addCartQuery = "INSERT INTO panierproduit (ID_Produit, quantite_produit) VALUES (?, 1) where ID_Panier = ?";
            $stmt = $conn->prepare($addCartQuery);
            if ($stmt === false) {
                die("Error preparing query: " . $conn->error);
            }
            $stmt->bind_param("ii", $product_id, $idpanier);
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
