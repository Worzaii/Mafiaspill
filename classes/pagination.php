<?php

class Pagination
{
    var $sqlText, $sqlResult, $arrayResult, $num_rows, $per_page, $current_page, $get_var, $adjacents;

    function __construct($db, $sqlText, $per_page, $get_var, $adjacents = 1)
    {
        // Set get variable and current page
        $this->get_var      = $get_var;
        $this->current_page = (isset($_GET[$get_var]) ? $_GET[$get_var] : 0);

        // Set SQL query and get results
        $this->sqlText   = $sqlText;
        $this->sqlResult = $db->query($this->sqlText) or die('Kunne ikke lage sidetall '.mysqli_error($db->connection_id).' <b>Vennligst rapporter til administrator.</b>');
        $this->num_rows  = mysqli_num_rows($this->sqlResult);

        // Save results in array
        $arrayResult = array();
        while ($res         = mysqli_fetch_array($this->sqlResult)) {
            $arrayResult[] = $res;
        }
        $this->arrayResult = $arrayResult;

        // Higher than max
        $this->current_page = $this->current_page > ceil($this->num_rows / $per_page) ? ceil($this->num_rows / $per_page)
                : $this->current_page;

        // Other inputs
        $this->per_page  = $per_page;
        $this->adjacents = $adjacents;
    }

    function GetNumPages($add = 0)
    {
        // return max number of pages (last page number)
        return ceil(($this->num_rows + $add) / $this->per_page);
    }

    function GetSQLRows($all = 'limit')
    {
        if ($all != 'limit') {
            return $this->arrayResult;
        } else {
            return array_slice($this->arrayResult, ($this->current_page - 1) * $this->per_page, $this->per_page);
        }
    }

    function GetLinkHref($pagenum)
    {
        $queryString = $_SERVER['QUERY_STRING'];

        $pattern     = array('/'.$this->get_var.'=[^&]*&?/', '/&$/');
        $replace     = array('', '');
        $queryString = preg_replace($pattern, $replace, $queryString);
        $queryString = str_replace('&', '&amp;', $queryString);

        if (!empty($queryString)) {
            $queryString .= '&amp;';
        }

        return "https://".DOMENE_NAVN.'?'.$queryString.$this->get_var.'='.$pagenum;
    }

    function GetSeperator()
    {
        return '<a href="#" onclick="var page = prompt(\'Skriv et sidetall. 1 til '.$this->GetNumPages().'.\'); if( page > 0 &amp;&amp; page &lt;= '.$this->GetNumPages().' ){ NavigateTo(\''.$this->GetLinkHref("'+page+'").'\'); } return false;" class="seperator">...</a>';
    }

    function GetPageLinks()
    {
        $begin    = $this->current_page < 0 ? 0 : $this->current_page * $this->per_page;
        $num_rows = $this->num_rows;
        $links    = '';

        $links .= '<div class="pagination">';

        if ($num_rows <= $this->per_page) {
            $links .= '<a href="'.$this->GetLinkHref(1).'" class="active">1</a> ';
        } else {
            $links .= $begin / $this->per_page <= 1 ? '<span class="prev">Forrige</span> ' : '<a href="'.$this->GetLinkHref($this->current_page
                    - 1).'" class="prev">Forrige</a> ';

            if ($this->GetNumPages() < 7 + ($this->adjacents * 2)) {
                for ($i = 1; $i <= $this->GetNumPages(); $i++) {
                    $class = $i == $this->current_page ? ' class="active"' : '';
                    $links .= '<a href="'.$this->GetLinkHref($i).'"'.$class.'>'.($i).'</a> ';
                }
            } elseif ($this->GetNumPages() > 5 + ($this->adjacents * 2)) {
                if ($this->current_page < 1 + ($this->adjacents * 2)) {
                    for ($i = 1; $i < 4 + ($this->adjacents * 2); $i++) {
                        $class = $i == $this->current_page ? ' class="active"' : '';
                        $links .= '<a href="'.$this->GetLinkHref($i).'"'.$class.'>'.($i).'</a> ';
                    }
                    $links .= $this->GetSeperator();
                    $links .= '<a href="'.$this->GetLinkHref($this->GetNumPages() - 1).'">'.($this->GetNumPages() - 1).'</a> '; // last page - 1
                    $links .= '<a href="'.$this->GetLinkHref($this->GetNumPages()).'">'.($this->GetNumPages()).'</a> '; // last page
                } elseif ($this->GetNumPages() - ($this->adjacents * 2) > $this->current_page && $this->current_page > ($this->adjacents
                    * 2)) {
                    $links .= '<a href="'.$this->GetLinkHref(1).'">1</a> '; // first page
                    $links .= '<a href="'.$this->GetLinkHref(2).'">2</a> '; // second page
                    $links .= $this->GetSeperator();
                    for ($i = $this->current_page - $this->adjacents; $i <= $this->current_page + $this->adjacents; $i++) {
                        $class = $i == $this->current_page ? ' class="active"' : '';
                        $links .= '<a href="'.$this->GetLinkHref($i).'"'.$class.'>'.($i).'</a> ';
                    }
                    $links .= $this->GetSeperator();
                    $links .= '<a href="'.$this->GetLinkHref($this->GetNumPages() - 1).'">'.($this->GetNumPages() - 1).'</a> '; // last page - 1
                    $links .= '<a href="'.$this->GetLinkHref($this->GetNumPages()).'">'.($this->GetNumPages()).'</a> '; // last page
                } else {
                    $links .= '<a href="'.$this->GetLinkHref(1).'">1</a> '; // first page
                    $links .= '<a href="'.$this->GetLinkHref(2).'">2</a> '; // second page
                    $links .= $this->GetSeperator();
                    for ($i = $this->GetNumPages() - (2 + ($this->adjacents * 2)); $i <= $this->GetNumPages(); $i++) {
                        $class = $i == $this->current_page ? ' class="active"' : '';
                        $links .= '<a href="'.$this->GetLinkHref($i).'"'.$class.'>'.($i).'</a> ';
                    }
                }
            }

            $links .= $this->current_page >= $this->GetNumPages() ? '<span class="next">Neste</span>' : '<a href="'.$this->GetLinkHref($this->current_page
                    + 1).'" class="next">Neste</a>';
        }

        $links .= '</div>';

        return $links;
    }
}