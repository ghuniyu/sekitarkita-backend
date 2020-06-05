<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\PaginatedResourceResponse;
use Illuminate\Support\Arr;

class CustomPaginatedResourceResponse extends PaginatedResourceResponse
{

    /**
     * Add the pagination information to the response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function paginationInformation($request)
    {
        $paginated = $this->resource->resource->toArray();

        return [
            'pagination' => $this->pagination($paginated),
        ];
    }

    /**
     * Meta Pagination
     *
     * @param array $paginated
     * @return array
     */
    protected function pagination($paginated)
    {
        $paginated['next_page'] = $paginated['current_page'] != $paginated['last_page'] ? ($paginated['current_page']+1) : null;

        return Arr::except($paginated, [
            'data',
        ]);
    }
}
