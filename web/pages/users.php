<?php 

if(@$_GET["action"] === "add" || @$_GET["action"] === "edit"){
?>
    <div class="page">
        <div class="title">Éditer / ajouter un utilisateur</div>
            <div class="form">
                <?php require(__DIR__ . "/components/user_edit.php"); ?>
            </div>
        </div>
    </div>
<?php
}else{

$users = DB::select("users");
array_multisort(array_column($users, 'id'), SORT_ASC, $users);

?>

<div class="page">
    <div class="title">Utilisateurs</div>

    <form action="?page=users&action=add" method="POST">
        <button style="float: right">Ajouter un utilisateur</button>
    </form>

    <br/><br/><br/>

    <div class="dataTables_wrapper">
        <table id="users_table" class="display dataTable" role="grid" aria-describedby="users_table_info">
            <thead>
                <tr>
                    <th style="width: 30px; text-align: left">Id</th>
                    <th style="text-align: left">Nom d'utilisateur</th>
                    <th style="text-align: left">Niveau d'accès</th>
                    <th style="width: 150px; text-align: right">Édition</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user){ ?>
                    <tr role="row" class="odd">
                        <td><?= $user["id"] ?></td>
                        <td><?= $user["username"] ?></td>
                        <td><?= $user["is_admin"] ? "Administrateur" : ( "Accès aux groupes de variables " . join(", ", $user["variables_groups"]) ) ?></td>
                        <td style="text-align: right; height: 40px;">
                            <?php if($user["id"] !== $CURRENT_USER["id"]){ ?>
                            <form action="?page=users&action=edit&user_id=<?= $user['id'] ?>" method="POST">
                                <button class="warning">
                                    Modifier
                                </button>
                            </form>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

<?php } ?>