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
            <div class="d-flex nav-buttons align-items-center">
                <a href="Main-Client.php" class="btn btn-outline-primary mr-2">Main</a>
                <a href="Clients.php" class="btn btn-outline-primary mr-2">Clients</a>
                <a href="Commandes.php" class="btn btn-outline-primary mr-2">Commandes</a>
                <a href="index.php" class="btn btn-outline-danger mr-2">Log Out</a>
                <button class="btn btn-dark btn-dark-mode">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-moon" viewBox="0 0 16 16">
                        <path d="M6 0a6 6 0 1 0 0 12 5.96 5.96 0 0 0 3.9-1.484 6.993 6.993 0 0 1-1.528-.164A5 5 0 0 1 7 1 5.977 5.977 0 0 0 6 0zM4 2a4 4 0 1 1-1 7.93c.29-.33.561-.684.805-1.063A3 3 0 1 0 3 4a4 4 0 0 1 1-2z" />
                    </svg>
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
</body>

</html>