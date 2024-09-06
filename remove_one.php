<?php
include_once('Connection Open.php');
session_start();

$UserName = $_SESSION['UserName'];
$productID = intval($_POST['ID_Produit']);

// Check current quantity
$checkQuery = "SELECT pp.ID_Panier, pp.quantite_produit FROM panierproduit pp 
               INNER JOIN panier p ON pp.ID_Panier = p.ID_Panier 
               WHERE p.UserName = ? AND pp.ID_Produit = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("si", $UserName, $productID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if ($row && $row['quantite_produit'] > 1) {
    // Decrease the quantity by one
    $updateQuery = "UPDATE panierproduit SET quantite_produit = quantite_produit - 1 WHERE ID_Panier = ? AND ID_Produit = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ii", $row['ID_Panier'], $productID);
} else {
    // Remove the product from the cart if the quantity is 1 or less
    $deleteQuery = "DELETE FROM panierproduit WHERE ID_Panier = ? AND ID_Produit = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("ii", $row['ID_Panier'], $productID);
}

if ($stmt->execute()) {
    header("Location: Panier.php?status=removed_one");
} else {
    header("Location: Panier.php?status=error");
}
$stmt->close();
mysqli_close($conn);
