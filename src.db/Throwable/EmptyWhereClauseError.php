<?php

namespace Arris\DelightAuth\Db\Throwable;

/**
 * Error that is thrown when an empty `WHERE` clause is provided
 *
 * Although technically perfectly valid, an empty list of criteria is often provided by mistake
 *
 * This is why, for some operations, it is deemed too dangerous and thus disallowed
 *
 * Usually, one can simply execute a manual statement instead to get rid of this restriction
 */
class EmptyWhereClauseError extends Error
{
}
