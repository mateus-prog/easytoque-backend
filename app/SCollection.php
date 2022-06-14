<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SCollection extends Collection {
	/**
	 * Retorna somente a propriedade de cada objeto
	 *
	 * @param  string $property
	 * @return array
	 */
	public function prop($property) {
		// SCollection vazia?
		if ($this->isEmpty()) {
			return [];
		}

		return $this->keyBy($property)->keys()->toArray();
	}

	/**
	 * Faz a collection ficar no formato passado
	 *
	 * @param  array $format
	 * @return \App\SCollection
	 */
	public function format(array $format) {
		// SCollection vazia?
		if ($this->isEmpty()) {
			return new SCollection;
		}

		// Chave da SCollection
		$propId = key($format);
		// Conteúdo de cada array
		$props  = is_array($format[$propId]) ? $format[$propId] : [$format[$propId]];

		return $this->keyBy($propId)
			->map(function($obj) use($props) {
				// Se for única, retorna sem array
				if (count($props) == 1) {
					return $obj->{end($props)};
				}

				// Propriedades a serem retornada em cada índice
				foreach ($props as &$prop) {
					$prop = $obj->{$prop};
				}

				return $props;
			});
	}
}
