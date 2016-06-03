<?php

/* * * * * * * * * *
 *
 * Pagination Plugin by Cownnect Developers
 *
 * GitHub : https://github.com/Cownnect/
 * * * * * * * * * */

$q = $db->query('SELECT id FROM ' . $table);

//Total lines
$nbr_total = $q->rowCount();

//Number of lines per page
$nbr_pages = $number;

$nbr_aa_pages = 4;

//Last page
$last_page = ceil($nbr_total / $nbr_pages);

//Current page
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $num_page = $_GET['page'];
} else {
    $num_page = 1;
}

if ($num_page < 1) {
    $num_page = 1;
} elseif ($num_page > $last_page) {
    $num_page = $last_page;
}

//Limit definition
$limit = "LIMIT " . ($num_page - 1) * $nbr_pages . "," . $nbr_pages;

$pagination = "";

if ($last_page != 1) {

    //IF page number upper than 1
    if ($num_page > 1) {

        //Adding Fast Backward Button
        $pagination .= '<li><a href="?page=1"><i class="glyphicon glyphicon-fast-backward"></i></a></li>';

        //Previous page
        $previous = $num_page - 1;

        //Adding Previous Button
        $pagination .= '<li><a href="?page=' . $previous . '"><i class="glyphicon glyphicon-chevron-left"></i></a></li>';

        //Previous pages from current page
        for ($i = $num_page - $nbr_aa_pages; $i < $num_page; $i++) {
            if ($i > 0) {
                //Adding Previous Page Button
                $pagination .= '<li><a href="?page=' . $i . '">' . $i . '</a></li>';
            }
        }
    }

    //Adding Current Page Button
    $pagination .= '<li class="disabled" style="font-weight: bold;"><a>' . $num_page . '</a></li>';

    //Next pages from current page
    for ($i = $num_page + 1; $i <= $last_page; $i++) {

        //Adding Next Page Button
        $pagination .= '<li><a href="?page=' . $i . '">' . $i . '</a></li>';

        if ($i >= $num_page + $nbr_aa_pages) {
            break;
        }
    }

    if ($num_page != $last_page) {
        //Next page
        $next = $num_page + 1;

        //Adding Next Button
        $pagination .= '<li class="next"><a href="?page=' . $next . '"><i class="glyphicon glyphicon-chevron-right"></i></a></li>';

        //Adding Fast Forward Button
        $pagination .= '<li><a href="?page=' . $last_page . '"><i class="glyphicon glyphicon-fast-forward"></i></a></li>';
    }


}
