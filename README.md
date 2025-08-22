# Laravel Sorting & Filtering System

This project demonstrates a complete Laravel implementation of column-based sorting and filtering functionality that works directly from HTML table headers to controller functions.

## Features

- **Column Sorting**: Click any table header to sort by that column
- **Direction Toggle**: Click the same column again to reverse sort order
- **Visual Indicators**: Sort icons show current sort state
- **Advanced Filtering**: Search, age range, and city filters
- **Pagination**: Maintains sort/filter state across pages
- **Responsive Design**: Bootstrap-based UI with Font Awesome icons

## How It Works

### 1. HTML to Controller Flow

The sorting system works through this flow:

```
HTML Table Header Click → JavaScript Function → URL Update → Controller Method → Database Query → View Update
```

#### HTML Table Headers
```html
<th class="sortable-header" onclick="sortTable('name')">
    Name
    @if($currentSort === 'name')
        <i class="fas fa-sort-{{ $currentDirection === 'asc' ? 'up' : 'down' }} sort-icon text-warning"></i>
    @else
        <i class="fas fa-sort sort-icon text-muted"></i>
    @endif
</th>
```

#### JavaScript Function
```javascript
function sortTable(column) {
    const currentUrl = new URL(window.location);
    const currentSort = currentUrl.searchParams.get('sort') || 'id';
    const currentDirection = currentUrl.searchParams.get('direction') || 'asc';
    
    let newDirection = 'asc';
    
    // If clicking the same column, toggle direction
    if (currentSort === column) {
        newDirection = currentDirection === 'asc' ? 'desc' : 'asc';
    }
    
    // Update URL parameters
    currentUrl.searchParams.set('sort', column);
    currentUrl.searchParams.set('direction', newDirection);
    currentUrl.searchParams.delete('page');
    
    // Navigate to new URL
    window.location.href = currentUrl.toString();
}
```

### 2. Controller Implementation

The controller receives the sort parameters and applies them to the database query:

```php
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

    return view('users.index', compact('users', 'filters', 'sortColumn', 'sortDirection'));
}
```

### 3. Model Scopes

The User model includes scopes for sorting and filtering:

```php
/**
 * Scope for sorting
 */
public function scopeSortBy($query, $column, $direction = 'asc')
{
    if (in_array($column, $this->fillable)) {
        return $query->orderBy($column, $direction);
    }
    return $query;
}

/**
 * Scope for filtering
 */
public function scopeFilter($query, $filters)
{
    if (isset($filters['search']) && !empty($filters['search'])) {
        $query->where(function($q) use ($filters) {
            $q->where('name', 'like', '%' . $filters['search'] . '%')
              ->orWhere('email', 'like', '%' . $filters['search'] . '%')
              ->orWhere('city', 'like', '%' . $filters['search'] . '%');
        });
    }

    if (isset($filters['age_min']) && !empty($filters['age_min'])) {
        $query->where('age', '>=', $filters['age_min']);
    }

    if (isset($filters['age_max']) && !empty($filters['age_max'])) {
        $query->where('age', '<=', $filters['age_max']);
    }

    if (isset($filters['city']) && !empty($filters['city'])) {
        $query->where('city', $filters['city']);
    }

    return $query;
}
```

## URL Structure

The system generates URLs like:
- `/users?sort=name&direction=asc` - Sort by name ascending
- `/users?sort=age&direction=desc&age_min=25&city=New%20York` - Sort by age descending with filters
- `/users?sort=created_at&direction=desc&page=2` - Sort by creation date with pagination

## Key Benefits

1. **SEO Friendly**: Sort parameters are in URLs, making them shareable and bookmarkable
2. **State Persistence**: Filters and sorting persist across pagination
3. **Performance**: Efficient database queries with proper indexing
4. **User Experience**: Visual feedback and intuitive interaction
5. **Maintainable**: Clean separation of concerns between view, controller, and model

## Usage Examples

### Basic Sorting
Click any column header to sort by that column. Click again to reverse the order.

### Advanced Filtering
- **Search**: Type in the search box to find users by name, email, or city
- **Age Range**: Set minimum and maximum age limits
- **City Filter**: Select a specific city from the dropdown

### Combining Filters and Sorting
You can combine multiple filters with sorting:
1. Set your filters (search, age range, city)
2. Click any column header to sort
3. The system maintains your filters while applying the sort

## Security Features

- **Column Validation**: Only allows sorting on fillable columns
- **Direction Validation**: Only accepts 'asc' or 'desc' values
- **SQL Injection Protection**: Uses Laravel's query builder
- **XSS Protection**: Blade templating automatically escapes output

## Customization

### Adding New Sortable Columns
1. Add the column to the `$fillable` array in the User model
2. Add the column header to the index.blade.php view
3. The sorting will work automatically

### Adding New Filters
1. Add the filter field to the view
2. Add the filter logic to the `scopeFilter` method in the User model
3. Update the controller to pass the new filter parameter

### Changing Sort Behavior
Modify the `scopeSortBy` method in the User model to implement custom sorting logic (e.g., case-insensitive sorting, relationship sorting, etc.)

## Requirements

- Laravel 10+
- PHP 8.1+
- MySQL/PostgreSQL/SQLite database
- Bootstrap 5 (for styling)
- Font Awesome 6 (for icons)

## Installation

1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure your database
4. Run `php artisan migrate`
5. Run `php artisan db:seed` to populate sample data
6. Start your development server with `php artisan serve`

## Testing the Sorting

1. Navigate to `/users`
2. Click different column headers to see sorting in action
3. Use the filters to narrow down results
4. Notice how sorting and filtering work together
5. Test pagination to see state persistence

This implementation provides a robust, user-friendly sorting system that's easy to maintain and extend for your Laravel applications.