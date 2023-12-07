<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Product;

class FetchProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch products from DummyJSON API. It will execute on every two hours interval.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $records = [];
            $limit = 10;
            for ($page = 1; $page <= 3; $page++) {
                $response = Http::get('https://dummyjson.com/products', [
                    'skip' => ($page - 1) * $limit,
                    'limit' => $limit
                ]);

                if ($response->getStatusCode() === 200) {
                    $products = json_decode($response->getBody(), true);
                    if (isset($products['products']) && is_array($products['products'])) {
                        foreach ($products['products'] as $productData) {
                            $records[] = [
                                'id' => $productData['id'],
                                'title' => $productData['title'],
                                'description' => $productData['description'],
                                'price' => $productData['price'],
                                'discountPercentage' => $productData['discountPercentage'],
                                'rating' => $productData['rating'],
                                'stock' => $productData['stock'],
                                'brand' => $productData['brand'],
                                'category' => $productData['category'],
                                'thumbnail' => $productData['thumbnail'],
                                'images' => json_encode($productData['images'])
                            ];
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Handle API request failure
            $this->error('Failed to fetch products. Check API connection.');
            $this->error($e->getMessage());
            exit;
        }

        if (!empty($records)) {
            try {
                Product::upsert(
                    $records,
                    ['id'],
                    ['id', 'title', 'description', 'price', 'discountPercentage', 'rating', 'brand', 'stock', 'brand', 'category', 'thumbnail', 'images']
                );

                $this->info('Products fetched successfully.');
            } catch (\Exception $e) {
                $this->error('Failed to insert/update products.');
                $this->error($e->getMessage());
            }
        } else {
            $this->info('There are no products to insert/update.');
        }

    }
}
