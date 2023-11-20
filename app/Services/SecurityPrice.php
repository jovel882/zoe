<?php

namespace App\Services;

use Throwable;
use Carbon\Carbon;
use App\Events\SynPrice;
use App\Models\Security;
use App\Models\SecurityType;
use Illuminate\Support\Facades\DB;
use App\Models\SecurityPrice as SecurityPriceModel;

class SecurityPrice
{
    public function __construct(
        public GetData $getData,
        public SecurityType $securityType,
        public SecurityPriceModel $securityPrice
    )
    {
    }

    public function __invoke(string $securityType): array|null
    {
        try {
            DB::beginTransaction();

            $securityPrices = $this->process($securityType);

            DB::commit();

            if (!empty($securityPrices)) {
                SynPrice::dispatch(
                    [
                        'securityType' => $securityType,
                        'securityPrices' => $securityPrices
                    ]
                );
            }
            return $securityPrices;
        } catch (Throwable $throw) {
            DB::rollBack();
            throw $throw;
        }
    }

    private function process(string $securityType): array|null
    {
        $securityTypeModel = $this->validateAndGetSecurityType($securityType);
        if ($securityTypeModel) {
            return $this->loadSecurityPrices($securityTypeModel, $this->getDataExternal($securityType));
        }
        return null;
    }

    private function validateAndGetSecurityType(string $securityType): SecurityType|bool
    {
        $securityTypeModel = $this->securityType->getFromNameWithSecurities($securityType);

        if ($securityTypeModel && !empty($securityTypeModel->securities)) {
            return $securityTypeModel;
        }

        return false;
    }

    private function getDataExternal(string $securityType): array
    {
        return $this->getData->__invoke($securityType);
    }

    private function loadSecurityPrices(SecurityType $securityType, array $dataExternal): array
    {
        $securityPrices = [];
        foreach ($dataExternal as $priceExternal) {
            if ($security = $this->getSecurity($securityType, $priceExternal['symbol'])) {
                $priceExternal['last_price_datetime'] = $this->transformDate($priceExternal['last_price_datetime']);

                $securityPrices[] = $this->getDataPriceReturn(
                    $this->syncSecurityPrice(
                        $priceExternal,
                        $security,
                        $securityPrices
                    ),
                    $priceExternal
                );
            }
        }
        return $securityPrices;
    }

    private function getSecurity(SecurityType $securityType, string $priceExternalName): Security|null
    {
        return $securityType->securities()
            ->whereSymbol($priceExternalName)
            ->first();
    }
    
    private function transformDate($date)
    {
        $date = new Carbon($date);
        $date->setTimezone(config('app.timezone'));
        return $date->format('Y-m-d H:i:s');
    }

    private function syncSecurityPrice(array $priceExternal, Security $security): int
    {
        return $this->securityPrice->updateOrCreate(
            [
                'security_id' => $security->id
            ],
            [
                'last_price' => $priceExternal['price'],
                'as_of_date' => $priceExternal['last_price_datetime']
            ]
        )->id;
    }

    private function getDataPriceReturn(int $id, array $priceExternal): array
    {
        return [
            'id' => $id,
            'security' => $priceExternal['symbol'],
            'last_price' => $priceExternal['price'],
            'as_of_date' => $priceExternal['last_price_datetime']
        ];
    }
}
