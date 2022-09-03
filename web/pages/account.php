<div class="page">
    
    <div class="title">
    Compte
    </div>

    <div class="form">

        <div class="title">Modifier mon mot de passe</div>
        
        <?php 
            $update_password_initial_user_id = $CURRENT_USER["id"];
            require(__DIR__ . "/components/update_password.php");
        ?>

    </div>

</div>