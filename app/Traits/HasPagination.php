<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

trait HasPagination
{
    /**
     * Default items per page
     */
    protected int $defaultPerPage = 15;

    /**
     * Available items per page options
     */
    protected array $perPageOptions = [10, 15, 25, 50, 100];

    /**
     * Get items per page from request
     */
    protected function getPerPage(Request $request): int
    {
        $perPage = $request->input('per_page', $this->defaultPerPage);
        
        // Validate per_page value
        if (!in_array($perPage, $this->perPageOptions)) {
            $perPage = $this->defaultPerPage;
        }

        return (int) $perPage;
    }

    /**
     * Apply pagination to query builder
     */
    protected function paginateQuery(Builder $query, Request $request): LengthAwarePaginator
    {
        $perPage = $this->getPerPage($request);
        
        return $query->paginate($perPage)
                    ->appends($request->query());
    }

    /**
     * Get pagination info for view
     */
    protected function getPaginationInfo(LengthAwarePaginator $paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
            'has_pages' => $paginator->hasPages(),
            'per_page_options' => $this->perPageOptions
        ];
    }

    /**
     * Apply search filters to query
     */
    protected function applySearch(Builder $query, Request $request, array $searchableFields): Builder
    {
        $search = $request->input('search');
        
        if (!empty($search)) {
            $query->where(function ($q) use ($search, $searchableFields) {
                foreach ($searchableFields as $field) {
                    if (str_contains($field, '.')) {
                        // Handle relationship fields
                        $parts = explode('.', $field);
                        $relation = $parts[0];
                        $relationField = $parts[1];
                        
                        $q->orWhereHas($relation, function ($relationQuery) use ($relationField, $search) {
                            $relationQuery->where($relationField, 'like', '%' . $search . '%');
                        });
                    } else {
                        // Handle direct fields
                        $q->orWhere($field, 'like', '%' . $search . '%');
                    }
                }
            });
        }

        return $query;
    }

    /**
     * Apply filters to query
     */
    protected function applyFilters(Builder $query, Request $request, array $filters): Builder
    {
        foreach ($filters as $field => $config) {
            $value = $request->input($field);
            
            if (!empty($value)) {
                if (is_array($config)) {
                    // Custom filter configuration
                    $operator = $config['operator'] ?? '=';
                    $column = $config['column'] ?? $field;
                    
                    if (isset($config['callback'])) {
                        // Custom callback
                        $query = $config['callback']($query, $value, $request);
                    } else {
                        $query->where($column, $operator, $value);
                    }
                } else {
                    // Simple equality filter
                    $query->where($field, $value);
                }
            }
        }

        return $query;
    }

    /**
     * Apply sorting to query
     */
    protected function applySorting(Builder $query, Request $request, string $defaultSort = 'created_at', string $defaultDirection = 'desc'): Builder
    {
        $sortBy = $request->input('sort', $defaultSort);
        $sortDirection = $request->input('direction', $defaultDirection);
        
        // Validate sort direction
        if (!in_array(strtolower($sortDirection), ['asc', 'desc'])) {
            $sortDirection = $defaultDirection;
        }

        return $query->orderBy($sortBy, $sortDirection);
    }
}