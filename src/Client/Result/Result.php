<?php

namespace AbstractBabel\Client\Result;

use Countable;
use Traversable;

interface Result extends CastsToArray, Countable, Traversable
{
}
