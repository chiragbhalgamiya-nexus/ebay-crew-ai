<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EbayAPIController extends Controller
{
    /**
     * Endpoint: GET /api/listings
     * Returns paginated product listings with cost breakdown
     */
    public function getListings(Request $request): JsonResponse
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 20);
        $offset = ($page - 1) * $limit;

        $listings = [
            [
                'id' => 1,
                'title' => 'Premium Vintage Camera',
                'seller_id' => 101,
                'seller_name' => 'TechCollector',
                'base_price' => 299.99,
                'shipping_cost' => 15.50,
                'tax_amount' => 24.00,
                'platform_fee' => 18.99,
                'total_landed_cost' => 358.48,
                'trust_score' => 98.5,
                'recommendation_score' => 0.92
            ],
            [
                'id' => 2,
                'title' => 'Wireless Bluetooth Headphones',
                'seller_id' => 102,
                'seller_name' => 'ElectronicsHub',
                'base_price' => 89.99,
                'shipping_cost' => 8.00,
                'tax_amount' => 7.20,
                'platform_fee' => 5.99,
                'total_landed_cost' => 111.18,
                'trust_score' => 95.2,
                'recommendation_score' => 0.85
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $listings,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => 150
            ]
        ], 200);
    }

    /**
     * Endpoint: GET /api/listings/{id}
     * Returns detailed product information with full cost breakdown
     */
    public function getProductDetails($id): JsonResponse
    {
        $product = [
            'id' => $id,
            'title' => 'Premium Vintage Camera',
            'description' => 'Excellent condition professional camera',
            'seller_id' => 101,
            'seller_name' => 'TechCollector',
            'seller_trust_badge' => 'VERIFIED',
            'base_price' => 299.99,
            'cost_breakdown' => [
                'base_price' => 299.99,
                'shipping_cost' => 15.50,
                'import_duty' => 0.00,
                'sales_tax' => 24.00,
                'platform_fee' => 18.99,
                'discount' => 0.00,
                'total_landed_cost' => 358.48
            ],
            'seller_metrics' => [
                'trust_score' => 98.5,
                'positive_rating_pct' => 99.2,
                'response_time_avg_hours' => 2.5,
                'verification_level' => 'ADVANCED'
            ],
            'images' => ['img1.jpg', 'img2.jpg'],
            'in_stock' => true,
            'quantity_available' => 1
        ];

        return response()->json([
            'success' => true,
            'data' => $product
        ], 200);
    }

    /**
     * Endpoint: POST /api/listings
     * Creates a new product listing (seller endpoint)
     */
    public function createListing(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'base_price' => 'required|numeric|min:0.01',
            'shipping_method' => 'required|in:standard,express,overnight',
            'tax_category' => 'required|string',
            'quantity' => 'required|integer|min:1'
        ]);

        $listing = [
            'id' => 999,
            'seller_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'base_price' => $validated['base_price'],
            'shipping_method' => $validated['shipping_method'],
            'tax_category' => $validated['tax_category'],
            'quantity_available' => $validated['quantity'],
            'status' => 'ACTIVE',
            'created_at' => now()->toIso8601String(),
            'recommendation_score' => 0.0,
            'cost_breakdown' => [
                'base_price' => $validated['base_price'],
                'estimated_platform_fee' => $validated['base_price'] * 0.065,
                'estimated_total' => $validated['base_price'] * 1.065
            ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'Listing created successfully',
            'data' => $listing
        ], 201);
    }
}
