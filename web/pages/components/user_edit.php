<?php

if(@!$CURRENT_USER["is_admin"]){
    die();
}

if(@$_GET["user_id"] === $CURRENT_USER["id"]){
    die();
}

$editing = @$_GET["user_id"] ? true : false;
$user = [];
if($editing){
    $user = DB::get("users", ["id"=>intval($_GET["user_id"])]);
}

if(@$_POST["delete"]){
    DB::delete("users", ["id"=>intval($_GET["user_id"])]);
    ?><script>document.location = "/?page=users";</script><?php die();
}

$hasError = false;
if(@$_POST["username"]){

    $user["id"] = intval($user["id"] ?: DB::autoIncrement("users"));
    $user["username"] = $_POST["username"];
    $user["is_admin"] = @$_POST["is_admin"] == "is_admin";
    $user["variables_groups"] = explode(",", str_replace(" ", "", $_POST["variables_groups"]));

    if($_POST["password"]){
        $user["password"] = password_hash($_POST["password"], PASSWORD_BCRYPT);
    }

    if(!$hasError){
        DB::upsert("users", $user);
        ?><script>document.location = "/?page=users";</script><?php die();
    }

}

?>

<form method="POST" action="">

    <div class="label">Nom d'utilisateur</div>
    <input class="<?= $hasError ? "has_error" : "" ?>" name="username" value="<?= @$user['username'] ?>" placeholder="Nom d'utilisateur">
    <br/>
    <br/>
    <div class="label">Mot de passe <?= $editing ? "(laisser vide si non modifié)" : "" ?></div>
    <input class="<?= $hasError ? "has_error" : "" ?>" name="password" value="" placeholder="Mot de passe">

    <div class="label">Administrateur</div>
    <div>
      <input type="checkbox" id="is_admin" name="is_admin" value="is_admin" <?= @$user['is_admin'] ? "checked" : "" ?> >
      <label for="is_admin">Aministrateur</label>
    </div>

    <div class="label">Accès (si non administrateur, identifiants de groupes de variables séparés par des virgules)</div>
    <input class="<?= $hasError ? "has_error" : "" ?>" name="variables_groups" value="<?= join(", ", @$user['variables_groups'] ?: []) ?>" placeholder="1, 2, 6">

    <?php if($hasError) { ?>
        <br />
        <br />
        <span className="error">
            Une erreur a eu lieu.
        </span>
        <br />
    <?php } ?>

    <br/><br/>
    <button type="submit">Ajouter / Éditer l'utilisateur</button>
    <br/><br/>
</form>

<?php if($editing) { ?>
    <form method="POST" action="" onsubmit="return confirm('Confirmer la suppression ?');">
        <input type="hidden" name="delete" value="1" />
        <button type="submit" class="danger">Supprimer l'utilisateur</button>
    </form>
    <br/>
<?php } ?>
