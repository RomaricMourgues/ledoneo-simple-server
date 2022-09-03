<?php

@$userId = $_POST["user_id"];
if(@!$CURRENT_USER["is_admin"]){
    $userId = $_SESSION["user_id"];
}

$user = DB::get("users", ["id" => $userId]);

$hasError = false;
$hasBeenDone = false;
if(@$_POST["type"] === "password") {
    if(!password_verify($_POST["old"], $user["password"]) || $_POST["new"] !== $_POST["confirm"]){
        $hasError = true;
    }else{
        $hasBeenDone = true;
        $user["password"] = password_hash($_POST["new"], PASSWORD_BCRYPT);
        DB::upsert("users", $user);
    }
}

?>

<form method="POST" action="">

    <div class="label">Ancien mot de passe</div>
    <input class="<?= $hasError ? "has_error" : "" ?>" name="old" value="" type="password" placeholder="Ancien mot de passe">
    <br/>
    <br/>
    <div class="label">Nouveau mot de passe</div>
    <input class="<?= $hasError ? "has_error" : "" ?>" name="new" value="" type="password" placeholder="Nouveau mot de passe">
    <input class="<?= $hasError ? "has_error" : "" ?>" name="confirm" value="" type="password" placeholder="Confirmation du nouveau mot de passe">

    <input type="hidden" value="<?= @$update_password_initial_user_id ?>" name="user_id" />
    <input type="hidden" value="password" name="type" />

    <?php if($hasError) { ?>
        <br />
        <br />
        <span className="error">
            Votre ancien mot de passe est incorrect ou les nouveaux mots de passe ne sont pas égaux.
        </span>
        <br />
    <?php } ?>

    <?php if($hasBeenDone) { ?>
        <br />
        <br />
        <span className="success">
            Modification faites.
        </span>
        <br />
    <?php } ?>

    <br/><br/>
    <button type="submit">Enregistrer</button>

</form>