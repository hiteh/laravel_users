<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

trait LengthAwarePaginable
{
	/**
     * Get data paginated.
     *
     * @param int $items_per_page
     * @param array $data
     * @param array $query
     * @param string $url
     * @param mixed $paginator
     * @return mixed
     */
	public function paginate( int $items_per_page, array $data, array $query, string $url, $paginator = LengthAwarePaginator::class )
	{
        $page = isset( $query['page'] ) ? $query['page'] : 1;
        $offset = ( $page * $items_per_page ) - $items_per_page;

        return new $paginator( array_slice( $data, $offset, $items_per_page, true ), count( $data ), $items_per_page, $page, ['path' => $url, 'query' => $query ] );
	}
}