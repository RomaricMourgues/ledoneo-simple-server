<?php 
if(@$_GET["logout"]){
    $_SESSION["user_id"] = null;
    header("Location:/");
    die();
}

$user = DB::get("users", ["id" => $_SESSION["user_id"]]);

?>
<div class="sidebar_structure">

    <div class="mobile_title">
    <div class="button" onclick="open_sidebar();">
        <img src="images/menu.svg">
    </div>
    </div>

    <div class="sidebar">

    <div class="userinfo">

        Bonjour <b class="js-username"><?= $user["username"] ?></b>, vous êtes <b><?= $user["is_admin"] ? "Administrateur" : "Modérateur" ?></b>.
        <br>
        <a onclick="document.location = '?logout=1'" href="#">Se déconnecter</a>

    </div>

    <div onclick="document.location='/?page=content'" class="section">Écran 1</div>
    <div class="separation js-level-1"></div>
    <div onclick="document.location='/?page=screens'" class="section">Écrans</div>
    <div onclick="document.location='/?page=users'" class="section">Utilisateurs</div>

    </div>

    <div class="mainpage" onclick="close_sidebar();">

        <?php $_GET["page"] === "content" ? require(__DIR__ . "/content.php") : "" ?>
        <?php $_GET["page"] === "screens" ? require(__DIR__ . "/screens.php") : "" ?>
        <?php $_GET["page"] === "users" ? require(__DIR__ . "/users.php") : "" ?>

    </div>

</div>

<script>
  function open_sidebar(){
    $(".sidebar").addClass("open");
  }

  function close_sidebar(){
    $(".sidebar").removeClass("open");
  }
</script>