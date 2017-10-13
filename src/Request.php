<?php
/**
 * Created by PhpStorm.
 * User: hysterias
 * Date: 12/10/17
 * Time: 18:30
 */

namespace FJA;


class Request
{
    public $user;

    public $arguments;


    /**
     * Request constructor.
     * @param $user
     * @param $arguments
     */
    public function __construct($user, $arguments)
    {
        $this->user = $user;
        $this->arguments = unserialize($arguments);
    }


    public function snippetsLite($affichExtend)
    {
        $returnDiv = "";
        $token = '3c1cd93b123d714fc732e9ad11999cd2c3ac815d'; // Banban
        $arrayFinal = "";

        $url = "https://api.github.com/users/$this->user";
        $user = curl_init();
        curl_setopt($user, CURLOPT_URL, $url);
        curl_setopt($user, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($user, CURLOPT_HEADER, 0);
        curl_setopt($user, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; fr; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13');
        curl_setopt($user, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer $token"));
        $dataUser = curl_exec($user);
        curl_close($user);
        $arrayUser = json_decode($dataUser);
        foreach ($arrayUser as $key => $value) {
            if (in_array($key, $this->arguments['user'])) {
                $arrayFinal['user'][$key] = $value;
            }
        }

        $repos = curl_init();
        curl_setopt($repos, CURLOPT_URL, "$arrayUser->repos_url");
        curl_setopt($repos, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($repos, CURLOPT_HEADER, 0);
        curl_setopt($repos, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; fr; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13');
        curl_setopt($repos, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer $token"));
        $dataRepos = curl_exec($repos);
        curl_close($repos);
        $arrayRepos = json_decode($dataRepos);



        $linkGists = preg_replace("/(\{.*?\})/", "", $arrayUser->gists_url);
        $gists = curl_init();
        curl_setopt($gists, CURLOPT_URL, "$linkGists");
        curl_setopt($gists, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($gists, CURLOPT_HEADER, 0);
        curl_setopt($gists, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; fr; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13');
        curl_setopt($gists, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer $token"));
        $dataGists = curl_exec($gists);
        curl_close($gists);
        $arrayGists = json_decode($dataGists);


        $limitRepos = $this->arguments['repos']['limit'];


        $limiterRepos = explode("-", $limitRepos);
        $nbRepos = $arrayUser->public_repos - 1;
        if ($limiterRepos[0] == "D") {
            for ($i = 0; $i < $limiterRepos[1]; $i++) {
                $arrayFinal['repos'][$i] = $arrayRepos[$i];
            }
        }
        if ($limiterRepos[0] == "F") {
            for ($i = $nbRepos; $i > ($nbRepos - $limiterRepos[1]); $i--) {
                $arrayFinal['repos'][$i] = $arrayRepos[$i];
            }

        }
        $limitGists = $this->arguments['gists']['limit'];
        $limiterGists = explode("-", $limitGists);
        $nbGists = $arrayUser->public_repos - 1;
        if ($limiterGists[0] == "D") {
            for ($i = 0; $i < $limiterGists[1]; $i++) {
                $arrayFinal['gists'][$i] = $arrayGists[$i];
            }
        }
        if ($limiterGists[0] == "F") {
            for ($i = $nbGists; $i > ($nbGists - $limiterGists[1]); $i--) {
                $arrayFinal['gists'][$i] = $arrayGists[$i];
            }
        }


        $returnDiv .= "<div class=\"app z-depth-4\">" . PHP_EOL;
        $returnDiv .= "<div class=\"appHeader\">" . PHP_EOL;
        if (in_array("avatar_url", $this->arguments['user'])) {
            $returnDiv .= "<img src=\"" . $arrayFinal['user']['avatar_url'] . "\" alt=\"imgProfil\" class=\"circle\" width=\"120px\" height=\"120px\">" . PHP_EOL;
        }
        $returnDiv .= "<div class=\"infos\">" . PHP_EOL;
        if (in_array("login", $this->arguments['user'])) {
            $returnDiv .= "<span class=\"userName\">@" . $arrayFinal['user']['login'] . "</span>" . PHP_EOL;
        }
        $returnDiv .= "<div class=\"appFollow\">" . PHP_EOL;
        if (in_array("followers", $this->arguments['user'])) {
            $returnDiv .= "<span class=\"followers chip amber white-text\">Followers : " . $arrayFinal['user']['followers'] . "</span>" . PHP_EOL;
        }
        $returnDiv .= "<br>" . PHP_EOL;
        if (in_array("following", $this->arguments['user'])) {
            $returnDiv .= "<span class=\"following chip amber white-text\">Following : " . $arrayFinal['user']['following'] . "</span>" . PHP_EOL;
        }
        $returnDiv .= "</div></div></div>" . PHP_EOL;
        $returnDiv .= "<div class=\"divider\"></div>" . PHP_EOL;
        $returnDiv .= "<div class=\"appRepos\">" . PHP_EOL;
        $returnDiv .= "<div class=\"countCreate\">" . PHP_EOL;
        if (in_array("public_repos", $this->arguments['user'])) {
            $returnDiv .= "<p class=\"countRepos chip blue white-text\">Depots : " . $arrayFinal['user']['public_repos'] . "</p>" . PHP_EOL;
        }
        if (in_array("public_gists", $this->arguments['user'])) {
            $returnDiv .= "<p class=\"countGists chip green white-text\">Gists : " . $arrayFinal['user']['public_gists'] . "</p>" . PHP_EOL;
        }
        $returnDiv .= "</div>" . PHP_EOL;
        $returnDiv .= "<div class=\"divider\"></div>" . PHP_EOL;
        $returnDiv .= "<div class=\"deposApp\">" . PHP_EOL;
        if (in_array("show", $this->arguments['repos'])) {
            $returnDiv .= "<span>Les derniers depos :</span>" . PHP_EOL;
            $returnDiv .= "<ul>" . PHP_EOL;
            foreach ($arrayFinal['repos'] as $key => $arrayOneRepos) {
                $returnDiv .= "<li><i class=\"material-icons blue-text\">folder</i> " . $arrayOneRepos->name . "</li>" . PHP_EOL;
            }
            $returnDiv .= "</ul>" . PHP_EOL;
        }
        $returnDiv .= "</div>" . PHP_EOL;
        $returnDiv .= "</div>" . PHP_EOL;

        if ($affichExtend == TRUE) {
            $returnDiv .= "<div class=\"appFooter center\">" . PHP_EOL;
            $returnDiv .= "<a class=\"waves-effect waves-light btn modal-trigger amber white-text\" href=\"#modal1\">Click here for more details</a>" . PHP_EOL;
            $returnDiv .= "</div>" . PHP_EOL;
        }



        $returnDiv .= "</div>" . PHP_EOL;

        //$returnDiv .= "";
        return $returnDiv;
    }

    public function snippetsFat()
    {
        $returnDiv = "";
        $token = '3c1cd93b123d714fc732e9ad11999cd2c3ac815d'; // Banban


        $url = "https://api.github.com/users/$this->user";
        $user = curl_init();
        curl_setopt($user, CURLOPT_URL, $url);
        curl_setopt($user, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($user, CURLOPT_HEADER, 0);
        curl_setopt($user, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; fr; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13');
        curl_setopt($user, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer $token"));
        $dataUser = curl_exec($user);
        curl_close($user);
        $arrayUser = json_decode($dataUser);
        foreach ($arrayUser as $key => $value) {
            if (in_array($key, $this->arguments['user'])) {
                $arrayFinal['user'][$key] = $value;
            }
        }

        $repos = curl_init();

        curl_setopt($repos, CURLOPT_URL, "$arrayUser->repos_url");
        curl_setopt($repos, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($repos, CURLOPT_HEADER, 0);
        curl_setopt($repos, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; fr; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13');
        curl_setopt($repos, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer $token"));
        $dataRepos = curl_exec($repos);
        curl_close($repos);
        $arrayRepos = json_decode($dataRepos);



        $linkGists = preg_replace("/(\{.*?\})/", "", $arrayUser->gists_url);
        $gists = curl_init();
        curl_setopt($gists, CURLOPT_URL, "$linkGists");
        curl_setopt($gists, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($gists, CURLOPT_HEADER, 0);
        curl_setopt($gists, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; fr; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13');
        curl_setopt($gists, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer $token"));
        $dataGists = curl_exec($gists);
        curl_close($gists);
        $arrayGists = json_decode($dataGists);
        $limitRepos = $this->arguments['repos']['limit'];

        $limiterRepos = explode("-", $limitRepos);
        $nbRepos = $arrayUser->public_repos - 1;
        if ($limiterRepos[0] == "D") {
            for ($i = 0; $i < $limiterRepos[1]; $i++) {
                $arrayFinal['repos'][$i] = $arrayRepos[$i];
            }
        }
        if ($limiterRepos[0] == "F") {
            for ($i = $nbRepos; $i > ($nbRepos - $limiterRepos[1]); $i--) {
                $arrayFinal['repos'][$i] = $arrayRepos[$i];
            }

        }
        $limitGists = $this->arguments['gists']['limit'];
        $limiterGists = explode("-", $limitGists);
        $nbGists = $arrayUser->public_repos - 1;
        if ($limiterGists[0] == "D") {
            for ($i = 0; $i < $limiterGists[1]; $i++) {
                $arrayFinal['gists'][$i] = $arrayGists[$i];
            }
        }
        if ($limiterGists[0] == "F") {
            for ($i = $nbGists; $i > ($nbGists - $limiterGists[1]); $i--) {
                $arrayFinal['gists'][$i] = $arrayGists[$i];
            }
        }


        $returnDiv .= "<div id=\"modal1\" class=\"modal bottom-sheet\">" . PHP_EOL;
        $returnDiv .= "<div class=\"modal-header\">" . PHP_EOL;
       // var_dump($arrayFinal);
        $returnDiv .= "<h4>Détails du compte github de @" . $arrayFinal['user']['login'] . "</h4>" . PHP_EOL;
        $returnDiv .= "<a href=\"#!\" class=\"modal-action modal-close waves-effect waves-green btn-flat\"><i class=\"material-icons\">close</i></a>" . PHP_EOL;
        $returnDiv .= "</div>" . PHP_EOL;
        $returnDiv .= "<div class=\"modal-content\">" . PHP_EOL;
        $returnDiv .= "<ul id=\"tabs-swipe-demo\" class=\"tabs tabs-fixed-width\">" . PHP_EOL;
        $returnDiv .= "<li class=\"tab\"><a href=\"#test-swipe-1\">repos</a></li>" . PHP_EOL;
        $returnDiv .= "<li class=\"tab\"><a href=\"#test-swipe-2\">gists</a></li>" . PHP_EOL;
        $returnDiv .= "</ul>" . PHP_EOL;

        $returnDiv .= "<div id=\"test-swipe-1\" class=\"col s12 slideDetails\">" . PHP_EOL;
        $returnDiv .= "<ul class=\"collapsible popout\" data-collapsible=\"accordion\">" . PHP_EOL;

        foreach ($arrayRepos as $key => $arrayOneRepos) {

            $returnDiv .= "<li>";
            $returnDiv .= "<div class=\"collapsible-header hoverable blue white-text\">";
            $returnDiv .= "<div>";
            $returnDiv .= "<h5><i class=\"material-icons\">folder</i>" . $arrayOneRepos->name . "</h5>" . PHP_EOL;
            $returnDiv .= "</div>" . PHP_EOL;

            $returnDiv .= "<span class=\"lastCommit\">Last updated : " . $arrayOneRepos->pushed_at . "</span>" . PHP_EOL;
            $returnDiv .= "</div>" . PHP_EOL;
            $returnDiv .= "<div class=\"collapsible-body\">" . PHP_EOL;
            $returnDiv .= "<div class=\"center\">" . PHP_EOL;
            $returnDiv .= "Lien du dépôt : <a href='" . $arrayOneRepos->html_url . "' target='_blank'>" . $arrayOneRepos->html_url . "</a>" . PHP_EOL;
            $returnDiv .= "</div>" . PHP_EOL;
            $returnDiv .= "</div>" . PHP_EOL;
            $returnDiv .= "<div class=\"collapsible-footer\">" . PHP_EOL;

            if ($arrayOneRepos->language !== NULL) {
                $returnDiv .= "<div class=\"chip red white-text\">" . PHP_EOL;
                $returnDiv .= $arrayOneRepos->language . PHP_EOL;
                $returnDiv .= "</div>" . PHP_EOL;
            }


            $returnDiv .= "<span>" . $arrayOneRepos->forks . " Forks</span>" . PHP_EOL;
            $returnDiv .= "</div>" . PHP_EOL;
            $returnDiv .= "</li>" . PHP_EOL;
        }

        $returnDiv .= "</ul>" . PHP_EOL;
        $returnDiv .= "</div>" . PHP_EOL;


        $returnDiv .= "<div id=\"test-swipe-2\" class=\"col s12 slideDetails\">" . PHP_EOL;
        $returnDiv .= "<ul class=\"collapsible popout\" data-collapsible=\"accordion\">" . PHP_EOL;

        foreach ($arrayGists as $key => $arrayOneGists) {

            $returnDiv .= "<li>" . PHP_EOL;
            $returnDiv .= "<div class=\"collapsible-header hoverable green white-text\">" . PHP_EOL;
            $returnDiv .= "<div>" . PHP_EOL;
            //var_dump($arrayOneGists);
            foreach ($arrayOneGists->files as $key => $arrayInfoGist) {
                $name = $key;
                $language = $arrayInfoGist->language;
            }


            $returnDiv .= "<h5><i class=\"material-icons\">description</i>" . $name . "</h5>" . PHP_EOL;
            $returnDiv .= "</div>" . PHP_EOL;

            $returnDiv .= "<span class=\"lastCommit\">Last updated : " . $arrayOneGists->created_at . "</span>" . PHP_EOL;
            $returnDiv .= "</div>" . PHP_EOL;
            $returnDiv .= "<div class=\"collapsible-body\">" . PHP_EOL;
            $returnDiv .= "<div class=\"center\">" . PHP_EOL;
            $returnDiv .= "Lien du gist : <a href='" . $arrayOneGists->html_url . "' target='_blank'>" . $arrayOneGists->html_url . "</a>" . PHP_EOL;
            $returnDiv .= "</div>" . PHP_EOL;
            $returnDiv .= "</div>" . PHP_EOL;
            $returnDiv .= "<div class=\"collapsible-footer\">" . PHP_EOL;

            if ($language !== NULL) {
                $returnDiv .= "<div class=\"chip red white-text\">" . PHP_EOL;
                $returnDiv .= $language . PHP_EOL;
                $returnDiv .= "</div>" . PHP_EOL;
            }


            $returnDiv .= "<span>" . $arrayOneGists->forks . "</span>" . PHP_EOL;
            $returnDiv .= "</div>" . PHP_EOL;
            $returnDiv .= "</li>" . PHP_EOL;
        }


        $returnDiv .= "</ul>" . PHP_EOL;

        $returnDiv .= "</div>" . PHP_EOL;
        $returnDiv .= "</div>" . PHP_EOL;
        $returnDiv .= "</div>" . PHP_EOL;
        //$returnDiv .= "";

        return $returnDiv;
    }
}







