<?php
include_once('Connection Open.php');
session_start();

$UserName = $_SESSION['UserName'];
$productID = intval($_POST['ID_Produit']);

// Delete the product from the cart
$deleteQuery = "DELETE FROM panierproduit USING panierproduit INNER JOIN panier ON panierproduit.ID_Panier = panier.ID_Panier 
                WHERE panier.UserName = ? AND panierproduit.ID_Produit = ?";
$stmt = $conn->prepare($deleteQuery);
$stmt->bind_param("si", $UserName, $productID);

if ($stmt->execute()) {
    header("Location: Panier.php?status=deleted");
} else {
    header("Location: Panier.php?status=error");
}
$stmt->close();
mysqli_close($conn);