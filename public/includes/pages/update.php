<?php

if (isset($_POST)) {
    $arguments['user'] = $_POST['user'];
    $arguments['repos'] = ["limit" => "D-" . $_POST['range']];
    $arguments['gists'] = ["limit" => "D-3", "show"];
    array_push($arguments['repos'], $_POST['repos'][0]);
    $serveurs = explode("/", $_SERVER['HTTP_REFERER']);
    $page = array_pop($serveurs);
    $serveur = implode("/", $serveurs);
    $extention = (!empty($_POST['extends']) ? "&extends" : "");
    $returnDiv = "&#60;div&#62;&#60;object  style=\"width: 380px;height: 600px;\" data='" . $serveur[0] . "snippets.php?user=" . $_POST['userName'] . "&var=" . serialize($arguments) . $extention . "' type=\"text/html\"&#62;&#60;/object&#62; &#60;/div&#62;";
    unlink("cache.html");
    file_put_contents("cache.html", $returnDiv);
    chmod("cache.html", 0777);
}
?>
<!-- form for update infos -->
<div class="card z-depth-4">
    <div class="row">
        <div class="col s2">
            <?php
            $preview = file_get_contents("cache.html");
            $preview = str_replace('&#60;', '<', $preview);
            $preview = str_replace('&#62;', '>', $preview);
            echo $preview;
            ?>
        </div>
        <div class="col s10">
            <form class="container" action="#" method="post">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="userName" name="userName" type="text" class="validate" required/>
                        <label for="userName">@userName</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s4">
                        <p>
                            <input type="checkbox" id="displayUserName" checked="checked" name="user[]"
                                   value="login"/>
                            <label for="displayUserName">Afficher le userName : </label>
                        </p>
                        <p>
                            <input type="checkbox" id="displayPicsProfil" checked="checked" name="user[]"
                                   value="avatar_url"/>
                            <label for="displayPicsProfil">Afficher la photo de profil : </label>
                        </p>
                    </div>
                    <div class="col s4">
                        <p>
                            <input type="checkbox" id="displayFollowers" checked="checked" name="user[]"
                                   value="followers"/>
                            <label for="displayFollowers">Afficher le total de followers : </label>
                        </p>
                        <p>
                            <input type="checkbox" id="displayFollowings" checked="checked" name="user[]"
                                   value="following"/>
                            <label for="displayFollowings">Afficher le total de followers : </label>
                        </p>
                    </div>
                    <div class="col s4">
                        <p>
                            <input type="checkbox" id="displayRepos" checked="checked" name="user[]"
                                   value="public_repos"/>
                            <label for="displayRepos">Afficher les depôts : </label>
                        </p>
                        <p>
                            <input type="checkbox" id="displayGists" checked="checked" name="user[]"
                                   value="public_gists"/>
                            <label for="displayGists">Afficher les gists : </label>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s4">
                        <select name="repos[]">
                            <option value="" disabled selected>Afficher la liste des dépôts</option>
                            <option value="show" selected>Show</option>
                            <option value="hide">Hide</option>
                        </select>
                        <label for="displayList">Afficher la liste des dépôts</label>
                    </div>
                    <div class="range-field col s4">
                        <label for="range">Nombres de depôt : </label>
                        <input type="range" id="range" name="range" min="1" max="6"/>
                    </div>
                    <p class="col s4">
                        <input type="checkbox" id="extends" checked="checked" name="extends" value="true"/>
                        <label for="extends">Modal de détail : </label>
                    </p>
                </div>
                <div class="row">
                    <div class="input-field col s12">

                        <textarea id="to-copy" spellcheck="false" class="materialize-textarea"
                                  readonly><?php echo file_get_contents("cache.html"); ?></textarea>
                        <a id="copy" class="btn" title="Copied HTML code"><i
                                    class="large material-icons">content_copy</i></a>
                        <label for="to-copy">Code HTML à intégrer</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s3 offset-s9">
                        <button class="btn waves-effect waves-light" type="submit">Submit
                            <i class="material-icons right">send</i>
                        </button>
                    </div>
                </div>
            </form>


        </div>

    </div>


</div>

<!-- Go to update -->
<div class="fixed-action-btn">
    <a class="btn-floating btn-large waves-effect waves-light red" href="?page=home" title="home page">
        <i class="large material-icons">home</i>
    </a>
</div>


