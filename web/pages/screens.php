<?php 

if(@$_GET["action"] === "add" || @$_GET["action"] === "edit"){
?>
    <div class="page">
        <div class="title">Éditer / ajouter un écran</div>
            <div class="form">
                <?php require(__DIR__ . "/components/screen_edit.php"); ?>
            </div>
        </div>
    </div>
<?php
}else{

$screens = DB::select("screens");
array_multisort(array_column($screens, 'id'), SORT_ASC, $screens);

?>

<div class="page">
    <div class="title">Écrans</div>

    <form action="?page=screens&action=add" method="POST">
        <button style="float: right">Ajouter un écran</button>
    </form>

    <br/><br/><br/>

    <div class="dataTables_wrapper">
        <table id="screens_table" class="display dataTable" role="grid" aria-describedby="screens_table_info">
            <thead>
                <tr>
                    <th style="width: 30px; text-align: left">Id</th>
                    <th style="text-align: left">Nom d'écran</th>
                    <th style="text-align: left">URL</th>
                    <th style="width: 150px; text-align: right">Édition</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($screens as $screen){ ?>
                    <tr role="row" class="odd">
                        <td><?= $screen["id"] ?></td>
                        <td><?= $screen["name"] ?></td>
                        <td><a target="_blank" href="/screen.php?id=<?= $screen['id'] ?>">/screen.php?id=<?= $screen['id'] ?></a></td>
                        <td style="text-align: right; height: 40px;">
                            <form style="display: inline-block" action="?page=screens&action=edit&screen_id=<?= $screen['id'] ?>" method="POST">
                                <button class="warning">
                                    Modifier
                                </button>
                            </form>
                            <button style="display: inline-block" onclick="window.open('/screen.php?id=<?= $screen['id'] ?>')">
                                Voir
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

<?php } ?>