<?php

namespace SimpleBus\Message\Tests\Bus\Fixtures;

class CallbackClass
{
    /**
     * @var string
     */
    public $locallyStoredMessage;

    public function storeMessageLocally($messageToStoreLocally)
    {
        $this->locallyStoredMessage = $messageToStoreLocally;
    }
}
