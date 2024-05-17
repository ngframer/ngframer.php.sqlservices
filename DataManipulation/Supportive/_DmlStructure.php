<?php

namespace NGFramer\NGFramerPHPSQLBuilder\DataManipulation\Supportive;

use NGFramer\NGFramerPHPSQLBuilder\_Builder;

abstract class _DmlStructure extends _Builder
{
    // Variable defined here.
    private bool $goDirect = false;
    private array $bindParameters = [];
    private array $structure;




    // Constructor function for the class.
    protected function __construct(string $structureType, string $structureValue)
    {
        if (empty($structureType)) {
            throw new \Exception('Structure type cannot be empty. Please provide a structure type.');
        }
        if (empty($structureValue)) {
            throw new \Exception("$structureType name cannot be empty. Please provide a structure value.");
        }
        $this->setStructure($structureType, $structureValue);
    }

    protected function setStructure(string $structureType, string $structureValue): void
    {
        $this->structure['type'] = $structureType;
        $this->structure['value'] = $structureValue;
    }

    protected function getStructureValue(): string
    {
        return $this->structure['value'];
    }




    // Functions used for defining the query execution method.
    public function goDirect(): void
    {
        // Use this function to set the method of executing the query to direct (not using prepare).
        $this->goDirect = true;
    }

    // Function to update/add to the bind parameters.
    protected function updateBindParameters(string $key, string $value): void
    {
        if (array_key_exists($key, $this->bindParameters)){
            throw new \Exception("Something unexpected happened. Repeated bindParameters Key.");
        }else{
            $this->bindParameters[$key] = $value;
        }
    }

    // Provides number to use further for the another binding, array starts from 0th position.
    protected function getBindIndexStarter(): int
    {
        return $this->goDirect ? count($this->bindParameters):0;
    }
}