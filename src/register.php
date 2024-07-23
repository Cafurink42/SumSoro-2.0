<?php
require_once('connection.php');


$email = $password = $confirm_password = "";
$email_error = $password_error = $confirm_password_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim(isset($_POST["email"])))){ 
        $username_error = "Por favor, insira um email válido.";
    } else{

        $sql = "SELECT id_user FROM users WHERE email = :email";
        
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            
            $param_email = trim($_POST["email"]);
            
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_error = "Este email já está em uso. Tente outro diferente :)";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }

            unset($stmt);
        }
    }
    
    if(empty(trim($_POST["password"]))){
        $password_error = "Por favor insira uma senha.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_error = "A senha deve ter pelo menos 6 caracteres.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Por favor, confirme a senha.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_error) && ($password != $confirm_password)){
            $confirm_password_err = "A senha não confere.";
        }
    }
    
    if(empty($email_error) && empty($password_error) && empty($confirm_password_error)){
        
        $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
         
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            
            $options = [
                'cost' => 12,
            ];

            
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_BCRYPT, $options); // Creates a password hash
            
            if($stmt->execute()){
                header("location: login.php");
            } else{
                echo "Ops! Algo deu errado. Por favor, tente novamente";
            }

            unset($stmt);
        }
    }
    
    unset($pdo);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SumSoro Register</title>
  <link rel="stylesheet" href="style.css">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <link href="./variables.scss">
  <link rel="icon" href="SumSoroIcons/sumsoroicon.jpg">
  <link rel="manifest" href="manifest.json">
  <style>
    h2{
        text-align: center;
    }

  </style>
</head>
<body>
  <h2 class="col-md-3 mx-auto" >Faça o seu cadastro</h2>
  <div class="col-md-3 container d-flex align-items-center " style="min-height: 50vh;">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
      <div class = "form-group">
        <label for="email">Email</label>
        <input class = "form-control <?php echo (!empty($email_error)) ? 'is-invalid' : ''; ?>" type="email" name="email" placeholder="Digite o seu email" value="<?php echo $email; ?>">
        <span class  = "invalid-feedback"><?php echo $email_error; ?></span>
      </div>
      <div class  ="form-group">
        <label for="password">Senha</label>
        <input type="password" name="password" placeholder="Registre a sua senha" class = "form-control <?php echo (!empty($password_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
        <span class  ="invalid-feedback"><?php echo $password_error;?>
      </div>
      <div>
        <label for = "confirm_password">Confirmar Senha</label>
        <input type  = "password" name = "confirm_password" placeholder ="Confirme a sua senha" class="form-control <?php echo (!empty($confirm_password_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
        <span class  = "invalid-feedback"><?php echo $confirm_password_error;?>
      </div>
      <br>
      <input type="submit" value = "Registre-se"class="p-1 mb-1 bg-success text-white btn btn-success">
    </form>
  </div>
  <span class  = "position-absolute top-10 start-50 translate-middle"" data-bs-toggle="offcanvas">Já tem registro ? Entre <a href="login.php">aqui</a></span>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
  </script>
</body>


</html>