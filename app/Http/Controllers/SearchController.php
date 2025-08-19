<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterSearchRequest;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 * Controller for handling search-related requests.
 */
class SearchController extends Controller
{
    
    protected SearchService $searchService;

  
    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

  

    public function search(FilterSearchRequest $request)
    {
        $validatedData = $request->validated();

        $term = $validatedData['query'];
        $type = $validatedData['type'] ?? 'all';
        $limit = (int) ($validatedData['limit'] ?? 10);

        $results = $this->searchService->performSearch($term, $type, $limit);

        return response()->json([
            'status'  => ResponseAlias::HTTP_OK,
            'success' => true,
            'message' => 'Search results fetched successfully',
            'results' => $results,
        ], ResponseAlias::HTTP_OK);
    }
}
