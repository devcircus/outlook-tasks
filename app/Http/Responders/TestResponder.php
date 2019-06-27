<?php

namespace App\Http\Responders;

use PerfectOblivion\Responder\Responder;

class TestResponder extends Responder
{
    /**
     * Send a response.
     *
     * @return mixed
     */
    public function respond()
    {
        if ($this->payload->getStatus() >= 400 && $this->payload->getStatus() < 500) {
            return response()->jsonWithPayload($this->payload);
        }

        return response()->jsonWithPayload($this->payload);
    }
}
