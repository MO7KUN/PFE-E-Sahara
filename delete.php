<?php
include_once('Connection Open.php');
session_start();

if (isset($_GET['ID_Produit'])) {
    $ID_Produit = $_GET['ID_Produit'];
    $UserName = $_SESSION['UserName'];

    // Retrieve the cart ID for the current user
    $cartQuery = "SELECT ID_Panier FROM panier WHERE UserName = '$UserName'";
    $cartResult = mysqli_query($conn, $cartQuery);

    if ($cartResult && mysqli_num_rows($cartResult) > 0) {
        $cartRow = mysqli_fetch_assoc($cartResult);
        $ID_Panier = $cartRow['ID_Panier'];

        // Delete the product from the cart
        $deleteQuery = "DELETE FROM panierproduit WHERE ID_Produit = '$ID_Produit' AND ID_Panier = '$ID_Panier'";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            header("Location: Panier.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Cart not found for user.";
    }
} else {
    echo "No product ID specified.";
}

// Close the database connection
mysqli_close($conn);
?>
