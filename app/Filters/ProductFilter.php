<?php

namespace App\Filters;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ProductFilter
{

    protected Request $request;
    protected Builder $query;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query):Builder
    {
        $this->query = $query;

        foreach ($this->filters() as $filter => $value)
        {
            if (method_exists($this, $filter))
            {
                $this->$filter($value);
            }
        }

        return $this->query;
    }

    public function filters():array
    {
        return $this->request->only([
            'search',
            'category_id',
            'price_from',
            'price_to',
            'in_stock',
            'sort',
        ]);
    }

    protected function search(string $value):void
    {
        $this->query->where('name', 'like', '%'. $value . '%');
    }

    protected function category_id(int $value):void
    {
        $this->query->where('category_id', $value);
    }

    protected function price_from(int $value):void
    {
        $this->query->where('price', '>=', $value);
    }

    protected function price_to(int $value):void
    {
        $this->query->where('price', '<=', $value);
    }

    protected function in_stock(bool $value):void
    {
        if ($value)
        {
            $this->query->where('stock', '>', 0);
        }
    }

    protected function sort(string $value):void
    {
        match ($value)
        {
            'price_asc' => $this->query->orderBy('price', 'asc'),
            'price_desc' => $this->query->orderBy('price', 'desc'),
            default => $this->query->latest(),
        };
    }


}
