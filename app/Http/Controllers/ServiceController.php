<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Service;

class ServiceController extends Controller
{
    /**
     * Get all services for the modal
     * This method returns services that can be searched and selected
     */
    public function getServices(Request $request): JsonResponse
    {
        $query = Service::active();

        // If search term is provided, filter the services
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        $services = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'services' => $services->toArray()
        ]);
    }

    /**
     * Get specific services by IDs
     * This method is used to get selected services details
     */
    public function getSelectedServices(Request $request): JsonResponse
    {
        $serviceIds = $request->input('service_ids', []);
        
        $selectedServices = Service::whereIn('id', $serviceIds)
            ->active()
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'services' => $selectedServices->toArray()
        ]);
    }
}