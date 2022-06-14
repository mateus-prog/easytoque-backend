<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SBuilder extends Builder {

	/**
	 * Retorna somente a propriedade de cada objeto
	 *
	 * @param  string $property
	 * @return array
	 */
	public function prop($property) {
		return $this->get()->prop($property);
	}

	/**
	 * Faz a collection ficar no formato passado
	 *
	 * @param  array $format
	 * @return \App\SCollection
	 */
	public function format(array $format) {
		return $this->get()->format($format);
	}
}
