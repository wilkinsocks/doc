<?php

namespace Wilkinsocks\Doc\Entities;

class Row
{
    protected $data;

    public function __construct(Array $data)
    {
        $this->data = $data;
    }

    public function get($key)
    {
        if ($this->has($key))
        {
            return $this->data[$key];
        }

        return null;
    }

    public function has($key)
    {
        if (isset($this->data[$key]) && $this->data[$key])
        {
            return true;
        }

        return false;
    }

    public function missing($key)
    {
        if ($this->has($key))
        {
            return false;
        }

        return true;
    }

    public function explode($key, $delimiter = '|', $clean = true)
    {
        if ($this->has($key))
        {
            $value = $this->get($key);

            if ($clean)
            {
                $value = trim($value);
            }

            $data = explode($delimiter, $value);

            if ($clean)
            {
                $cleanedData = [];

                foreach ($data as $item)
                {
                    if (!empty($item))
                    {
                        $cleanedData[] = trim($item);
                    }
                }

                return $cleanedData;
            }

            return $data;
        }

        return null;
    }

    public function transformToBoolean($key, $valueIsTrue = false)
    {
        if ($this->has($key))
        {
            $value = strtolower(trim($this->get($key)));

            if ($valueIsTrue)
            {
                if ($value)
                {
                    return true;
                }

                return false;
            }

            $trueStatements = ['true', '1', 'yes', 'y'];
            $falseStatements = ['false', '0', 'no', 'n'];

            if (in_array($value, $trueStatements))
            {
                return true;
            }

            if (in_array($value, $falseStatements))
            {
                return false;
            }
        }

        return null;
    }

    public static function isValid($data)
    {
        if (is_array($data) && count($data) > 1)
        {
            return true;
        }

        return false;
    }

    public static function trimValues(Array $rowData)
    {
        $cleanedData = [];

        foreach ($rowData as $item)
        {
            $cleanedData[] = trim($item);
        }

        return $cleanedData;
    }

    public static function nullValues(Array $rowData, Array $strings)
    {
        $cleanedData = [];

        foreach ($rowData as $item)
        {
            if (in_array($item, $strings))
            {
                $cleanedData[] = null;
            }
            else
            {
                $cleanedData[] = $item;
            }
        }

        return $cleanedData;
    }
}
