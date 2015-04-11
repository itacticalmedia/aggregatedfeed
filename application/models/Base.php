<?php

/**
 * This class is a base class for all models
 */
abstract class Application_Model_Base
{

    function __construct($id = NULL)
    {

        if ($id && $id > 0)
        {
            $mp = $this->getMapper();
            $mp->initObject($mp->find($id), $this);
        }else
        {
            $this->setId(0);
        }
    }

    /**
     * This class return the mapper object of the corresponding model
     * @return Application_Model_MapperBase 
     */
    public function getMapper()
    {
        $mapperName = get_class($this) . "Mapper";
        return new $mapperName();
    }

    /**
     * This is a setter functions of all properties
     * @param string $name
     * @param string $value
     * @throws Exception
     */
    public function __set($name, $value)
    {
        if ($name[0] == '_')
        {
            $this->$name = $value;
        } else
        {
            throw new Exception("Not a valid property");
        }
    }

    /**
     * This is a getter funcion for all properties
     * @param string $name
     * @return type
     */
    public function __get($name)
    {
        return $this->$name;
    }

}
