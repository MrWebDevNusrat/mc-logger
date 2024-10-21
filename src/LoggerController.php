<?php

namespace MC\Logger;

use App\Http\Controllers\Controller;

class LoggerController extends Controller
{
    public function add($a,$b)
    {
        echo $a + $b;
    }

    public function substract($a,$b)
    {
        echo $a - $b;
    }
}
