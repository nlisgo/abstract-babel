<?php

namespace AbstractBabel\CrossRefClient\Result;

use ArrayAccess;

interface CastsToArray extends ArrayAccess
{
    public function toArray() : array;
}
