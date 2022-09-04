<?php 
if(@$_GET["logout"]){
    $_SESSION["user_id"] = null;
    header("Location:/");
    die();
}

$user = DB::get("users", ["id" => $_SESSION["user_id"]]);
$CURRENT_USER = $user;
$variables_groups = DB::select("variables_groups", []);

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

    <?php foreach($variables_groups as $group){
      if(@$user["is_admin"] || @in_array($group["id"], $user["variables_groups"])){
        ?>
        <div onclick="document.location='/?page=content&group=<?= $group['id'] ?>'" class="section"><?= $group["name"] ?></div>
        <?php
      }
    } ?>
    <div class="separation js-level-1"></div>
    <?php if(@$user["is_admin"]){ ?>
      <div onclick="document.location='/?page=variables_groups'" class="section">Groupes de variables</div>
      <div onclick="document.location='/?page=screens'" class="section">Écrans</div>
      <div onclick="document.location='/?page=users'" class="section">Utilisateurs</div>
      <div onclick="document.location='/?page=settings'" class="section">Paramètres</div>
    <?php } ?>
    <div onclick="document.location='/?page=account'" class="section">Mon compte</div>      

    </div>

    <div class="mainpage" onclick="close_sidebar();">

        <?php if(true){ @$_GET["page"] === "content" ? require(__DIR__ . "/content.php") : ""; } ?>
        <?php @$_GET["page"] === "account" ? require(__DIR__ . "/account.php") : ""; ?>
        <?php if(@$user["is_admin"]){ @$_GET["page"] === "screens" ? require(__DIR__ . "/screens.php") : ""; } ?>
        <?php if(@$user["is_admin"]){ @$_GET["page"] === "users" ? require(__DIR__ . "/users.php") : ""; } ?>
        <?php if(@$user["is_admin"]){ @$_GET["page"] === "variables_groups" ? require(__DIR__ . "/variables_groups.php") : ""; } ?>
        <?php if(@$user["is_admin"]){ @$_GET["page"] === "settings" ? require(__DIR__ . "/settings.php") : ""; } ?>

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