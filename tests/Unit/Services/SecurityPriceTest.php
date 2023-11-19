<?php

namespace Tests\Unit\Services;

use App\Events\SynPrice;
use App\Models\Security;
use App\Models\SecurityPrice as SecurityPriceModel;
use App\Models\SecurityType;
use App\Services\GetData;
use App\Services\SecurityPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class SecurityPriceTest extends TestCase
{
    use RefreshDatabase;

    protected $getData;

    protected $securityType;

    protected $securityPriceModel;

    protected $securityPrice;

    public function setUp(): void
    {
        parent::setUp();
        Event::fake();
        $this->setModelDataBase();

        $this->getData = $this->createMock(GetData::class);

        $this->getData->method('__invoke')
            ->willReturn([
                [
                    'symbol' => 'APPL',
                    'price' => 188.97,
                    'last_price_datetime' => '2023-10-30T17:31:18-04:00',
                ],
                [
                    'symbol' => 'TSLA',
                    'price' => 244.42,
                    'last_price_datetime' => '2023-10-30T17:32:11-04:00',
                ],
                [
                    'symbol' => 'AWS',
                    'price' => 882.42,
                    'last_price_datetime' => '2023-10-30T17:35:20-04:00',
                ],
            ]);

        $this->securityPrice = new SecurityPrice(
            $this->getData,
            resolve(SecurityType::class),
            resolve(SecurityPriceModel::class)
        );
    }

    /**
     * @test
     */
    public function itCanInvokeSecurityPriceService()
    {
        $this->assertNull($this->securityPrice->__invoke('securityType'));
    }

    /**
     * @test
     */
    public function invokeSecurityPriceServiceAndSuccess()
    {
        $securityType = 'mutual_funds';

        $result = $this->securityPrice->__invoke($securityType);

        $this->assertEquals(
            $result,
            [
                [
                    'id' => 2,
                    'security' => 'APPL',
                    'last_price' => 188.97,
                    'as_of_date' => '2023-10-30 21:31:18',
                ],
                [
                    'id' => 1,
                    'security' => 'TSLA',
                    'last_price' => 244.42,
                    'as_of_date' => '2023-10-30 21:32:11',
                ],
            ]
        );
        Event::assertDispatched(
            SynPrice::class,
            fn ($event) =>
                $event->securityPrices === [
                    'securityType' => $securityType,
                    'securityPrices' => $result,
                ]
        );
    }

    /**
     * @test
     */
    public function invokeSecurityPriceServiceAndFail()
    {
        $this->expectException(\Throwable::class);
        $this->getData->method('__invoke')
            ->willThrowException(new \Exception('Simulando una excepción'));

        $this->securityPrice->__invoke('mutual_funds');
    }

    private function setModelDataBase()
    {
        $securityType = SecurityType::factory()->create([
            'name' => 'mutual_funds',
        ]);

        SecurityPriceModel::factory()->create([
            'security_id' => Security::factory()->create([
                'security_type_id' => $securityType->id,
                'symbol' => 'TSLA',
            ])->id,
        ]);

        Security::factory()->create([
            'security_type_id' => $securityType->id,
            'symbol' => 'APPL',
        ]);
    }
}
