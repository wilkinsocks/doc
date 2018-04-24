<?php

namespace Wilkinsocks\Doc\Entities;

use Iterator;

class Rows implements Iterator
{
    private $position = 0;
    private $array = [];
    public $headers = [];

    public function __construct()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->array[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function valid()
    {
        return isset($this->array[$this->position]);
    }

    public function count()
    {
        return count($this->array);
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function appendRow($data)
    {
        if ($this->headers)
        {
            $rowData = array_combine($this->headers, $data);
        }
        else
        {
            $rowData = $data;
        }

        $this->array[] = new Row($rowData);
    }
}
