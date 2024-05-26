<!DOCTYPE html>
<html lang="en">
<?php
require_once('Connection Open.php');
session_start();
$_SESSION['role_user'] = "";
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

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 25px;
            height: 50px;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
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
        <h1 class="mb-4">Login</h1>
        <form action="" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="Identificateur" placeholder="Email ou Nom d'utilisateur" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="Password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary">Valider</button>
        </form>
        <?php
        if (isset($_POST['Identificateur']) && isset($_POST['Password'])) {
            $login = $_POST['Identificateur'];
            $password_plain = $_POST['Password']; // Plain-text password entered by user

            // Check if the login is email or username
            if (strpos($login, "@") !== false) {
                $sql = "SELECT email_user, password_user, role_user FROM user WHERE email_user = ?";
            } else {
                $sql = "SELECT UserName, password_user, role_user FROM user WHERE UserName = ?";
            }

            // Prepare and bind the SQL statement
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $login);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Verify the password using password_verify()
                    if (password_verify($password_plain, $row['password_user'])) {
                        $_SESSION['role_user'] = $row['role_user'];
                        $_SESSION['UserName'] = $row['UserName'];
                        // Password is correct
                        if ($row['role_user'] == "admin") {
                            header("Location: Main-Admin.php");
                            exit;
                        } else if ($row['role_user'] == "user") {
                            header("Location: Main-Client.php");
                            exit;
                        }
                    } else {
                        // Password is incorrect
                        echo "<div class='text-danger'>Incorrect password.</div>";
                    }
                }
            } else {
                // User not found
                echo "<div class='text-danger'>User not found.</div>";
            }

            // Close statement
            $stmt->close();
        }

        // Close database connection
        $conn->close();
        ?>
    </div>
    <p class="footer">Don't have an account? <a href="SignUp.php">Sign up</a></p>
</body>

</html>
