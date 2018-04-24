<?php

namespace Wilkinsocks\Doc;

use Wilkinsocks\Doc\File;
use Wilkinsocks\Doc\Entities\{Rows, Row};
use SplFileObject;

class Csv
{
    protected $path;
    protected $file;
    protected $data;
    protected $rows;
    protected $header = false;
    protected $delimiter = ",";
    protected $enclosure = "\"";
    protected $escape = "\\";
    protected $valueTrimming = false;
    protected $nullableStrings = [];

    public function __construct(String $path)
    {
        $this->path = $path;

        $this->file = new File($path);
        $this->rows = new Rows;
    }

    public function setHeaders($headers)
    {
        if (!$this->hasHeaderRow())
        {
            $this->setHasHeaderRow();
        }

        $this->rows
            ->setHeaders($headers);
    }

    public function setDelimiterCharacter(String $delimiter)
    {
        $this->delimiter = $delimiter;
    }

    public function getDelimiterCharacter()
    {
        return $this->delimiter;
    }

    public function setEnclosureCharacter(String $enclosure)
    {
        $this->enclosure = $enclosure;
    }

    public function getEnclosureCharacter()
    {
        return $this->enclosure;
    }

    public function setEscapeCharacter(String $delimiter)
    {
        $this->delimiter = $delimiter;
    }

    public function getEscapeCharacter()
    {
        return $this->delimiter;
    }

    public function firstRowIsHeader()
    {
        $this->setHasHeaderRow();

        return $this;
    }

    public function setValueTrimming(Bool $setting)
    {
        $this->valueTrimming = $setting;

        return $this;
    }

    public function castAsNull(Array $strings)
    {
        $this->nullableStrings = $strings;

        return $this;
    }

    protected function setHasHeaderRow()
    {
        $this->header = true;
    }

    public function hasHeaderRow()
    {
        if ($this->header)
        {
            return true;
        }

        return false;
    }

    public function hasHeader($header)
    {
        if ($this->hasHeaderRow() && in_array($header, $this->rows->headers))
        {
            return true;
        }

        return false;
    }

    public function read()
    {
        if ($this->file instanceof File)
        {
            $i = 0;
            $file = $this->file
                ->getFile();

            while (!$file->eof())
            {
                $lineData = $file->fgetcsv(
                        $this->delimiter,
                        $this->enclosure,
                        $this->escape
                    );

                if (($i == 0) && $this->hasHeaderRow())
                {
                    $this->setHeaders($lineData);

                    $i++; continue;
                }

                if(Row::isValid($lineData))
                {
                    $rowData = $lineData;

                    if ($this->valueTrimming)
                    {
                        $rowData = Row::trimValues($rowData);
                    }

                    if ($this->nullableStrings)
                    {
                        $rowData = Row::nullValues($rowData, $this->nullableStrings);
                    }

                    $this->appendRow($rowData);
                }

                $i++;
            }

        }
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function appendRow($data)
    {
        $this->rows
            ->appendRow($data);
    }
}
