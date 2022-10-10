<?php

namespace App\Helpers;

abstract class Format {
	
	public static function formatDate($date) {
		return date('d/m/Y', strtotime($date));
	}

	public static function formatDateTime($date) {
		return date('d/m/Y H:i', strtotime($date));
	}

	public static function formatDateToDB($date) {
		if ($d = date_create_from_format('d/m/Y', $date)) {
			return $d->format('Y-m-d');
		}

		if($d = date_create_from_format('d/m/Y H:i', $date)) {
			return $d->format('Y-m-d H:i');
		}

		return $date;
	}

	public static function valueBR($value){
		return number_format($value,2,",",".");
	}

	/**
	 * Formata uma string para um formato de telefone conhecido
	 *
	 * @param  string $number
	 * @return string
	 */
	public static function phone($number) {
		// Remove o que não for número
		$number = preg_replace('/\D/', '', $number);

		// Nenhum formato conhecido
		if (strlen($number) < 8) {
			return $number;
		}

		// Até 9 dígitos
		if (strlen($number) <= 9) {
			return preg_replace('/(\d{5})(\d*)/', '$1-$2', $number);
		}

		// Demais formatos
		return preg_replace('/(\d{2})(\d{5})(\d*)/', '($1) $2-$3', $number);
	}

	/**
	 * Formata uma string para um formato do CEP
	 *
	 * @param  string $number
	 * @return string
	 */
	public static function cep($number) {
		// Remove o que não for número
		$number = preg_replace('/\D/', '', $number);

		// Nenhum formato conhecido
		if (strlen($number) != 8) {
			return $number;
		}

		return preg_replace('/(\d{5})(\d*)/', '$1-$2', $number);
	}

	/**
	 * Coloca a string na formatação de CPF
	 *
	 * @param  string $number
	 * @return string
	 */
	public static function cpf($number) {
		return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $number);
	}

	/**
	 * Coloca a string na formatação de CNPJ
	 *
	 * @param  string $number
	 * @return string
	 */
	public static function cnpj($number) {
		return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $number);
	}

	public static function slugify($text, string $divider = '-'){
		// replace non letter or digits by divider
		$text = preg_replace('~[^\pL\d]+~u', $divider, $text);
	  
		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	  
		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);
	  
		// trim
		$text = trim($text, $divider);
	  
		// remove duplicate divider
		$text = preg_replace('~-+~', $divider, $text);
	  
		// lowercase
		$text = strtolower($text);
	  
		if (empty($text)) {
			return 'n-a';
		}
	  
		return $text;
	}
}