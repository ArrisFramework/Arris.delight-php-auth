<?php

namespace Arris\DelightAuth\Db;

/** Description of a data source */
interface DataSource
{

    /**
     * Converts this instance to a DSN
     *
     * @return Dsn
     */
    public function toDsn();

}
