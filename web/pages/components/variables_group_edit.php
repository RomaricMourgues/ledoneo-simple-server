<?php

if(@!$CURRENT_USER["is_admin"]){
    die();
}


$editing = @$_GET["group_id"] ? true : false;
$group = [];
if($editing){
    $group = DB::get("variables_groups", ["id"=>intval($_GET["group_id"])]);
}

if(@$_POST["delete"]){
    DB::delete("variables_groups", ["id"=>intval($_GET["group_id"])]);
    header("Location:/?page=variables_groups");
}

$hasError = false;
if(@$_POST["name"]){

    $group["id"] = intval(@$group["id"] ?: DB::autoIncrement("variables_groups"));
    $group["name"] = $_POST["name"];
    $group["variables"] = @json_decode($_POST["variables"], true) ?: [];

    if(!$hasError){
        DB::upsert("variables_groups", $group);
        header("Location:/?page=variables_groups");
    }

}

?>

<form id="group_form" method="POST" action="">

    <div class="label">Nom du groupe</div>
    <input class="<?= $hasError ? "has_error" : "" ?>" name="name" value="<?= @$group['name'] ?>" placeholder="Nom">
    <br/>
    
    <div class="label">Variables</div>
    <input id="variables_input" type="hidden" name="variables" value="<?= @json_encode($group["variables"]) ?: [] ?>" />
    <div id="var_list"></div>
    <button type="button" class="default" onclick="addVar()">Ajouter</button>
    <br/>

    <?php if($hasError) { ?>
        <br />
        <br />
        <span className="error">
            Une erreur a eu lieu.
        </span>
        <br />
    <?php } ?>
    <br/><br/>
    <button type="submit"><?= $editing ? "Enregistrer" : "Ajouter" ?> le groupe</button>

</form>

<?php if($editing) { ?>
    <br/>
    <form method="POST" action="" onsubmit="return confirm('Confirmer la suppression ?');">
        <input type="hidden" name="delete" value="1" />
        <button type="submit" class="danger">Supprimer le groupe</button>
    </form>
<?php } ?>

<br/><br/>


<div style="display: none">
    <div id="var_line_template">
        <input type="hidden" class="var_id" />
        <div style="font-family: monospace; font-size: 12px; margin-bottom: 4px">&#60;?= $content["<span class="var_code_name_display"></span>"] ?&#62;</div>
        <div style="display: flex;">
            <input onchange="updateVar(this)" class="var_code_name" value="" placeholder="Code de la variable" style="font-family: monospace; margin-right: 8px">
            <input onchange="updateVar(this)" class="var_name" value="" placeholder="Nom de la variable" style="margin-right: 8px">
            <select onchange="updateVar(this)" class="var_type" value=""  style="margin-right: 8px">
                <option disabled>-- Entrées</option>
                <option value="input">Texte</option>
                <option value="input-compact">Texte compact</option>
                <!--
                <option value="textarea">Texte multi-lignes</option>
                <option value="cellules">Cellules</option>
                <option value="checkbox">Booléen</option>
                <option value="number">Nombre</option>
                <option value="image">Image</option>
                <option value="video">Video</option>-->
                <option disabled>-- Affichage</option>
                <option value="label">Label</option>
            </select>
            <button type="button" class="danger" style="height: 40px" onclick="delVar(this)">Supprimer</button>
            <span href="#" style="cursor: pointer; height: 40px; line-height: 40px; padding-left: 12px;" onclick="moveVar(this, true)">&#8593</span>
            <span href="#" style="cursor: pointer; height: 40px; line-height: 40px; padding-left: 4px;" onclick="moveVar(this, false)">&#8595</span>
        </div>
    </div>
</div>

<script>

/**
 * Variables
 */

var variables = <?= @json_encode($group["variables"]) ?: "[]" ?>;

function addVar() {
    variables.push({
        id: variables.map(u=>u.id).reduce((a, c) => Math.max(a, c), 0) + 1,
        name: "",
        type: "input"
    });
    variables.sort((a, b) => a.id - b.id);
    updateVarView();
}

function delVar(e) {
    const id = e.parentNode.parentNode.getElementsByClassName("var_id")[0].value;
    variables = variables.filter(u => parseInt(u.id) !== parseInt(id));
    updateVarView();
}

function moveVar(e, asc = false) {
    const id = e.parentNode.parentNode.getElementsByClassName("var_id")[0].value;
    let idSwitched = variables.filter(a => asc ? (a.id < id) : (a.id > id));
    idSwitched = idSwitched[asc ? (idSwitched.length - 1) : 0]?.id;
    if(idSwitched){
        variables = variables.map(u => {
            if(parseInt(u.id) === parseInt(id)){
                u.id = idSwitched;
            }else if(parseInt(u.id) === parseInt(idSwitched)){
                u.id = id;
            }
            return u;
        });
        variables.sort((a, b) => a.id - b.id);
        updateVarView();
    }
}

function updateVar(e) {
    const varLine = e.parentNode.parentNode;
    const id = varLine.getElementsByClassName("var_id")[0].value;
    variables = variables.map(u => {
        if(parseInt(u.id) === parseInt(id)){
            u.name = varLine.getElementsByClassName("var_name")[0].value;
            u.type = varLine.getElementsByClassName("var_type")[0].value;
            u.var_name = varLine.getElementsByClassName("var_code_name")[0].value.toLocaleLowerCase().replace(/[^a-z0-9]/gm, "_");
        }
        return u;
    });
    updateVarView();
}

function updateVarView() {
    document.getElementById("variables_input").value = JSON.stringify(variables);

    const varListDom = document.getElementById("var_list");
    varListDom.innerHTML = "";
    for(const variable of variables){
        const varLine = document.getElementById("var_line_template").cloneNode(true);
        console.log(varLine);
        varLine.removeAttribute("id");
        varLine.getElementsByClassName("var_id")[0].value = variable.id;
        varLine.getElementsByClassName("var_name")[0].value = variable.name;
        varLine.getElementsByClassName("var_type")[0].value = variable.type;
        varLine.getElementsByClassName("var_code_name")[0].value = variable.var_name;
        varLine.getElementsByClassName("var_code_name_display")[0].innerHTML = variable.var_name;
        varLine.getElementsByClassName("var_code_name")[0].style.display = ["label"].includes(variable.type) ? "none" : "";
        varLine.getElementsByClassName("var_code_name_display")[0].parentNode.style.display = ["label"].includes(variable.type) ? "none" : "";
        varListDom.append(varLine);
    }
}

updateVarView();

</script>