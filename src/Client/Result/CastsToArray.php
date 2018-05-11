<?php

namespace AbstractBabel\Client\Result;

use ArrayAccess;

interface CastsToArray extends ArrayAccess
{
    public function toArray() : array;
}
