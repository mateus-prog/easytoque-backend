<?php

namespace App\Traits;

use App\SCollection;
use Illuminate\Support\Collection;

trait TraitCollection {
	/**
	 * Substitui a \Illuminate\Support\Collection por \App\SCollection
	 *
	 * @param  array $models
	 * @return \App\SCollection
	 */
	public function newCollection(array $models = []) {
		return new SCollection($models);
    }

	/**
	 * Retorna somente a propriedade de cada objeto
	 *
	 * @param  string $property
	 * @return array
	 */
	public static function prop($property) {
		return self::get()->prop($property);
	}

	/**
	 * Deixa a collection ficar no formato passado
	 *
	 * @param  array $format
	 * @return \App\SCollection
	 */
	public static function format($format) {
		return self::get()->format($format);
	}
}
