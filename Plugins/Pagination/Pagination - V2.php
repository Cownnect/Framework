<?php

/*
 * Comments Plugin by Cownnect Developpers
 *
 * Copyright (c) 2015 Cownnect

    MIT license

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
    THE SOFTWARE.
 */

/* GITHUB : https://github.com/Cownnect/ */

namespace Cownnect\Framework\Plugins;


class Pagination
{

    private $pdo;
    private $row_per_page;
    private $db_table;

    private $pagination_html;
    private $limit;

    /**
     * Pagination constructor.
     * @param $pdo Database PDO Connection
     * @param $row_per_page Rows per page
     * @param $db_table Database table
     */
    public function __construct($pdo, $row_per_page, $db_table)
    {
        $this->pdo = $pdo;
        $this->row_per_page = $row_per_page;
        $this->db_table = $db_table;
    }

    /**
     * Initiliaze Pagination
     */
    public function Paginate()
    {
        //Row count from table
        $table_count = $this->pdo->query("SELECT COUNT(id) FROM " . $this->db_table)->fetchColumn();

        $npba = 4;

        //Last page
        $last_page = ceil($table_count / $this->row_per_page);

        //Current page
        if (isset($_GET['page']) && is_numeric($_GET['page']))
        {
            $current_page = $_GET['page'];
        }
        else
        {
            $current_page = 1;
        }

        if ($current_page < 1)
        {
            $current_page = 1;
        }
        elseif ($current_page > $last_page)
        {
            $current_page = $last_page;
        }

        //SQL Limit definition
        $this->limit = "LIMIT " . ($current_page - 1) * $this->row_per_page . "," . $this->row_per_page;

        if ($last_page != 1) {

            //IF page number upper than 1
            if ($current_page > 1) {

                //Adding Fast Backward Button
                $this->pagination_html .= '<li><a href="?page=1"><i class="glyphicon glyphicon-fast-backward"></i></a></li>';

                //Previous page
                $previous = $current_page - 1;

                //Adding Previous Button
                $this->pagination_html .= '<li><a href="?page=' . $previous . '"><i class="glyphicon glyphicon-chevron-left"></i></a></li>';

                //Previous pages from current page
                for ($i = $current_page - $npba; $i < $current_page; $i++) {
                    if ($i > 0) {
                        //Adding Previous Page Button
                        $this->pagination_html .= '<li><a href="?page=' . $i . '">' . $i . '</a></li>';
                    }
                }
            }

            //Adding Current Page Button
            $this->pagination_html .= '<li class="disabled" style="font-weight: bold;"><a>' . $current_page . '</a></li>';

            //Next pages from current page
            for ($i = $current_page + 1; $i <= $last_page; $i++) {

                //Adding Next Page Button
                $this->pagination_html .= '<li><a href="?page=' . $i . '">' . $i . '</a></li>';

                if ($i >= $current_page + $npba) {
                    break;
                }
            }

            if ($current_page != $last_page) {
                //Next page
                $next = $current_page + 1;

                //Adding Next Button
                $this->pagination_html .= '<li class="next"><a href="?page=' . $next . '"><i class="glyphicon glyphicon-chevron-right"></i></a></li>';

                //Adding Fast Forward Button
                $this->pagination_html .= '<li><a href="?page=' . $last_page . '"><i class="glyphicon glyphicon-fast-forward"></i></a></li>';
            }


        }
    }

    /**
     * @return mixed SQL Limit
     */
    public function Limit()
    {
        return $this->limit;
    }

    /**
     * @return mixed HTML pagination
     */
    public function View()
    {
        return $this->pagination_html;
    }
}
