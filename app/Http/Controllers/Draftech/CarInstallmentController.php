<?php

namespace App\Http\Controllers\Draftech;

use App\Helpers\CarInstallmentCalculator2;
use Illuminate\Http\Request;

class CarInstallmentController extends BaseController
{
    public function calculateInstallment(Request $request)
    {
        $request->validate([
            'car_price' => 'required|numeric|min:1',
            'down_payment' => 'required|numeric|min:0',
        ]);

        $calculator = new CarInstallmentCalculator2(
            $request->car_price,
            $request->down_payment
        );

        $result = $calculator->calculate();

        return response()->json($result);
    }
}
