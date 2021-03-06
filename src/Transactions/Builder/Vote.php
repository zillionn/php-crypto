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

namespace ArkEcosystem\Crypto\Transactions\Builder;

use ArkEcosystem\Crypto\Identities\Address;
use ArkEcosystem\Crypto\Configuration\Network;

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

        $this->transaction->asset = [];
    }

    /**
     * Set the votes to cast.
     *
     * @param array $votes
     *
     * @return \ArkEcosystem\Crypto\Transactions\Builder\Vote
     */
    public function votes(array $votes): self
    {
        $this->transaction->asset['votes'] = $votes;

        return $this;
    }

    /**
     * Sign the transaction using the given passphrase.
     *
     * @param string $passphrase
     *
     * @return \ArkEcosystem\Crypto\Transactions\Builder\AbstractTransaction
     */
    public function sign(string $passphrase): AbstractTransaction
    {
        $this->transaction->recipientId = Address::fromPassphrase($passphrase, Network::get());

        parent::sign($passphrase);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getType(): int
    {
        return \ArkEcosystem\Crypto\Enums\Types::VOTE;
    }
}
