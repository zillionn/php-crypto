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

namespace ArkEcosystem\Tests\Crypto\Serialisers;

use ArkEcosystem\Crypto\Serialiser;
use ArkEcosystem\Tests\Crypto\TestCase;

/**
 * This is the multi payment serialiser test class.
 *
 * @author Brian Faust <brian@ark.io>
 * @coversNothing
 */
class MultiPaymentTest extends TestCase
{
    /** @test */
    public function it_should_serialise_the_transaction()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');

        $transaction = $this->getTransactionFixture(7);

        $actual = Serialiser::new($transaction)->serialise();

        $this->assertSame($transaction->serialized, $actual->getHex());
    }
}