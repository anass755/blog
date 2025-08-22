<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Agency Management - DEMO MODE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .demo-badge {
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
            margin-left: 10px;
        }
        .service-modal {
            max-width: 600px;
        }
        .service-item {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .service-item:hover {
            background-color: #f8f9fa;
            border-color: #007bff;
        }
        .service-item.selected {
            background-color: #e3f2fd;
            border-color: #007bff;
        }
        .service-checkbox {
            margin-right: 10px;
        }
        .service-name {
            font-weight: 600;
            color: #333;
        }
        .service-description {
            color: #666;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .service-price {
            color: #28a745;
            font-weight: bold;
            font-size: 0.9em;
        }
        .service-category {
            background: #6c757d;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75em;
        }
        .selected-services-input {
            min-height: 80px;
            resize: vertical;
        }
        .search-box {
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            margin-bottom: 15px;
        }
        .services-container {
            max-height: 400px;
            overflow-y: auto;
        }
        .no-services {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        .loading {
            text-align: center;
            padding: 20px;
        }
        .demo-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- Demo Info Banner -->
        <div class="demo-info">
            <h5><i class="fas fa-flask"></i> DEMO MODE <span class="demo-badge">No Database Required!</span></h5>
            <p class="mb-0">This is a fully functional demo. All data is simulated - no migrations needed. Perfect for testing before your senior confirms the database schema!</p>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Agency Management <span class="demo-badge">DEMO</span></h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#serviceModal">
                            <i class="fas fa-plus"></i> Add Service
                        </button>
                    </div>
                    <div class="card-body">
                        <p>Click the "Add Service" button to select services for your agency. This demo includes 8 sample services across different categories.</p>
                        
                        <!-- Display current selected services -->
                        <div id="currentServices" class="mt-3">
                            <h6>Currently Selected Services:</h6>
                            <div id="selectedServicesList" class="text-muted">
                                No services selected yet.
                            </div>
                        </div>

                        <!-- Demo Statistics -->
                        <div id="demoStats" class="mt-4">
                            <h6>Demo Statistics:</h6>
                            <div id="statsDisplay" class="text-muted">
                                Loading statistics...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Selection Modal -->
    <div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg service-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="serviceModalLabel">
                        <i class="fas fa-search"></i> Select Services <span class="demo-badge">DEMO</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Search Field -->
                    <div class="search-box">
                        <div class="mb-3">
                            <label for="serviceSearch" class="form-label">Search Services</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="serviceSearch" 
                                       placeholder="Try searching: web, design, marketing..." autocomplete="off">
                            </div>
                        </div>

                        <!-- Selected Services Display (Readonly) -->
                        <div class="mb-3">
                            <label for="selectedServicesDisplay" class="form-label">Selected Services</label>
                            <textarea class="form-control selected-services-input" id="selectedServicesDisplay" 
                                      readonly placeholder="Selected services will appear here..."></textarea>
                        </div>
                    </div>

                    <!-- Services List Container -->
                    <div class="services-container">
                        <div id="servicesLoading" class="loading">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading demo services...</p>
                        </div>
                        <div id="servicesList"></div>
                        <div id="noServicesFound" class="no-services" style="display: none;">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <p>No services found matching your search.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmSelection">
                        <i class="fas fa-check"></i> Confirm Selection
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        $(document).ready(function() {
            let selectedServices = [];
            let allServices = [];
            let searchTimeout;

            // Setup CSRF token for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Load demo statistics on page load
            loadDemoStatistics();

            // Load services when modal opens
            $('#serviceModal').on('show.bs.modal', function() {
                loadServices();
            });

            // Search functionality with debounce
            $('#serviceSearch').on('input', function() {
                clearTimeout(searchTimeout);
                const searchTerm = $(this).val().trim();
                
                searchTimeout = setTimeout(function() {
                    searchServices(searchTerm);
                }, 300);
            });

            // Load demo statistics
            function loadDemoStatistics() {
                $.get('/api/demo/statistics')
                    .done(function(response) {
                        if (response.success) {
                            const stats = response.statistics;
                            const statsHtml = `
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card text-center bg-primary text-white">
                                            <div class="card-body py-2">
                                                <h5>${stats.total_services}</h5>
                                                <small>Total Services</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card text-center bg-success text-white">
                                            <div class="card-body py-2">
                                                <h5>${stats.enabled_services}</h5>
                                                <small>Enabled</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card text-center bg-info text-white">
                                            <div class="card-body py-2">
                                                <h5>${stats.categories}</h5>
                                                <small>Categories</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card text-center bg-warning text-white">
                                            <div class="card-body py-2">
                                                <h5>$${stats.average_price.toFixed(0)}</h5>
                                                <small>Avg Price</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $('#statsDisplay').html(statsHtml);
                        }
                    })
                    .fail(function() {
                        $('#statsDisplay').html('<span class="text-danger">Failed to load statistics</span>');
                    });
            }

            // Load all services (DEMO VERSION)
            function loadServices() {
                $('#servicesLoading').show();
                $('#servicesList').empty();
                $('#noServicesFound').hide();

                $.get('/api/demo/services') // Using demo endpoint
                    .done(function(response) {
                        if (response.success) {
                            allServices = response.services;
                            displayServices(allServices);
                            console.log('Demo mode: Loaded', response.total_count, 'services');
                        } else {
                            showError('Failed to load demo services');
                        }
                    })
                    .fail(function() {
                        showError('Error loading demo services');
                    })
                    .always(function() {
                        $('#servicesLoading').hide();
                    });
            }

            // Search services (DEMO VERSION)
            function searchServices(searchTerm) {
                $('#servicesLoading').show();
                $('#servicesList').empty();
                $('#noServicesFound').hide();

                const url = searchTerm ? `/api/demo/services?search=${encodeURIComponent(searchTerm)}` : '/api/demo/services';

                $.get(url)
                    .done(function(response) {
                        if (response.success) {
                            displayServices(response.services);
                            console.log('Demo search:', response.search_term, '- Found', response.total_count, 'services');
                        } else {
                            showError('Failed to search demo services');
                        }
                    })
                    .fail(function() {
                        showError('Error searching demo services');
                    })
                    .always(function() {
                        $('#servicesLoading').hide();
                    });
            }

            // Display services in the modal
            function displayServices(services) {
                const $servicesList = $('#servicesList');
                $servicesList.empty();

                if (services.length === 0) {
                    $('#noServicesFound').show();
                    return;
                }

                services.forEach(function(service) {
                    const isSelected = selectedServices.some(s => s.id === service.id);
                    const serviceHtml = `
                        <div class="service-item ${isSelected ? 'selected' : ''}" data-service-id="${service.id}">
                            <div class="d-flex align-items-start">
                                <input type="checkbox" class="form-check-input service-checkbox" 
                                       ${isSelected ? 'checked' : ''} data-service-id="${service.id}">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="service-name">${escapeHtml(service.name)}</div>
                                        <div class="d-flex gap-2">
                                            <span class="service-category">${escapeHtml(service.category)}</span>
                                            <span class="service-price">$${parseFloat(service.price).toFixed(2)}</span>
                                        </div>
                                    </div>
                                    <div class="service-description">${escapeHtml(service.description || '')}</div>
                                </div>
                            </div>
                        </div>
                    `;
                    $servicesList.append(serviceHtml);
                });

                attachServiceHandlers();
            }

            // Attach event handlers to service items
            function attachServiceHandlers() {
                $('.service-item').off('click').on('click', function(e) {
                    if ($(e.target).hasClass('service-checkbox')) return;
                    
                    const $checkbox = $(this).find('.service-checkbox');
                    $checkbox.prop('checked', !$checkbox.prop('checked')).trigger('change');
                });

                $('.service-checkbox').off('change').on('change', function() {
                    const serviceId = parseInt($(this).data('service-id'));
                    const isChecked = $(this).is(':checked');
                    const $serviceItem = $(this).closest('.service-item');

                    if (isChecked) {
                        const service = allServices.find(s => s.id === serviceId);
                        if (service && !selectedServices.some(s => s.id === serviceId)) {
                            selectedServices.push(service);
                        }
                        $serviceItem.addClass('selected');
                    } else {
                        selectedServices = selectedServices.filter(s => s.id !== serviceId);
                        $serviceItem.removeClass('selected');
                    }

                    updateSelectedServicesDisplay();
                });
            }

            // Update the selected services display textarea
            function updateSelectedServicesDisplay() {
                const $display = $('#selectedServicesDisplay');
                
                if (selectedServices.length === 0) {
                    $display.val('No services selected');
                } else {
                    const serviceNames = selectedServices.map(service => `${service.name} ($${parseFloat(service.price).toFixed(2)})`);
                    $display.val(serviceNames.join(', '));
                }
            }

            // Confirm selection and close modal
            $('#confirmSelection').on('click', function() {
                updateMainPageDisplay();
                $('#serviceModal').modal('hide');
                showSuccess(`${selectedServices.length} service(s) selected successfully! (Demo Mode)`);
            });

            // Update main page with selected services
            function updateMainPageDisplay() {
                const $selectedList = $('#selectedServicesList');
                
                if (selectedServices.length === 0) {
                    $selectedList.html('<span class="text-muted">No services selected yet.</span>');
                } else {
                    let html = '<div class="row">';
                    selectedServices.forEach(function(service) {
                        html += `
                            <div class="col-md-6 mb-2">
                                <div class="card card-body py-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">${escapeHtml(service.name)}</h6>
                                            <small class="text-muted">${escapeHtml(service.description || '')}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="service-category">${escapeHtml(service.category)}</span>
                                            <div class="service-price mt-1">$${parseFloat(service.price).toFixed(2)}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    $selectedList.html(html);
                }
            }

            // Utility functions
            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function showSuccess(message) {
                alert('✅ ' + message);
            }

            function showError(message) {
                alert('❌ ' + message);
            }

            // Reset modal when closed
            $('#serviceModal').on('hidden.bs.modal', function() {
                $('#serviceSearch').val('');
                updateSelectedServicesDisplay();
            });
        });
    </script>
</body>
</html>