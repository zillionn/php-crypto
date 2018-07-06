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

use ArkEcosystem\Crypto\Slot;
use ArkEcosystem\Crypto\Configuration\Fee;
use ArkEcosystem\Crypto\Identity\PrivateKey;
use ArkEcosystem\Crypto\Identity\PublicKey;
use ArkEcosystem\Crypto\Transaction;
use function Stringy\create as s;
use stdClass;

/**
 * This is the abstract transaction class.
 *
 * @author Brian Faust <brian@ark.io>
 */
abstract class AbstractTransaction
{
    /**
     * Create a new transaction instance.
     */
    public function __construct()
    {
        $this->transaction              = new Transaction();
        $this->transaction->recipientId = null;
        $this->transaction->type        = $this->getType();
        $this->transaction->amount      = 0;
        $this->transaction->fee         = $this->getFee();
        $this->transaction->vendorField = null;
        $this->transaction->timestamp   = Slot::getTime();

        $this->transaction->senderPublicKey = null;

        $this->transaction->signature     = null;
        $this->transaction->signSignature = null;

        $this->transaction->id    = null;
        $this->transaction->asset = [];
    }

    /**
     * Convert the message to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJSON();
    }

    /**
     * Create a new transaction instance.
     *
     * @return \ArkEcosystem\Crypto\Builder\AbstractTransaction
     */
    public static function new(): self
    {
        return new static();
    }

    /**
     * Set the transaction fee.
     *
     * @param int $fee
     *
     * @return \ArkEcosystem\Crypto\Builder\AbstractTransaction
     */
    public function withFee(int $fee): self
    {
        $this->transaction->fee = $fee;

        return $this;
    }

    /**
     * Sign the transaction using the given secret.
     *
     * @param string $secret
     *
     * @return \ArkEcosystem\Crypto\Builder\AbstractTransaction
     */
    public function sign(string $secret): AbstractTransaction
    {
        $keys                               = PrivateKey::fromSecret($secret);
        $this->transaction->senderPublicKey = $keys->getPublicKey()->getHex();

        $this->transaction = $this->transaction->sign($keys);

        return $this;
    }

    /**
     * Sign the transaction using the given second secret.
     *
     * @param string $secondSecret
     *
     * @return \ArkEcosystem\Crypto\Builder\AbstractTransaction
     */
    public function secondSign(string $secondSecret): AbstractTransaction
    {
        $this->transaction = $this->transaction->secondSign(PrivateKey::fromSecret($secondSecret));

        return $this;
    }

    /**
     * Verify the transaction validity.
     *
     * @return bool
     */
    public function verify(): bool
    {
        return $this->transaction->verify();
    }

    /**
     * Verify the transaction validity with a second signature.
     *
     * @return bool
     */
    public function secondVerify(string $secondSecret): bool
    {
        return $this->transaction->secondVerify(
            PublicKey::fromSecret($secondSecret)->getHex()
        );
    }

    /**
     * Convert the message to its plain object representation.
     *
     * @return \stdClass
     */
    public function getSignedObject(): stdClass
    {
        $this->transaction->id = $this->transaction->getId();

        if (empty($this->transaction->signSignature)) {
            unset($this->transaction->signSignature);
        }

        if (empty($this->transaction->asset)) {
            unset($this->transaction->asset);
        }

        return $this->transaction;
    }

    /**
     * Convert the message to its JSON representation.
     *
     * @return string
     */
    public function toJSON(): string
    {
        return json_encode($this->transaction);
    }

    /**
     * Get the transaction type.
     *
     * @return int
     */
    private function getType(): int
    {
        $identifier = $this->getIdentifier();

        return constant("ArkEcosystem\Crypto\Enums\Types::{$identifier}");
    }

    /**
     * Get the transaction fee.
     *
     * @return int
     */
    private function getFee(): int
    {
        return Fee::get($this->transaction->type);
    }

    /**
     * Get the class identifier to be used with enums.
     *
     * @return string
     */
    private function getIdentifier(): string
    {
        $className = (new \ReflectionClass($this))->getShortName();

        return (string) s($className)
            ->underscored()
            ->toUpperCase();
    }
}