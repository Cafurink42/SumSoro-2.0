<?php
session_start();

// Verifique se o usuário já está logado, em caso afirmativo, redirecione-o para a página de boas-vindas
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
  header("location: index.php");
  exit;
}

require_once('connection.php');
$email = $password = "";
$email_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty(trim($_POST["email"]))) {
    $username_err = "Por favor, seu email.";
  } else {
    $username = trim($_POST["email"]);
  }

  if (empty(trim($_POST["password"]))) {
    $password_err = "Por favor, insira sua senha.";
  } else {
    $password = trim($_POST["password"]);
  }

  if (empty($email_err) && empty($password_err)) {
    $sql = "SELECT id_user, email, password FROM users WHERE email = :email";

    if ($stmt = $pdo->prepare($sql)) {
      $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

      $param_email = trim($_POST["email"]);

      if ($stmt->execute()) {
        if ($stmt->rowCount() == 1) {
          if ($row = $stmt->fetch()) {
            $id = $row["id_user"];
            $username = $row["email"];
            $hashed_password = $row["password"];
            if (password_verify($password, $hashed_password)) {
              session_start();

              $_SESSION["loggedin"] = true;
              $_SESSION["id_user"] = $id;
              $_SESSION["username"] = $email;

              header("location: index.php");
            } else {
              $login_error = "Email de usuário ou senha inválidos.";
            }
          }
        } else {
          $login_error = "Email de usuário ou senha inválidos.";
        }
      } else {
        echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
      }

      unset($stmt);
    }
  }

  unset($pdo);
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Daniel Guth">
  <meta name="description" content="SumSoro é uma ferramenta que ajuda operadores e analistas a fazerem calculos de producação e relatórios">
  <meta name="keywords" content="SumSoro, sumsoro, ferramenta, calculos, produção, soro">
  <link rel="stylesheet" href="style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <link href="./variables.scss">
  <link rel="icon" href="SumSoroIcons/sumsoroicon.jpg">
  <link rel="manifest" href="manifest.json">
  <style>
    body {
      background-color: gainsboro;
    }
  </style>
</head>

<body>

  <header>
    <?php
    if (!empty($login_err)) {
      echo '<div class="alert alert-danger">' . $login_err . '</div>';
    }
    ?>

    <nav class="navbar fixed-top card text-bg-light p-3">
      <div class="container-fluid">
        <a class="navbar-brand"></a>
        <span class="SumSorologinTitle">
          <h1 class="title">SumSoro</h1>
        </span>
        <button class="navbar-toggler" id="button" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
          <svg xmlns="http://www.w3.org/2000/svg" width="40" height="30" fill="currentColor" class="bi bi-arrow-bar-right" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M6 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L12.293 7.5H6.5A.5.5 0 0 0 6 8m-2.5 7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5" />
          </svg>
          Login
        </button>
        <div class="offcanvas offcanvas-end bg-body-secondary" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
          <div class="offcanvas-header">
            <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">
            <p>Faça o seu login</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
              <div class="form-group">
                <label for="exampleInputEmail1">Enderço de email</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="email">
                <small id="emailHelp" class="form-text text-muted">Seu email nunca será compartilhado com ninguém.</small>
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">Senha</label>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Senha" name="password">
              </div>
              <br>
              <p>Registre-se: <a href="register.php">aqui</a></p>
              <br>
              <input type="submit" value="Enviar" class="p-1 mb-1 bg-success text-white btn btn-success">
            </form>
          </div>
        </div>
      </div>
    </nav>
  </header>
  <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwww.silocc.com.au%2Fwp-content%2Fuploads%2F2019%2F03%2FIMG_1454.jpg&f=1&nofb=1&ipt=228874f321bad5ee3dfb169c1a029f16c5f3fc0e95c7d8947c73a1c371f7694d&ipo=images" class="d-block w-100" alt="...">
      </div>
      <div class="carousel-item">
        <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwww.silocc.com.au%2Fwp-content%2Fuploads%2F2019%2F03%2FIMG_1454.jpg&f=1&nofb=1&ipt=228874f321bad5ee3dfb169c1a029f16c5f3fc0e95c7d8947c73a1c371f7694d&ipo=images" class="d-block w-100" alt="...">
      </div>
      <div class="carousel-item">
        <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwww.silocc.com.au%2Fwp-content%2Fuploads%2F2019%2F03%2FIMG_1454.jpg&f=1&nofb=1&ipt=228874f321bad5ee3dfb169c1a029f16c5f3fc0e95c7d8947c73a1c371f7694d&ipo=images" class="d-block w-100" alt="...">
      </div>
    </div>
  </div>

  <footer class="text-center bg-body-tertiary">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      © 2024 Copyright:
      <a class="text-body">SumSoro.com</a>
    </div>
  </footer>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
</script>

</html>