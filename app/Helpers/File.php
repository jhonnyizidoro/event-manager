<?php

namespace App\Helpers;

use File as LaravelFile;
use Storage;
use Input;

class File
{
	/**
	* Faz o upload de um arquivo
	* @param RequestFile arquivo da requisição
	* @param String o segundo parâmetro é a pasta que o arquivo vai ficar, por default é images, pois é o mais usado
	* @return String localização do arquivo no storage
	*/
	public static function upload($file, $directory)
	{
		if (!$file) {
			return $file;
		}	
		$name = self::sanitizeFileName($file->getClientOriginalName());
		$name = "{$directory}/".date("Y-m-d_H-i-s_").$name;
		$file = LaravelFile::get($file);
		Storage::put($name, $file);
		return $name;
	}

	/**
	* Faz o upload de um arquivo em base64
	* @param String $file é a String no formato base64 URL -> data:image/png;base64,........
	* @param String o segundo parâmetro é a pasta que o arquivo vai ficar, por default é images, pois é o mais usado
	* @return String localização do arquivo no storage
	*/
	public static function uploadBase64($file, $directory)
	{
		if (!$file) {
			return $file;
		}
		$explode = explode(',', $file);
		$format = str_replace(['data:image/', ';', 'base64'], ['', '', ''], $explode[0]);
		$file = base64_decode($explode[1]);
		$name = "{$directory}/".date("Y-m-d_H-i-s_").uniqid().'.'.$format;
		Storage::put($name, $file);
		return $name;
	}
	
	/**
	* Converte o nome do arquivo arquivo para um nome sem caracteres especiais e espaços
	* @param String nome do arquivo com extensão
	* @return String nome do arquivo com extensão
	*/
	public static function sanitizeFileName($filename)
	{
		//Separar o nome e a extensão do arquivo
		$name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
		$array = explode('.', $filename);
		$extension = end($array);
		//Modificar o nome do arquivo para tirar caracteres especiais
		$name = self::slugify($name);
		return $name.'.'.$extension;
	}
	
	/**
	* Converte uma string em um formato que seja uma URL browser friendly
	* @param String texto original
	* @return String text convertido
	*/
	public static function slugify($text)
	{
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		$text = preg_replace('~[^-\w]+~', '', $text);
		$text = trim($text, '-');
		$text = preg_replace('~-+~', '-', $text);
		$text = strtolower($text);
		return $text;
	}
	
	/**
	* Gera uma string aleatória com os carecteres setados
	* @param int recebe o tamanho da String, por default é 8
	* @return String retorn a string gerada
	*/
	public static function generateString($length = 8) 
	{
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$count = mb_strlen($chars);
		for ($i = 0, $result = ''; $i < $length; $i++) {
			$index = rand(0, $count - 1);
			$result .= mb_substr($chars, $index, 1);
		}
		return $result;
	}
}