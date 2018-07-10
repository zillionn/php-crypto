<?php

declare(strict_types=1);

/*
 * This file is part of Ark PHP Crypto.
 *
 * (c) Ark Ecosystem <info@ark.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArkEcosystem\Crypto\Builder;

use ArkEcosystem\Crypto\Configuration\Network;
use ArkEcosystem\Crypto\Identity\Address;
use stdClass;

/**
 * This is the vote transaction class.
 *
 * @author Brian Faust <brian@ark.io>
 */
class Vote extends AbstractTransaction
{
    /**
     * Create a new multi signature transaction instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->transaction->asset = new stdClass();
    }

    /**
     * Set the votes to cast.
     *
     * @param array $votes
     *
     * @return \ArkEcosystem\Crypto\Builder\Vote
     */
    public function votes(array $votes): self
    {
        $this->transaction->asset->votes = $votes;

        return $this;
    }

    /**
     * Sign the transaction using the given passphrase.
     *
     * @param string $passphrase
     *
     * @return \ArkEcosystem\Crypto\Builder\AbstractTransaction
     */
    public function sign(string $passphrase): AbstractTransaction
    {
        $this->transaction->recipientId = Address::fromPassphrase($passphrase, Network::get());

        parent::sign($passphrase);

        return $this;
    }
}
