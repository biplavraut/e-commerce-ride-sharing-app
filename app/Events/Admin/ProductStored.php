<?php

namespace App\Events\Admin;

use App\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProductStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Product
     */
    public $product;
    /**
     * @var array
     */
    private $data;

    /**
     * Create a new event instance.
     *
     * @param $product
     * @param array $data
     */
    public function __construct($product, array $data)
    {
        $this->product = $product;
        $this->data    = $data;
    }

    public function attributesAndValues(): array
    {
        return $this->data['attributesAndValues'] ?? [];
    }

    public function attributesAndValuesModified(): array
    {
        $returnData = [];

        foreach ($this->data['attributesAndValues'] ?? [] as $attributesAndValue) {
            foreach ($attributesAndValue as $key => $value) {
                $returnData[] = [
                    trim($key) => trim($value),
                ];
            }
        }

        return $returnData;
    }

    public function varieties(): array
    {
        return array_map(function ($value) {
            $value['product_id'] = $this->product->id;

            return $value;
        }, $this->data['varieties'] ?? []);
    }
}
