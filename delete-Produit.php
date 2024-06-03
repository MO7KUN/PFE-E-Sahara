<?php
include_once('Connection Open.php');
session_start();

if (isset($_GET['ID_Produit'])) {
    $id = $_GET['ID_Produit'];

    // Get the product's image path to delete the image file as well
    $sql = "SELECT image_produit FROM produit WHERE ID_Produit = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $image_path = $row['image_produit'];

    // Delete the product from the database
    $sql = "DELETE FROM produit WHERE ID_Produit = $id";
    if (mysqli_query($conn, $sql)) {
        // Delete the image file from the server
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        echo "<div class='alert alert-success'>Produit supprimé avec succès.</div>";
        header('Location: Main-Admin.php');
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de la suppression du produit : " . mysqli_error($conn) . "</div>";
    }
} else {
    echo "<div class='alert alert-danger'>ID de produit non spécifié.</div>";
}

mysqli_close($conn);
?>
