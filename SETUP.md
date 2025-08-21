# Agency Service Selection Setup Guide

## Overview
This implementation provides a complete service selection modal for your agency with search functionality using Laravel and jQuery.

## Features Implemented

### 1. **Service Selection Modal**
- Clean, responsive modal interface
- Bootstrap 5 styling with custom CSS
- Font Awesome icons for better UX

### 2. **Search Functionality**
- Real-time search with 300ms debounce
- Searches both service name and description
- AJAX-powered with loading states

### 3. **Service Selection**
- Checkbox-based selection
- Click anywhere on service item to select
- Visual feedback for selected services
- Readonly input field showing selected services

### 4. **Laravel Backend**
- `ServiceController` with two main methods:
  - `getServices()` - Returns all/filtered services
  - `getSelectedServices()` - Returns specific services by IDs
- `Service` model with search and active scopes
- Database migration for services table
- Sample data seeder

## Files Created

### Controllers
- `app/Http/Controllers/ServiceController.php`

### Models  
- `app/Models/Service.php`

### Views
- `resources/views/agency/index.blade.php`

### Database
- `database/migrations/2024_01_01_000001_create_services_table.php`
- `database/seeders/ServiceSeeder.php`

### Routes
- `routes/web.php`

## Setup Instructions

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Sample Data
```bash
php artisan db:seed --class=ServiceSeeder
```

### 3. Access the Page
Visit: `http://your-domain/agency`

## How It Works

### Frontend (jQuery)
1. **Modal Opening**: When "Add Service" button is clicked, modal opens and loads services
2. **Search**: User types in search box → debounced AJAX call → filtered results displayed
3. **Selection**: User clicks service items or checkboxes → updates selected array → updates readonly input
4. **Confirmation**: User clicks "Confirm Selection" → updates main page display

### Backend (Laravel)
1. **GET /api/services**: Returns all active services (with optional search parameter)
2. **POST /api/services/selected**: Returns specific services by IDs (for future use)

## Key jQuery Functions

### `loadServices()`
- Loads all services when modal opens
- Shows loading spinner during AJAX call

### `searchServices(searchTerm)`
- Performs search with debouncing
- Updates service list based on search results

### `displayServices(services)`
- Renders service list in modal
- Handles empty states and selection states

### `updateSelectedServicesDisplay()`
- Updates the readonly textarea with selected service names
- Called whenever selection changes

## Customization Options

### 1. **Styling**
- Modify CSS in the `<style>` section of `agency/index.blade.php`
- Colors, spacing, and animations can be easily adjusted

### 2. **Service Data**
- Update `ServiceSeeder.php` to add your actual services
- Modify `Service` model if you need additional fields

### 3. **Search Logic**
- Extend the `search` scope in `Service` model for more complex searches
- Add additional filters in the controller

### 4. **UI Enhancements**
- Replace `alert()` calls with toast notifications
- Add service categories or tags
- Implement pagination for large service lists

## Dependencies
- Laravel (any recent version)
- Bootstrap 5.1.3 (CDN)
- jQuery 3.6.0 (CDN)
- Font Awesome 6.0.0 (CDN)

## Browser Compatibility
- Modern browsers (Chrome, Firefox, Safari, Edge)
- IE11+ (with polyfills if needed)

## Performance Considerations
- Search debouncing prevents excessive AJAX calls
- Services are cached in JavaScript after first load
- Minimal DOM manipulation for better performance

This implementation provides a solid foundation that you can extend based on your specific requirements!