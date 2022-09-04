<?php

if(@!$CURRENT_USER["is_admin"]){
    die();
}


$editing = @$_GET["screen_id"] ? true : false;
$screen = [];
if($editing){
    $screen = DB::get("screens", ["id"=>intval($_GET["screen_id"])]);
}


if(@$_POST["delete"]){
    DB::delete("screens", ["id"=>intval($_GET["screen_id"])]);
    header("Location:/?page=screens");
}

$hasError = false;
if(@$_POST["name"]){

    $editing = false;
    if(@$screen["id"]){
        $editing = true;
    }

    $screen["id"] = intval(@$screen["id"] ?: DB::autoIncrement("screens"));
    $screen["name"] = $_POST["name"];
    $screen["content"] = $_POST["content"];
    $screen["style"] = $_POST["style"];
    $screen["variables_groups"] = @explode(",", str_replace(" ", "", $_POST["variables_groups"])) ?: [];

    if(@$_POST["draft"]) {
        DB::upsert("screens_previews", $screen);
        die;
    }else{
        if(!$hasError){
            DB::upsert("screens", $screen);
            header("Location:/?page=screens&action=edit&screen_id=" . $screen["id"]);
        }
    }

}

?>

<form id="screen_form" method="POST" action="">

    <div class="label">Nom de l'écran</div>
    <input class="<?= $hasError ? "has_error" : "" ?>" name="name" value="<?= @$screen['name'] ?>" placeholder="Nom">
    <br/>
    
    <div class="label">Groupes de variables (identifiants de groupes de variables séparés par des virgules)</div>
    <input name="variables_groups" value="<?= join(", ", @$screen["variables_groups"]?:[]) ?>" placeholder="1, 2, 6" />
    <br/>

    <div class="label">Style (css)</div>
    <div id="container_style" style="height:400px;border:1px solid black;"></div>
    <input type="hidden" id="container_style_value" name="style"/>


    <div class="label">HTML/PHP</div>
    <div id="container" style="height:400px;border:1px solid black;"></div>
    <input type="hidden" id="container_value" name="content"/>

    <?php if($hasError) { ?>
        <br />
        <br />
        <span className="error">
            Une erreur a eu lieu.
        </span>
        <br />
    <?php } ?>
    <br/><br/>
    <button type="button" onclick="updateEditorValue(); document.getElementById('screen_form').submit();"><?= $editing ? "Enregistrer" : "Ajouter" ?> l'écran</button>

</form>

<?php if($editing) { ?>
    <br/>
    <form method="POST" action="" onsubmit="return confirm('Confirmer la suppression ?');">
        <input type="hidden" name="delete" value="1" />
        <button type="submit" class="danger">Supprimer l'écran</button>
    </form>
<?php } ?>

<br/><br/>

<hr/>

<div class="label">Preview <a href="#" onclick="refreshPreview(true)">refresh</a></div>
<iframe id="preview_frame" src="/screen.php?id=<?= intval($_GET['screen_id']) ?>&preview=1" style="width: 600px; height: 400px; border: 0px;"></iframe>

<br/><br/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.26.1/min/vs/loader.min.js"></script>
<script>

/**
 * Designer
 */

require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.26.1/min/vs' }});
require(["vs/editor/editor.main"], () => {
  window.editor = monaco.editor.create(document.getElementById('container'), {
    value: `<?= @$screen["content"] ?: "" ?>`,
    language: 'php',
    theme: 'vs-light',
  });
  window.editor_style = monaco.editor.create(document.getElementById('container_style'), {
    value: `<?= @$screen["style"] ?: "" ?>`,
    language: 'css',
    theme: 'vs-light',
  });
});

function updateEditorValue() {
    document.getElementById('container_value').value = editor.getValue();
    document.getElementById('container_style_value').value = editor_style.getValue();
}

/**
 * Preview
 */

var oldValue = "";
var oldValueStyle = "";
function refreshPreview(force) {
    const newValue = editor.getValue();
    const newValueStyle = editor_style.getValue();
    if(force || oldValue !== newValue || oldValueStyle !== newValueStyle){
        oldValue = newValue;
        oldValueStyle = newValueStyle;
        updateEditorValue();
        const formattedFormData = new FormData(document.getElementById("screen_form"));
        formattedFormData.append('draft', '1');
        formattedFormData.append('content', document.getElementById('container_value').value)
        fetch(document.location,{
            method: 'POST',
            body: formattedFormData
        });
    }
    if(force){
        document.getElementById("preview_frame").contentWindow.location.reload();
    }
}

setInterval(() => {
    refreshPreview();
}, 2000);
</script>