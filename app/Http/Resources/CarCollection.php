<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

class CarCollection extends ResourceCollection
{
    public function toArray($request)
    {
        // لو Kalksat
        if (config('app.app') === 'kalksat') {
            return [
                'message' => 'Cars fetched successfully',
                'data' => CarResource::collection($this->collection),
                'count' => $this->collection->count(),
            ];
        }

        // لو Draftech
        $data = [
            'status' => true,
            'message' => 'Cars fetched successfully.',
            'data' => [
                'items' => CarResource::collection(
                    $this->resource instanceof AbstractPaginator
                        ? collect($this->resource->items())   // ✅ هنا التعديل
                        : $this->collection
                ),
            ],
        ];

        if ($this->resource instanceof AbstractPaginator) {
            $data['data']['pagination'] = [
                'current_page' => $this->resource->currentPage(),
                'per_page'     => $this->resource->perPage(),
                'total'        => $this->resource->total(),
                'last_page'    => $this->resource->lastPage(),
            ];
        }

        return $data;
    }
}
