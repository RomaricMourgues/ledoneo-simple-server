<?php 

if(@$_GET["action"] === "add" || @$_GET["action"] === "edit"){
?>
    <div class="page">
        <div class="title">Éditer / ajouter un groupe de variables</div>
            <div class="form">
                <?php require(__DIR__ . "/components/variables_group_edit.php"); ?>
            </div>
        </div>
    </div>
<?php
}else{

$variables_groups = DB::select("variables_groups");
array_multisort(array_column($variables_groups, 'id'), SORT_ASC, $variables_groups);

?>

<div class="page">
    <div class="title">Groupes de variables</div>

    <form action="?page=variables_groups&action=add" method="POST">
        <button style="float: right">Ajouter un groupe de variables</button>
    </form>

    <br/><br/><br/>

    <div class="dataTables_wrapper">
        <table id="variables_groups_table" class="display dataTable" role="grid" aria-describedby="variables_groups_table_info">
            <thead>
                <tr>
                    <th style="width: 30px; text-align: left">Id</th>
                    <th style="text-align: left">Nom du groupe</th>
                    <th style="text-align: left">Variables</th>
                    <th style="width: 150px; text-align: right">Édition</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($variables_groups as $vgroup){ ?>
                    <tr role="row" class="odd">
                        <td><?= $vgroup["id"] ?></td>
                        <td><?= $vgroup["name"] ?></td>
                        <td><?= count($vgroup["variables"]) ?></td>
                        <td style="text-align: right; height: 40px;">
                            <form action="?page=variables_groups&action=edit&group_id=<?= $vgroup['id'] ?>" method="POST">
                                <button class="warning">
                                    Modifier
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

<?php } ?>