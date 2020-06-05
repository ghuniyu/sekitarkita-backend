<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

class NearbyCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public static $wrap = 'nearbies';

    public function toResponse($request)
    {
        return $this->resource instanceof AbstractPaginator
            ? (new CustomPaginatedResourceResponse($this))->toResponse($request)
            : parent::toResponse($request);
    }

}
