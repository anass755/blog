<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Sorting & Filtering Demo</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .sortable-header {
            cursor: pointer;
            user-select: none;
        }
        .sortable-header:hover {
            background-color: #f8f9fa;
        }
        .sort-icon {
            margin-left: 5px;
        }
        .filter-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Users Management</h1>
        
        <!-- Filters Section -->
        <div class="filter-section">
            <h5 class="mb-3">Filters</h5>
            <form method="GET" action="{{ route('users.index') }}" class="row g-3">
                <!-- Search -->
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ $filters['search'] ?? '' }}" 
                           placeholder="Name, Email, or City">
                </div>
                
                <!-- Age Range -->
                <div class="col-md-2">
                    <label for="age_min" class="form-label">Min Age</label>
                    <input type="number" class="form-control" id="age_min" name="age_min" 
                           value="{{ $filters['age_min'] ?? '' }}" min="0">
                </div>
                
                <div class="col-md-2">
                    <label for="age_max" class="form-label">Max Age</label>
                    <input type="number" class="form-control" id="age_max" name="age_max" 
                           value="{{ $filters['age_max'] ?? '' }}" min="0">
                </div>
                
                <!-- City Filter -->
                <div class="col-md-3">
                    <label for="city" class="form-label">City</label>
                    <select class="form-select" id="city" name="city">
                        <option value="">All Cities</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ ($filters['city'] ?? '') == $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Filter Buttons -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Results Summary -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="mb-0">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} 
                of {{ $users->total() }} results
            </p>
            <small class="text-muted">
                Sorted by: <strong>{{ ucfirst($currentSort) }}</strong> 
                ({{ $currentDirection === 'asc' ? 'Ascending' : 'Descending' }})
            </small>
        </div>

        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th class="sortable-header" onclick="sortTable('id')">
                            ID
                            @if($currentSort === 'id')
                                <i class="fas fa-sort-{{ $currentDirection === 'asc' ? 'up' : 'down' }} sort-icon text-warning"></i>
                            @else
                                <i class="fas fa-sort sort-icon text-muted"></i>
                            @endif
                        </th>
                        <th class="sortable-header" onclick="sortTable('name')">
                            Name
                            @if($currentSort === 'name')
                                <i class="fas fa-sort-{{ $currentDirection === 'asc' ? 'up' : 'down' }} sort-icon text-warning"></i>
                            @else
                                <i class="fas fa-sort sort-icon text-muted"></i>
                            @endif
                        </th>
                        <th class="sortable-header" onclick="sortTable('email')">
                            Email
                            @if($currentSort === 'email')
                                <i class="fas fa-sort-{{ $currentDirection === 'asc' ? 'up' : 'down' }} sort-icon text-warning"></i>
                            @else
                                <i class="fas fa-sort sort-icon text-muted"></i>
                            @endif
                        </th>
                        <th class="sortable-header" onclick="sortTable('age')">
                            Age
                            @if($currentSort === 'age')
                                <i class="fas fa-sort-{{ $currentDirection === 'asc' ? 'up' : 'down' }} sort-icon text-warning"></i>
                            @else
                                <i class="fas fa-sort sort-icon text-muted"></i>
                            @endif
                        </th>
                        <th class="sortable-header" onclick="sortTable('city')">
                            City
                            @if($currentSort === 'city')
                                <i class="fas fa-sort-{{ $currentDirection === 'asc' ? 'up' : 'down' }} sort-icon text-warning"></i>
                            @else
                                <i class="fas fa-sort sort-icon text-muted"></i>
                            @endif
                        </th>
                        <th class="sortable-header" onclick="sortTable('created_at')">
                            Created At
                            @if($currentSort === 'created_at')
                                <i class="fas fa-sort-{{ $currentDirection === 'asc' ? 'up' : 'down' }} sort-icon text-warning"></i>
                            @else
                                <i class="fas fa-sort sort-icon text-muted"></i>
                            @endif
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->age }}</td>
                            <td>{{ $user->city }}</td>
                            <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                <p class="text-muted">No users found matching your criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="pagination-wrapper">
                <div>
                    {{ $users->appends(request()->query())->links() }}
                </div>
                <div class="text-muted">
                    Page {{ $users->currentPage() }} of {{ $users->lastPage() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sorting JavaScript -->
    <script>
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
            
            // Remove page parameter when sorting
            currentUrl.searchParams.delete('page');
            
            // Navigate to new URL
            window.location.href = currentUrl.toString();
        }

        // Add hover effect to sortable headers
        document.addEventListener('DOMContentLoaded', function() {
            const sortableHeaders = document.querySelectorAll('.sortable-header');
            sortableHeaders.forEach(header => {
                header.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#e9ecef';
                });
                header.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                });
            });
        });
    </script>
</body>
</html>