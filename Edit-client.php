<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once('Connection Open.php');
    session_start();
    $username = $_SESSION['UserName'];
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>E-Sahara Edit</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        /* Light mode */
        .light-mode {
            background-color: #f0f2f5;
            color: #000000;
        }

        .light-mode header {
            background-color: #ffffff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Dark mode */
        .dark-mode {
            background-color: #121212;
            color: #ffffff;
        }

        .dark-mode header {
            background-color: #1f1f1f;
            box-shadow: 0px 4px 6px rgba(255, 255, 255, 0.1);
        }

        .header-container {
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
        }

        .search-bar-container {
            margin: 10px 0;
            display: flex;
            align-items: stretch;
        }

        .input-group {
            width: 100%;
            margin-right: -1px;
        }

        .form-control {
            height: calc(100% - 2px);
            padding-top: 0.375rem;
            padding-bottom: 0.375rem;
            line-height: 1.5;
            height: 50px;
        }

        @media (max-width: 576px) {
            .search-bar-container {
                margin-top: 10px;
            }

            .nav-buttons {
                margin-left: 0;
            }
        }

        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.5rem;
            color: #333;
        }

        .list-unstyled li {
            margin-bottom: 10px;
        }

        .btn {
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: rgba(0, 0, 255, 0.1);
            /* Blue color */
        }

        .btn-dark-mode {
            color: #ffffff;
        }

        .form-group {
            margin-bottom: 2rem;
        }
    </style>
</head>

<body class="light-mode">
<header>
        <div class="container d-flex justify-content-between align-items-center header-container">
            <h1 class="font-weight-bold mb-0">E-Sahara</h1>
            <div class="search-bar-container">
                <form class="input-group" method="GET">
                    <input type="text" name="SrchPro" class="form-control" placeholder="Chercher un produit" aria-label="Chercher un produit" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="d-flex nav-buttons align-items-center">
                <a href="Main-Client.php" class="btn btn-outline-primary mr-2">
                    <i class="fas fa-home"></i>
                </a>
                <a href="Panier.php" class="btn btn-outline-primary mr-2">
                    <i class="fas fa-shopping-cart"></i>
                </a>
                <a href="Commandes-Client.php" class="btn btn-outline-primary mr-2">
                    <i class="fas fa-box"></i>
                </a>
                <?php if ($_SESSION['role_user'] == 'admin') { ?>
                    <a href="Main-Admin.php" class="btn btn-outline-secondary mr-2">
                        <i class="fas fa-user-shield"></i>
                    </a>
                <?php } ?>
                <a href="Edit-client.php" class="btn btn-outline-warning mr-2">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="index.php" class="btn btn-outline-danger mr-2">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
                <button class="btn btn-dark btn-dark-mode">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </header>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4">Modifier Vos informations</h2>
                <form action="" method="post">
                    <div class="form-group">
                        <input type="text" class="form-control" name="Nom" placeholder="Nom" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="Prenom" placeholder="Prénom" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="E-mail" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="NumTele" placeholder="Numéro de Téléphone">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="Adresse" placeholder="Adresse">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="oldPass" placeholder="Mot de passe" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="PasswordN1" placeholder=" Nouveau Mot de passe" >
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="PasswordN2" placeholder="Répétez le nouveau mot de passe" >
                    </div>
                    <input type="submit" value=" Modifier " class="text-dark btn btn-outline-secondary mr-2 col-12">
                </form>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Get form data
                    $email = $_POST['E-mail'];
                    $passwordN1 = $_POST['PasswordN1'];
                    $passwordN2 = $_POST['PasswordN2'];
                    $password_plain = $_POST['oldPass'];
                    $nom = $_POST['Nom'];
                    $pren = $_POST['Prenom'];
                    $NumTele = $_POST['NumTele'];
                    $adr = $_POST['Adresse'];
                    // Check if password matches
                    if ($passwordN1 !== $passwordN2) {
                        echo "<div class='text-danger col-12 mr-2'>Passwords do not match.</div>";
                        exit;
                    }
                    if (!strpos($email, "@")) {
                        echo "<div class='text-danger col-12 mr-2'>Please enter a valid email address.</div>";
                        exit;
                    }
                    $sql = "SELECT password_user FROM user WHERE UserName = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    if (password_verify($password_plain, $row['password_user'])) {
                        $hashed_password = password_hash($passwordN1, PASSWORD_DEFAULT);


                        // Insert user data into database
                        $sql = "UPDATE user SET nom_user = '?', prenom_user = '?', email_user = '?', num_tel_user = '?', password_user = '?', adresse_user = '?' WHERE user.UserName = '?'";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("sssssss", $nom, $pren, $NumTele, $email, $hashed_password, $adr,$username);


                        if ($stmt->execute()) {


                            echo "<div class='text-success col-12 mr-2'>User registered successfully!</div>";
                            // Redirect the user to the login page or any other page
                            header("Location: Main-Client.php");
                            exit;
                        } else {
                            echo "<div class='text-danger col-12 mr-2'>Error: " . $stmt->error . "</div>";
                        }
                    } else {
                        // Password is incorrect
                        echo "<div class='text-danger col-12 mr-2'>Incorrect password.</div>";
                    }

                    // Hash the password


                    // Close statement
                    $stmt->close();
                }

                // Close database connection
                $conn->close();
                ?>
            </div>


        </div>
    </div>
    <script>
        const btnDarkMode = document.querySelector('.btn-dark-mode');
        const body = document.body;

        btnDarkMode.addEventListener('click', () => {
            body.classList.toggle('light-mode');
            body.classList.toggle('dark-mode');
            if (body.classList.contains('dark-mode')) {
                btnDarkMode.classList.add('btn-dark');
                btnDarkMode.classList.remove('btn-primary');
            } else {
                btnDarkMode.classList.add('btn-primary');
                btnDarkMode.classList.remove('btn-dark');
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit-id.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>