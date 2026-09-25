<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EBayListingController extends Controller
{
    /**
     * Get all listings with pagination and filtering
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getListings(Request $request): JsonResponse
    {
        $page = $request->query('page', 1);
        $limit = min($request->query('limit', 20), 100);
        $sellerConfidence = $request->query('seller_confidence');
        $priceMin = $request->query('price_min');
        $priceMax = $request->query('price_max');

        $listings = [
            [
                'id' => 1,
                'title' => 'Vintage iPhone 12 - Excellent Condition',
                'price' => 599.99,
                'seller_confidence' => 'Gold',
                'condition' => 'Like New',
                'image_urls' => ['https://cdn.example.com/listing1.jpg'],
                'active' => true
            ],
            [
                'id' => 2,
                'title' => 'MacBook Pro 16" 2023 M2',
                'price' => 1299.99,
                'seller_confidence' => 'Platinum',
                'condition' => 'New',
                'image_urls' => ['https://cdn.example.com/listing2.jpg'],
                'active' => true
            ]
        ];

        return response()->json([
            'status' => 'success',
            'data' => $listings,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => count($listings)
            ]
        ], 200);
    }

    /**
     * Get product details by listing ID
     * 
     * @param int $listingId
     * @return JsonResponse
     */
    public function getProductDetails(int $listingId): JsonResponse
    {
        $product = [
            'id' => $listingId,
            'title' => 'Premium Vintage Watch',
            'description' => 'Authentic Swiss-made watch from 1980s',
            'price' => 450.00,
            'seller_id' => 12345,
            'seller_confidence_score' => 4.8,
            'seller_confidence_tier' => 'Gold',
            'condition' => 'Used - Excellent',
            'category' => 'Watches & Jewelry',
            'image_urls' => [
                'https://cdn.example.com/watch1.jpg',
                'https://cdn.example.com/watch2.jpg'
            ],
            'shipping_speed' => '2-3 days',
            'return_policy' => '30-day returns accepted',
            'active' => true,
            'created_at' => '2026-09-20T10:30:00Z',
            'seller_response_time_hours' => 2
        ];

        return response()->json([
            'status' => 'success',
            'data' => $product
        ], 200);
    }

    /**
     * Create a new listing
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function createListing(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:80',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0.01',
            'condition' => 'required|string',
            'category_id' => 'required|integer',
            'image_urls' => 'array'
        ]);

        $listing = [
            'id' => rand(10000, 99999),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'condition' => $validated['condition'],
            'category_id' => $validated['category_id'],
            'image_urls' => $validated['image_urls'] ?? [],
            'seller_id' => auth()->id() ?? 1,
            'status' => 'pending_review',
            'created_at' => now(),
            'active' => false
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Listing created successfully',
            'data' => $listing
        ], 201);
    }
}
