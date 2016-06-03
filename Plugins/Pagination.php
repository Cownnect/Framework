<?php

/* * * * * * * * * *
 *
 * Pagination Plugin by Cownnect Developers
 *
 * GitHub : https://github.com/Cownnect/
 * * * * * * * * * */

$q = $db->query('SELECT id FROM ' . $table);

//Récupération du nombre total de lignes
$nbr_total = $q->rowCount();

//Récupération du nombre de lignes par pages
$nbr_pages = $number;

//Nombres de pages avant et après la page courante
$nbr_aa_pages = 4;

//Récupération de la dernière page
$last_page = ceil($nbr_total / $nbr_pages);

//Récupération de la page courante
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $num_page = $_GET['page'];
} else {
    $num_page = 1;
}

//Évitement de page supérieur à la dernière et inférieur à la première
if ($num_page < 1) {
    $num_page = 1;
} elseif ($num_page > $last_page) {
    $num_page = $last_page;
}

//Définition de la limit
$limit = "LIMIT " . ($num_page - 1) * $nbr_pages . "," . $nbr_pages;

$pagination = "";

//Création de la pagination si il y a plus que 1 page
if ($last_page != 1) {

    //Si le numero de page est superieur à 1
    if ($num_page > 1) {

        //Alors ajout du bouton de retour à la première page
        $pagination .= '<li><a href="?page=1"><i class="glyphicon glyphicon-fast-backward"></i></a></li>';

        //Calcule de la page précédente
        $previous = $num_page - 1;

        //Ajout du bouton de retour à la page précédente
        $pagination .= '<li><a href="?page=' . $previous . '"><i class="glyphicon glyphicon-chevron-left"></i></a></li>';

        //Ajout des pages précédent la page courante
        for ($i = $num_page - $nbr_aa_pages; $i < $num_page; $i++) {
            if ($i > 0) {
                $pagination .= '<li><a href="?page=' . $i . '">' . $i . '</a></li>';
            }
        }
    }

    //Ajout de la page courante
    $pagination .= '<li class="disabled" style="font-weight: bold;"><a>' . $num_page . '</a></li>';

    //Ajout des pages suivant la page courante
    for ($i = $num_page + 1; $i <= $last_page; $i++) {
        $pagination .= '<li><a href="?page=' . $i . '">' . $i . '</a></li>';

        //Arret de l'ajout des pages
        if ($i >= $num_page + $nbr_aa_pages) {
            break;
        }
    }

    //Si la page courante n'est pas la dernière
    if ($num_page != $last_page) {
        //Calcule de la page suivante
        $next = $num_page + 1;

        //Ajout du bouton permettant d'aller à la page suivante
        $pagination .= '<li class="next"><a href="?page=' . $next . '"><i class="glyphicon glyphicon-chevron-right"></i></a></li>';

        //Ajout du bouton permettant d'aller à la dernière page
        $pagination .= '<li><a href="?page=' . $last_page . '"><i class="glyphicon glyphicon-fast-forward"></i></a></li>';
    }


}
