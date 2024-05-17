<?php

namespace NGFramer\NGFramerPHPSQLBuilder\DataManipulation;

use NGFramer\NGFramerPHPSQLBuilder\DataManipulation\Supportive\_DmlTable;

class SelectTable extends _DmlTable
{
    // Use the following trait to access the functions.
    use WhereTrait, LimitTrait, SortByTrait, GroupByTrait{
        WhereTrait::build as buildWhere;
        LimitTrait::build as buildLimit;
//      SortByTrait::build as buildSortBy; // TODO: To be built.
//      GroupByTrait::build as buildGroupBy; // TODO: To be built.
    }




    // Constructor function for the class.
    public function __construct(string $tableName, array $columns)
    {
        parent::__construct($tableName);
        $this->addQueryLog('table', $tableName, 'selectData');
        $this->select($columns);
    }




    // Main function for the class.
    public function select(array $fields): void
    {
        foreach ($fields as $field) {
            if (!is_string($field)) {
                throw new \InvalidArgumentException('Field names must be string.');
            }
            $this->addToQueryLogDeepArray('columns', $field);
        }
    }




    // Builder function for the class.
    public function build(): string
    {
        // Get the queryLog initially to process.
        $queryLog = $this->getQueryLog();
        // Start building the where query.
        $query = 'SELECT ';
        $query .= implode(', ', $queryLog['columns']);
        $query .= ' FROM ' . $queryLog['table'];
        $query .= $this->buildWhere();
//        $query .= $this->buildSortBy();
//        $query .= $this->buildGroupBy();
//        $query .= $this->buildLimit();
        return $query;
    }
}