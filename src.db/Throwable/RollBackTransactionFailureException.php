<?php

namespace Arris\DelightAuth\Db\Throwable;

/** Exception that is thrown when a transaction cannot be rolled back successfully for some reason */
class RollBackTransactionFailureException extends TransactionFailureException
{
}
