<?php

$hasError = false;
if(@$_POST["username"]){
  $user = @DB::select("users", ["username" => $_POST["username"]])[0];
  if($user && password_verify($_POST["password"], $user["password"])){
    $_SESSION["user_id"] = $user["id"];
    header("Location:/");
    die();
  }else{
    $hasError = true;
  }
}

?>
<div class="login">
  <form class="login_bloc" action="" method="POST">
    <div class="title">Connectez-vous</div>
    <br />

    <input
      class="<?= $hasError ? "has_error" : "" ?>"
      name="username"
      type="text"
      placeholder="Nom d'utilisateur"
      autoComplete="off"
    ></input>
    <br />
    <input
      class="<?= $hasError ? "has_error" : "" ?>"
      name="password"
      type="password"
      placeholder="Mot de passe"
      autoComplete="off"
    ></input>
    <br />
    <input
      type="submit"
      value="Connexion"
      style="margin-top: 20px"
    ></input>

    <?php if($hasError) { ?>
        <br />
        <br />
        <span className="error">
          Votre mot de passe ou identifiant sont incorrects.
        </span>
        <br />
    <?php } ?>
  </form>
</div>