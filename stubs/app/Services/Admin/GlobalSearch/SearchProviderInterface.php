<?php

namespace App\Services\Admin\GlobalSearch;

interface SearchProviderInterface
{
    /**
     * Perform a search query and return an array of standardized results.
     * Each result must be an associative array with keys:
     * - 'type' (string)
     * - 'title' (string)
     * - 'url' (string)
     * - 'icon_svg' (string)
     *
     * @param string $query
     * @param int $limit
     * @return array
     */
    public function search(string $query, int $limit): array;
}
