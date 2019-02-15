<?php
namespace Core;
/**
 * Class model
 * @package core\lib
 */
class Model extends Medoo
{
    /**
     * model constructor.
     */
    public function __construct()
    {
        $option = Conf::get('database');
        parent::__construct($option);
    }
}