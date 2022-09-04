<?php
if(!@$_GET["preview"]){
    error_reporting(false);
}

require("../database.php");

$settings = DB::get("settings", ["id" => 1]);
$screen = DB::get("screens", ["id" => intval($_GET["id"])]);

if(@$_GET["preview"]){
    $screen = DB::get("screens_previews", ["id" => intval($_GET["id"])]) ?: $screen;
}

$content = [];
foreach($screen["variables_groups"] as $group){
    $all_allowed = @DB::get("variables_groups", ["id" => intval($group)])["variables"] ?: [];
    $_content = DB::get("variables_values", ["id" => intval($group)]) ?: [];
    $_content = @$_content["values"] ?: [];
    foreach($_content as $key => $value){
        foreach($all_allowed as $allowed){
            if($allowed["var_name"] === $key){
                $content[$key] = $value;
                break;
            }
        }
    }
}

function array_carrousel($array, $delay = 5, $padding = 4){
    //Make sure we always display 4 lines
    while(count($array) === 0 || count($array) % $padding != 0) $array[] = [];

    //Compute current page and loop every $delay seconds
    $page = 0;
    $pages = count($array) / $padding;
    $page = ceil(intval(date("U")) / $delay) % $pages;

    return array_slice($array, $page * $padding, $padding);
}

?>
<html>
    <head>
        <?php require(__DIR__."/pages/common/screen_head.php") ?>
        <style>
            <?= @$settings["style"] ?>
        </style>
        <style>
            <?= @$screen["style"] ?>
        </style>
    </head>
    <body>
        <div id="content">
            <?php
                eval("?>".$screen["content"]);
            ?>
        </div>
    </body>
</html>

<script type="text/javascript">
    const loadedAt = Date.now();
    let initial = document.getElementById("content").innerHTML;
    async function checkChanges() {
        try{
            const _updated = await (await fetch(document.location)).text();
            var parser = new DOMParser();
            var htmlDoc = parser.parseFromString(_updated, 'text/xml');
            const updated = htmlDoc.getElementById("content").innerHTML;
            if(initial !== updated){
                document.getElementById("content").innerHTML = updated;
                initial = updated;

                if(Date.now() - loadedAt > 1000 * 60){
                    document.location.reload();
                }
            }
        }catch(err){
            console.log(err);
        }finally{
            setTimeout(() => {
                checkChanges();
            }, 2000);
        }
    }
    checkChanges();
</script>