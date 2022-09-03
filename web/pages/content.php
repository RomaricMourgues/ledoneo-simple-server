<?php

$variables = DB::get("variables_groups", ["id" => intval(@$_GET["group"])]);
$title = $variables["name"];
$variables = $variables["variables"];

$variables_values = DB::get("variables_values", ["id" => intval(@$_GET["group"])]);
$values = @$variables_values["values"] ?: [];

if(@$_POST["::updated"]) {
    foreach($_POST as $key => $value) {
        $split = explode("_", $key);
        $var_name = @join("_", array_slice($split, 1));
        if($split[0] === "previous" && isset($_POST[$var_name]) && @$_POST[$var_name] !== $value) {
            $values[$var_name] = @$_POST[$var_name];
        }
    }
    $variables_values["id"] = intval(@$_GET["group"]);
    $variables_values["values"] = $values;
    DB::upsert("variables_values", $variables_values);
}

?>

<div class="page">
    <form action="" method="post">

        
        <div class="title" style="display: inline-block"><?= $title ?></div>
        
        <button type="submit" style="display: inline-block; margin-left: 24px">Envoyer la mise à jour</button>

        <div class="form">

                <input type="hidden" value="1" name="::updated" />

                <?php foreach($variables as $variable){ ?>
                    <?php
                        $value = @$values[$variable["var_name"]] ?: "";
                        require(__DIR__ . "/components/content/" . $variable["type"] . ".php");
                    ?>
                    <input type="hidden" value="<?= $value ?>" name="previous_<?= $variable["var_name"] ?>"
                    <br/>
                <?php } ?>

                
            </div>

        </form>            
</div>