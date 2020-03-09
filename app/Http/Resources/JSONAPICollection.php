<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\MissingValue;

class JSONAPICollection extends ResourceCollection
{
    public $collects = JSONAPIResource::class;

    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'included' => $this->mergeIncludedRelations($request),
        ];
    }

    private function mergeIncludedRelations($request) {
        $includes = $this
                        ->collection
                        ->flatMap
                        ->included($request)
                        ->unique()
                        ->values();

        return $includes->isNotEmpty() ? $includes : new MissingValue();
    }
}
