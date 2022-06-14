<?php

namespace App\Traits;

use App\SBuilder;
use Illuminate\Support\Collection;

trait TraitBuilder {
	/**
	 * Substitui a \Illuminate\Database\Query\Builder por \App\SBuilder
	 *
	 * @param  Illuminate\Database\Query\Builder $query
	 * @return \App\SBuilder
	 */
	public function newEloquentBuilder($query) {
		return new SBuilder($query);
    }
}
