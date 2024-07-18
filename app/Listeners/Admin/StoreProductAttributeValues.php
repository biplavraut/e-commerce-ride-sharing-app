<?php

namespace App\Listeners\Admin;

use App\Events\Admin\ProductStored;
use App\Services\AttributeValueService;
use App\Services\ProductAttributeService;
use App\Services\ProductVarietyService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreProductAttributeValues
{
    /**
     * @var ProductAttributeService
     */
    private $productAttributeService;
    /**
     * @var AttributeValueService
     */
    private $attributeValueService;
    /**
     * @var ProductVarietyService
     */
    private $productVarietyService;

    /**
     * Create the event listener.
     *
     * @param ProductAttributeService $productAttributeService
     * @param AttributeValueService $attributeValueService
     * @param ProductVarietyService $productVarietyService
     */
    public function __construct(ProductAttributeService $productAttributeService, AttributeValueService $attributeValueService, ProductVarietyService $productVarietyService)
    {
        $this->productAttributeService = $productAttributeService;
        $this->attributeValueService   = $attributeValueService;
        $this->productVarietyService   = $productVarietyService;
    }

    /**
     * Handle the event.
     *
     * @param  ProductStored $event
     * @return void
     */
    public function handle(ProductStored $event)
    {
        $this->storeProductVarieties($event->varieties(), $event->attributesAndValues());

        /*foreach ($event->attributesAndValuesModified() as $attributeAndValue) {
            $attribute = $this->storeAttribute(array_keys($attributeAndValue)[0]);

            $attrValueIds[] = $this->storeAttributeValue($attribute, array_values($attributeAndValue)[0]);
        }*/
    }

    private function storeProductVarieties($varieties, $attributeAndValues): void
    {
        foreach ($varieties as $key => $variety) {
            $productVariety = $this->productVarietyService->store($variety);

            $valueIds = $this->storeAttributesAndValues($attributeAndValues[$key]);

            $productVariety->attributeValues()->sync($valueIds);
        }
    }

    private function storeAttributesAndValues($attributesAndValues): array
    {
        foreach ($attributesAndValues as $attr => $val) {
            $attribute = $this->storeAttribute($attr);

            $value = $this->storeAttributeValue($attribute, $val);

            $valueIds[] = $value->id;
        }

        return $valueIds ?? [];
    }

    private function storeAttribute($attribute)
    {
        $attr = $this->productAttributeService->findBy('name', $attribute);
        if (!$attr) {
            $attr = $this->productAttributeService->store([
                'name' => $attribute,
            ]);
        }

        return $attr;
    }

    private function storeAttributeValue($attribute, $value)
    {
        $attributeValue = $this->attributeValueService->exists($attribute->id, $value);
        if (!$attributeValue) {
            $attributeValue = $this->attributeValueService->store([
                'attribute_id' => $attribute->id,
                'value'        => $value,
            ]);
        }

        return $attributeValue;
    }
}
