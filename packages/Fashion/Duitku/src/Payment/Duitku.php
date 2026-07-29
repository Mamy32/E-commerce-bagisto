<?php

namespace Fashion\Duitku\Payment;

use Webkul\Payment\Payment\Payment;

class Duitku extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'duitku';

    public function getRedirectUrl()
    {
        return route('duitku.redirect');
    }
}
