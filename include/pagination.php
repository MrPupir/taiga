<?php

class Pagination
{
    private $limit = 10;
    private $page = 1;
    private $rowsCount = 0;
    private $pageCount = 0;
    private $limitPages = 5;

    public function getRowsCount()
    {
        return $this->rowsCount;
    }
    public function getPageCount()
    {
        return $this->pageCount;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getFirst()
    {
        return ($this->page - 1) * $this->limit;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setLimit($limit)
    {
        if ($limit && is_numeric($limit) && $limit <= 30) {
            $this->limit = $limit;
            $this->calcPages();
        }
    }

    public function setPage($page)
    {
        if ($page && is_numeric($page) && $page > 0) {
            $this->page = $page;
            $this->calcPages();
        }
    }

    public function setRowsCount($rows)
    {
        $this->rowsCount = $rows;
        $this->calcPages();
    }

    private function calcPages()
    {
        $this->pageCount = 0;
        if ($this->rowsCount > 0) {
            $this->pageCount = (int) (($this->rowsCount - 1) / $this->limit) + 1;
        }
        if ($this->pageCount == 0) {
            $this->page = 1;
        } else if ($this->page > $this->pageCount) {
            $this->page = $this->pageCount;
        }
    }
}
