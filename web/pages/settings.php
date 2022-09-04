<?php 

if(@$_POST["action"] === "style"){
    DB::upsert("settings", ["id" => 1, "style" => $_POST["style"]]);
}

error_log(json_encode($_FILES["backup"]));

if(@$_POST["action"] === "import" && @$_FILES["backup"]){
    error_log("HHHH");
    $zip = @$_FILES["backup"];
    error_log($zip);
}

if(@$_POST["action"] === "export"){
    
    $zipname = "export-".date("U").".zip";
    $zippath = __DIR__ . "/../exports/" . $zipname;
    @mkdir(__DIR__ . "/../exports/");
    $zip = new ZipArchive;
    $zip->open($zippath, ZipArchive::CREATE);
    if ($handle = opendir(__DIR__ . "/../../database/")) {
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." && !strstr($entry,'.zip')) {
            $zip->addFile(__DIR__ . "/../../database/" . $entry, $entry);
        }
      }
      closedir($handle);
    }
    $zip->close();

    ?><script>document.location = "/exports/<?= $zipname ?>";</script><?php die();
}

$settings = DB::get("settings", ["id" => 1]);

?>

<div class="page">
    <div class="title">Paramètres</div>



    <div class="form">
        <div class="label">Exporter / Importer</div>
        <br/>

        <form action="" method="POST">
            Exporter les données vers un fichier zip<br/>
            <button onclick="export()">Exporter</button>
            <input type="hidden" name="action" value="export" />
        </form> 
        <br/>

        <form action="" method="POST">
            Importer les données depuis un fichier zip (écrase les données actuelles !)<br/>
            <input type="hidden" name="action" value="import" />
            <input style="display: inline-block; width: 200px;" type="file" name="backup" />
            <button class="danger">Importer</button>
        </form>

    </div>

    <div class="form">
        <form action="" method="POST">

            <div class="label">Style global</div>
            <div id="container_style" style="height:400px;border:1px solid black;"></div>
            <input type="hidden" id="container_style_value" name="style"/>

            <input type="hidden" name="action" value="style" />
            <br/>
            <button onclick="updateEditorValue(); this.submit()">Enregistrer</button>

        </form>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.26.1/min/vs/loader.min.js"></script>
<script>

/**
 * Designer
 */

require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.26.1/min/vs' }});
require(["vs/editor/editor.main"], () => {
  window.editor_style = monaco.editor.create(document.getElementById('container_style'), {
    value: `<?= @$settings["style"] ?: "" ?>`,
    language: 'css',
    theme: 'vs-light',
  });
});

function updateEditorValue() {
    document.getElementById('container_style_value').value = editor_style.getValue();
}
</script>

<?php  ?>