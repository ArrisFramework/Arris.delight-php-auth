<?php

namespace Arris\DelightAuth\Auth;

use Arris\DelightAuth\Db\PdoDatabase;
use Arris\DelightAuth\Db\PdoDsn;

class Config extends UserManager
{
    /**
     * @param PdoDatabase|PdoDsn|\PDO $databaseConnection the database connection to operate on
     * @param string|null $dbTablePrefix (optional) the prefix for the names of all database tables used by this component
     * @param string|null $dbSchema (optional) the schema name for all database tables used by this component
     */
    public function __construct($databaseConnection, $dbTablePrefix = null, $dbSchema = null)
    {
        parent::__construct($databaseConnection, $dbTablePrefix, $dbSchema);
    }

    /**
     * @param string $name
     * @return string
     */
    public function getTable(string $name):string
    {
        $table = match ($name) {
            'confirmations' =>  'users_confirmations',
            'remembered'    =>  'users_remembered',
            'resets'        =>  'users_resets',
            'throttling'    =>  'users_throttling',
            default         =>  'users'
        };

        if ($this->dbTablePrefix) {
            $table = $this->dbTablePrefix . $table;
        }

        return $table;
    }

}