<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    use HasFactory;

    protected $fillable = ['status_demand'];

    public function saveDemand($data)
    {
        \DB::beginTransaction();
        $demand = Demand::create(['status_demand' => true]);
        $demand_products = [];

        foreach ($data as $key => $product_demands) {
            $demand_products[$key]['id_product'] = $product_demands['id_product'];
            $demand_products[$key]['id_demand'] = $demand->id;
        }

        \DB::table('demand_products')->insert($demand_products);

        \DB::commit();

        return $demand;
    }

}
