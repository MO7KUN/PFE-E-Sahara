<!DOCTYPE html>
<html lang="en">
<?php
require_once('Connection Open.php');
$NumTele = NULL;
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>E-Sahara</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 40px 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        .container h1 {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            height: 50px;
            border-radius: 25px;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            border-radius: 25px;
            height: 50px;
            width: 100%;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .text-danger {
            color: #dc3545;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mb-4">Sign Up</h1>
        <form action="" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="Nom" placeholder="Nom" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="Prenom" placeholder="Prénom" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="UserName" placeholder="Nom d'utilisateur" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="E-mail" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="NumTele" placeholder="Numéro de Téléphone">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="Adresse" placeholder="Adresse" >
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="PasswordN1" placeholder="Mot de passe" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="PasswordN2" placeholder="Répétez le mot de passe" required>
            </div>
            <button type="submit" class="btn btn-success">Valider</button>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get form data
            $username = $_POST['UserName'];
            $email = $_POST['E-mail'];
            $passwordN1 = $_POST['PasswordN1'];
            $passwordN2 = $_POST['PasswordN2'];
            $nom = $_POST['Nom'];
            $pren = $_POST['Prenom'];
            $NumTele = $_POST['NumTele'];
            $adr = $_POST['Adresse'];
            // Check if password matches
            if ($passwordN1 !== $passwordN2) {
                echo "<div class='text-danger'>Passwords do not match.</div>";
                exit;
            }
            if(strpos($login, "@") === false){
                echo "<div class='text-danger'>Please enter a valid email address.</div>";
                exit;
            }

            // Hash the password
            $hashed_password = password_hash($passwordN1, PASSWORD_DEFAULT);


            // Insert user data into database
            $sql = "INSERT INTO user (UserName, nom_user, prenom_user, num_tel_user, email_user, password_user, adresse_user) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $username, $nom, $pren, $NumTele, $email, $hashed_password, $adr);


            if ($stmt->execute()) {

                $addCartQuery = "INSERT INTO panier (UserName, ID_Produit, Qautite_produit) VALUES (?, ?, 1)";
                $stmt2 = $conn->prepare($addCartQuery);
                if ($stmt2 === false) {
                    die("Error preparing query: " . $conn->error);
                }
                $stmt2->bind_param("si", $UserName, $product_id);
                if ($stmt2->execute()) {
                    echo "<div class='text-success'>User registered successfully!</div>";
                    // Redirect the user to the login page or any other page
                    header("Location: index.php");
                    exit;
                } else {
                    echo "<div class='text-danger'>Error: " . $stmt2->error . "</div>";
                }
            } else {
                echo "<div class='text-danger'>Error: " . $stmt->error . "</div>";
            }

            // Close statement
            $stmt->close();
        }

        // Close database connection
        $conn->close();
        ?>
    </div>
    <p class="footer">Already have an account? <a href="index.php">Log in</a></p>
</body>

</html>