<?php

namespace App\Services;

use App\Services\RotatedFpdi;

class FpdiClipping extends RotatedFpdi
{
    protected $_jsonoutput = false;

    function ClippingRect($x, $y, $w, $h, $outline = false)
    {
        $op = $outline ? 'S' : 'n';
        $this->_out(sprintf('q %.2F %.2F %.2F %.2F re W %s', $x * $this->k, ($this->h - $y) * $this->k, $w * $this->k, -$h * $this->k, $op));
    }

    function UnsetClipping()
    {
        $this->_out('Q');
    }
}
