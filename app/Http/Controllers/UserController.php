<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the users with sorting and filtering.
     */
    public function index(Request $request): View
    {
        // Get sorting parameters from request
        $sortColumn = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'asc');
        
        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        // Get filter parameters from request
        $filters = [
            'search' => $request->get('search'),
            'age_min' => $request->get('age_min'),
            'age_max' => $request->get('age_max'),
            'city' => $request->get('city'),
        ];

        // Build the query with filtering and sorting
        $query = User::query();
        
        // Apply filters
        $query = $query->filter($filters);
        
        // Apply sorting
        $query = $query->sortBy($sortColumn, $sortDirection);

        // Get paginated results
        $users = $query->paginate(15)->withQueryString();

        // Get unique cities for filter dropdown
        $cities = User::distinct()->pluck('city')->filter()->sort()->values();

        // Prepare data for view
        $data = [
            'users' => $users,
            'cities' => $cities,
            'currentSort' => $sortColumn,
            'currentDirection' => $sortDirection,
            'filters' => $filters,
        ];

        return view('users.index', $data);
    }

    /**
     * Get the sortable columns for the table headers
     */
    private function getSortableColumns(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'age' => 'Age',
            'city' => 'City',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Generate sort URL for table headers
     */
    private function generateSortUrl(string $column, Request $request): string
    {
        $currentSort = $request->get('sort', 'id');
        $currentDirection = $request->get('direction', 'asc');
        
        // If clicking the same column, toggle direction
        if ($currentSort === $column) {
            $newDirection = $currentDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $newDirection = 'asc';
        }

        // Build query parameters
        $queryParams = $request->all();
        $queryParams['sort'] = $column;
        $queryParams['direction'] = $newDirection;
        
        // Remove page parameter when sorting
        unset($queryParams['page']);

        return '?' . http_build_query($queryParams);
    }

    /**
     * Get sort icon for table headers
     */
    private function getSortIcon(string $column, string $currentSort, string $currentDirection): string
    {
        if ($currentSort !== $column) {
            return '<i class="fas fa-sort text-muted"></i>';
        }

        return $currentDirection === 'asc' 
            ? '<i class="fas fa-sort-up text-primary"></i>' 
            : '<i class="fas fa-sort-down text-primary"></i>';
    }
}