<?php
include_once('Connection Open.php');
session_start();

$UserName = $_SESSION['UserName'];

// Delete all items from the user's cart
$deleteAllQuery = "DELETE FROM panierproduit USING panierproduit INNER JOIN panier ON panierproduit.ID_Panier = panier.ID_Panier WHERE panier.UserName = ?";
$stmt = $conn->prepare($deleteAllQuery);
$stmt->bind_param("s", $UserName);

if ($stmt->execute()) {
    header("Location: Panier.php?status=deleted_all");
} else {
    header("Location: Panier.php?status=error");
}
$stmt->close();
mysqli_close($conn);
