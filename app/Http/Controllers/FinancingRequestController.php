<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFinancingRequest;
use App\Models\FinancingRequest;
use Illuminate\Http\Request;



class FinancingRequestController extends Controller
{

    public function store(StoreFinancingRequest $request)
    {
        // الحصول على البيانات بعد التحقق من صحتها
        $data = $request->validated();

        // إنشاء طلب التمويل مباشرة
        $financing = FinancingRequest::create($data);

        return response()->json([
            'success' => true,
            'data' => $financing,
            'message' => 'Financing request created successfully.'
        ], 201);
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. جلب page و size من request أو استخدام قيم افتراضية
        $page = $request->get('page', 1);
        $size = $request->get('size', 10);

        // 2. حساب عدد الطلبات "In process" كاملة للمستخدم
        $inProcessCount = FinancingRequest::where('user_id', $user->id)
            ->where('status', 'In process')
            ->count();

        // 3. جلب الطلبات مع العلاقات واستخدام paginate
        $requests = FinancingRequest::with('brand')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate($size, ['*'], 'page', $page);
        $formatted = $requests->map(function ($item) {
            return [
                'id' => $item->id,
                'brand' => $item->car_brand,
                'brand_img' => $item->brand?->image_path,
                'car_model' => $item->car_model,
                'year' => $item->manufacture_year,
                'price' => $item->total_price,
                'status' => $item->status,
                'created_at' => $item->created_at->toDateString(),
            ];
        });

        $pagination = [
            'current_page' => $requests->currentPage(),
            'per_page' => $requests->perPage(),
            'total' => $requests->total(),
            'last_page' => $requests->lastPage(),
        ];

        // 6. الإرجاع النهائي
        return response()->json([
            'can_apply' => $inProcessCount < 3,
            'data' => $formatted,
            'pagination' => $pagination,
        ]);
    }

    public function cancel(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:financing_requests,id',
        ]);

        $financing = FinancingRequest::where('id', $request->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($financing->status !== 'In process') {
            return response()->json(['message' => 'Cannot cancel this request.'], 403);
        }

        $financing->update(['status' => 'Cancelled']);
        return response()->json(['message' => 'Request cancelled successfully.']);
    }


}
