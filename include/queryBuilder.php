<?php

class QueryBuilder
{
    private $db;
    protected $from = '';
    protected $select = [];
    protected $where = [];
    protected $order = [];
    protected $join = [];
    protected $offset = null;
    protected $limit = null;

    public function __construct($db, $from)
    {
        $this->from = $from;
        $this->db = $db;
    }

    public function offset($o)
    {
        $this->offset = $o; return $this;
    }

    public function limit($l)
    {
        $this->limit = $l; return $this;
    }

    public function leftJoin($t, $c)
    {
        $this->join[] = [
            'type' => ' LEFT JOIN ',
            'table' => $t,
            'condition' => $c,
        ];
        return $this;
    }

    public function select($fields)
    {
        if (is_array($fields)) {
            foreach ($fields as $v) {
                array_push($this->select, $v);
            }
        } else {
            array_push($this->select, $fields);
        }
        return $this;
    }

    public function orderBy($fields)
    {
        if (is_array($fields)) {
            foreach ($fields as $v) {
                array_push($this->order, $v);
            }
        } else {
            array_push($this->order, $fields);
        }
        return $this;
    }

    public function where($field, $condition, $value)
    {
        $condition = $this->convertCondition($condition, $value);
        array_push($this->where, [
            'field' => $field,
            'condition' => $condition[0],
            'value' => $condition[1],
        ]);
        return $this;
    }

    public function openBracket($operator)
    {
        if ($operator) {
            array_push($this->where, [
                'operator' => $operator,
                'bracket' => '(',
            ]);
        } else {
            array_push($this->where, [
                'bracket' => '(',
            ]);
        }
        return $this;
    }

    public function closeBracket()
    {
        array_push($this->where, [
            'bracket' => ')',
        ]);
        return $this;
    }

    public function whereGroup($conditions, $operator = 'AND')
    {
        $group = [];
        foreach ($conditions as $condition) {
            $newCondition = $this->convertCondition($condition[1], $condition[2]);
            $group[] = [
                'field' => $condition[0],
                'condition' => $newCondition[0],
                'value' => $newCondition[1]
            ];
        }
        $this->where[] = [
            'group' => $group,
            'operator' => $operator,
        ];
        return $this;
    }

    private function convertCondition($condition, $value)
    {
        $conversion = [
            'eq' => '=',
            'ne' => '<>',
            'le' => '<=',
            'lt' => '<',
            'gt' => '>',
            'ge' => '>=',
            'bw' => 'LIKE',
            'ew' => 'LIKE',
            'cn' => 'LIKE'
        ];

        if ($condition == 'bw') {
            $value .= '%';
        } else if ($condition == 'ew') {
            $value = '%' . $value;
        } else if ($condition == 'cn') {
            $value = '%' . $value . '%';
        }

        return [$conversion[$condition] ?? $condition, $value];
    }

    private function buildWhere($where, $isJoin = false)
    {
        $wh = '';
        $isFirst = true;

        foreach ($where as $item) {
            if (isset($item['bracket'])) {
                if ($item['bracket'] == '(') {
                    if ($wh && !$isFirst) {
                        $wh .= ' ' . $item['operator'];
                    }

                    $wh .= $item['bracket'] . ' ';
                } else {
                    $wh .= ' ' . $item['bracket'];
                }
                continue;
            }

            if (isset($item['group'])) {
                $groupWh = '';
                foreach ($item['group'] as $groupItem) {
                    if ($groupWh) {
                        $groupWh .= ' AND';
                    }
                    if ($groupItem['condition'] == 'nc') {
                        $groupWh .= ' ' . $groupItem['field'] . " NOT LIKE '%" . $groupItem['value'] . "%'";
                    } else if ($groupItem['condition'] == 'bn') {
                        $groupWh .= ' ' . $groupItem['field'] . " NOT LIKE '" . $groupItem['value'] . "%'";
                    } else if ($groupItem['condition'] == 'en') {
                        $groupWh .= ' ' . $groupItem['field'] . " NOT LIKE '%" . $groupItem['value'] . "'";
                    } else if ($groupItem['condition'] == 'IN') {
                        $groupWh .= ' ' . $groupItem['field'] . ' ' . $groupItem['condition'] . ' (' . implode(',', $groupItem['value']) . ')';
                    } else {
                        $groupWh .= ' ' . $groupItem['field'] . ' ' . $groupItem['condition'] . ((!$isJoin) ? " '" : " ") . $groupItem['value'] . ((!$isJoin) ? "'" : "");
                    }
                }
                if ($wh && !$isFirst) {
                    $wh .= ' ' . $item['operator'];
                }
                $wh .= ' (' . $groupWh . ')';
            } else {
                if ($wh && !$isFirst) {
                    $wh .= ' AND';
                }
                $isFirst = false;

                if ($item['condition'] == 'nc') {
                    $wh .= ' ' . $item['field'] . " NOT LIKE '%" . $item['value'] . "%'";
                } else if ($item['condition'] == 'bn') {
                    $wh .= ' ' . $item['field'] . " NOT LIKE '" . $item['value'] . "%'";
                } else if ($item['condition'] == 'en') {
                    $wh .= ' ' . $item['field'] . " NOT LIKE '%" . $item['value'] . "'";
                } else if ($item['condition'] == 'IN') {
                    $wh .= ' ' . $item['field'] . ' ' . $item['condition'] . ' (' . implode(',', $item['value']) . ')';
                } else {
                    $wh .= ' ' . $item['field'] . ' ' . $item['condition'] . ((!$isJoin) ? " '" : " ") . $item['value'] . ((!$isJoin) ? "'" : "");
                }
            }
        }

        return $wh;
    }

    public function sql()
    {
        $sql = 'SELECT ';
        if (!$this->select) {
            $sql .= ' * ';
        } else {
            $sql .= implode(',', $this->select);
        }
        $sql .= ' FROM ' . $this->from;
        foreach ($this->join as $item) {
            $sql .= ' ' . $item['type'] . ' ' . $item['table'] . ' ON ' . $this->buildWhere($item['condition'], true);
        }
        if ($this->where) {
            $sql .= ' WHERE ' . $this->buildWhere($this->where);
        }
        if ($this->order) {
            $sql .= ' ORDER BY ' . implode(',', $this->order);
        }

        if ($this->offset && $this->limit) {
            $sql .= ' LIMIT ' . $this->offset . ',' . $this->limit;
        } else if ($this->limit) {
            $sql .= ' LIMIT ' . $this->limit;
        }

        return $sql;
    }

    public function count()
    {
        $c = $this->db->queryOne(str_replace(' * ', 'COUNT(*) as count', $this->sql()));
        if (!$c) {
            return false;
        }

        return $c['count'];
    }

    public function rows()
    {
        return $this->db->query($this->sql());
    }

    public function one()
    {
        return $this->db->queryOne($this->sql());
    }
}
