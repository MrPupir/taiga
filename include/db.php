<?php

class DB
{
    private $options = [];
    private $con = null;
    public function __construct($options)
    {
        $this->options = $options;
        $this->connect();
    }

    public function find($from)
    {
        return new QueryBuilder($this, $from);
    }

    public function __destruct()
    {
        $this->close();
    }

    public function isConnect()
    {
        return ($this->con != null);
    }

    public function close()
    {
        if ($this->con) {
            mysqli_close($this->con);
        }
    }

    public function error()
    {
        return mysqli_error($this->con);
    }

    public function connect()
    {
        $this->con = mysqli_connect($this->options['host'], $this->options['user'], $this->options['password'], $this->options['db']);
        return $this->isConnect();
    }

    public function query($sql)
    {
        if (!$this->isConnect()) {
            return false;
        }

        $r = mysqli_query($this->con, $sql);
        if (!$r) {
            return false;
        }

        $rows = [];
        while ($row = mysqli_fetch_assoc($r)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function queryOne($sql)
    {
        if (!$this->isConnect()) {
            return false;
        }

        $r = mysqli_query($this->con, $sql);
        if (!$r) {
            return false;
        }

        return mysqli_fetch_assoc($r);
    }

    public function insert($table, $ar)
    {
        $fields = '';
        $values = '';
        $i = 0;
        foreach ($ar as $k => $v) {
            if ($i > 0) {
                $fields .= ',';
                $values .= ',';
            }
            $fields .= '`' . $k . "`";
            $values .= "'" . $v . "'";
            $i++;
        }
        $sql = "INSERT INTO `" . $table . "` ( " . $fields . ") VALUES(" . $values . ")";
        if (!$this->isConnect()) {
            return false;
        }

        $r = mysqli_query($this->con, $sql);
        if (!$r) {
            return false;
        }

        return mysqli_insert_id($this->con);
    }

    public function update($table, $id, $data)
    {
        $set = '';
        foreach ($data as $key => $value) {
            $set .= "`$key` = '" . mysqli_real_escape_string($this->con, $value) . "', ";
        }
        $set = rtrim($set, ', ');
        $sql = "UPDATE `$table` SET $set WHERE `id` = " . (int) $id;
        return mysqli_query($this->con, $sql);
    }

    public function delete($table, $condition)
    {
        $sql = "DELETE FROM `$table` WHERE $condition";
        return mysqli_query($this->con, $sql);
    }
}
