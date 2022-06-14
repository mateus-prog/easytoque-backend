<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait TraitSeeder
{
    public function after()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function before()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    }

    public function smartySeeder($model, $dados)
    {
        $this->before();

        $first = $model->first();

        if ($first) {
            $first->forceDelete();
        }

        foreach ($dados as $dado) {
            if (isset($dado['id'])) {
                $model::updateOrCreate(['id' => $dado['id']], $dado);
            } else {
                $model::updateOrCreate($dado, $dado);
            }
        }

        $this->after();
    }
}
