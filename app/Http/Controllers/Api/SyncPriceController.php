<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SyncPriceRequest;
use App\Services\SecurityPrice;
use Illuminate\Http\JsonResponse;
use Throwable;

class SyncPriceController extends Controller
{
    public function syncPrice(SyncPriceRequest $request, SecurityPrice $securityPrice): JsonResponse
    {
        try {
            $prices = $securityPrice($request->type);
            return response()->json([
                'message' => __(
                    'For type :securityType the following :quantity prices have been updated.',
                    [
                        'securityType' => $request->type,
                        'quantity' => $prices ? count($prices) : 0,
                    ]
                ),
                'prices' => $prices,
            ], JsonResponse::HTTP_OK);
        } catch (Throwable $throw) {
            return response()->json([
                'message' => $throw->getMessage(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
