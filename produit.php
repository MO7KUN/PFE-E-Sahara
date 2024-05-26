<?php
include_once('Connection Open.php');
$produit = "";
session_start();
$_GET['product_id']=1;

// Check if product ID is provided
if(isset($_GET['product_id'])) {
    // Fetch product details from the database based on the provided product ID
    $product_id = $_GET['product_id'];
    $sql = "SELECT * FROM produit WHERE ID_Produit = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if product is found
    if($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        // Redirect back to Main-Client.php with an error message
        header("Location: Main-Client.php?error=Product%20not%20found");
        exit();
    }
} else {
    // Redirect back to Main-Client.php with an error message
    header("Location: Main-Client.php?error=Product%20ID%20not%20provided");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <title>Product Details</title>
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

    .card img {
      border-radius: 10px 10px 0 0;
      /* Match the card border radius */
    }

    .card-body {
      padding: 20px;
      display: flex;
      flex-direction: column;
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

    /* Dark mode button */
    .btn-dark-mode {
      color: #fff;
      padding: 0.375rem 0.75rem;
      font-size: 1rem;
      line-height: 1.5;
      border-radius: 0.2rem;
    }

    /* Email text color in dark mode */
    .dark-mode .card-text {
      color: #000000;
      /* Black color */
    }
  </style>
</head>

<body class="light-mode">
  <header>
    <div class="container d-flex justify-content-between align-items-center header-container">
      <h1 class="font-weight-bold mb-0">E-Sahara</h1>
      <div class="search-bar-container">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Chercher un produit" aria-label="Chercher un produit" aria-describedby="basic-addon2">
          <div class="input-group-append">
            <button class="btn btn-primary" type="button">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
              </svg>
            </button>
          </div>
        </div>
      </div>
      <div class="d-flex nav-buttons align-items-center">
        <a href="Main-Client.php" class="btn btn-outline-primary mr-2">Main</a>
        <a href="Panier.php" class="btn btn-outline-primary mr-2">Panier</a>
        <a href="Commandes.php" class="btn btn-outline-primary mr-2">Commandes</a>
        <a href="index.php" class="btn btn-outline-danger mr-2">Log Out</a>
        <button class="btn btn-dark btn-dark-mode">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16
                    fill=" currentColor" class="bi bi-moon" viewBox="0 0 16 16">
            <path d="M6 0a6 6 0 0 0 0 12 5.96 5.96 0 0 0 3.9-1.484 6.993 6.993 0 0 1-1.528-.164A5 5 0 0 1 7 1 5.977 5.977 0 0 0 6 0zM4 2a4 4 0 1 1-1 7.93c.29-.33.561-.684.805-1.063A3 3 0 1 0 3 4a4 4 0 0 1 1-2z" />
          </svg>
        </button>
      </div>
    </div>
  </header>
  <div class="container mt-4">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <div class="card shadow-sm">
          <img class="card-img-top" src="<?php echo $product['image_produit']; ?>" alt="Photo du produit">
          <div class="card-body">
            <h4 class="card-title font-weight-bold text-black-50"><?php echo $product['Libelle_produit']; ?></h4>
            <p class="font-weight-bold card-text"><?php echo $product['prix_unitaire'] . " Dh"; ?></p>
            <p class="card-text"><?php echo $product['description_produit']; ?></p>
            <!-- You can add more product details here -->
          </div>
        </div>
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