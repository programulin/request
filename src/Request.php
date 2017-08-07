<?php
namespace Programulin;

/**
  Класс для хранения информации о текущем запросе.
 */
class Request
{
	private $get = [];
	private $post = [];
	private $files = [];
	private $server = [];
	private $cookie = [];
	private $headers = [];
	private $url = [];

	public function __construct(
            array $get = null,
            array $post = null,
            array $files = null,
            array $server = null,
            array $cookie = null,
            array $headers = null)
	{
        $this->get = is_null($get) ? $_GET : $get;
		$this->post = is_null($post) ? $_POST : $post;
		$this->files = is_null($files) ? $_FILES : $files;
		$this->server = is_null($server) ? $_SERVER : $server;
		$this->cookie = is_null($cookie) ? $_COOKIE : $cookie;
		$this->headers = is_null($headers) ? getallheaders() : $headers;
		
		$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->url = array_filter(explode('/', trim($url, '/')), function($var){ return $var !== ''; });
	}

	/**
	 * Получение массива $_GET или одного из его элементов.
     * 
	 * @param string $element
	 * @return mixed Значение или null, если такого элемента нет.
	 */
	public function get($element = null)
	{
		return is_null($element) ? $this->get : $this->getElement($this->get, $element);
	}

	/**
	 * Получение массива $_POST или одного из его элементов.
     * 
	 * @param string $element
	 * @return mixed Значение или null, если такого элемента нет.
	 */
	public function post($element = null)
	{
		return is_null($element) ? $this->post : $this->getElement($this->post, $element);
	}

	/**
	 * Получение массива $_FILES или одного из его элементов.
     * 
	 * @param string $element
	 * @return mixed Значение или null, если такого элемента нет.
	 */
	public function files($element = null)
	{
		return is_null($element) ? $this->files : $this->getElement($this->files, $element);
	}

	/**
	 * Получение массива $_SERVER или одного из его элементов.
     * 
	 * @param string $element
	 * @return mixed Значение или null, если такого элемента нет.
	 */
	public function server($element = null)
	{
		return is_null($element) ? $this->server : $this->getElement($this->server, $element);
	}

	/**
	 * Получение массива $_COOKIE или одного из его элементов.
     * 
	 * @param string $element
	 * @return mixed Значение или null, если такого элемента нет.
	 */
	public function cookie($element = null)
	{
		return is_null($element) ? $this->cookie : $this->getElement($this->cookie, $element);
	}

	/**
	 * Получение массива заголовков или одного из его элементов.
     * 
	 * @param string $element
	 * @return mixed Значение или null, если такого элемента нет.
	 */
	public function headers($element = null)
	{
		return is_null($element) ? $this->headers : $this->getElement($this->headers, $element);
	}
	
	/**
	 * Получение URL или одного из его компонентов.
     * 
	 * @param string $element 
	 * @return mixed Значение или null, если такого элемента нет.
	 */
	public function url($element = null)
	{
		return is_null($element) ? $this->url : $this->getElement($this->url, $element);
	}
	
	/**
     * Получение URL в виде строки с GET-параметрами.
     * 
     * @return string
     */
	public function urlWithQuery()
	{
		return $this->server['REQUEST_URI'];
	}

	/**
     * Получение URL в виде строки, без GET-параметров.
     * 
     * @return string
     */
	public function urlWithoutQuery()
	{
		return parse_url($this->server['REQUEST_URI'], PHP_URL_PATH);
	}

	/**
     * Получение метода запроса.
     * 
     * return @string
     */
	public function method()
	{
		return $this->server['REQUEST_METHOD'];
	}

    /**
     * Проверка метода запроса.
     * 
     * @param string $method
     * @return bool
     */
    public function isMethod($method)
    {
        return $this->server['REQUEST_METHOD'] === mb_strtoupper($method);
    }
    
	/**
	 * Проверка наличия If-Modified-Since. Если пришёл - возвращается его значение, если нет - false.
	 * 
	 * @return mixed
	 */
	public function isModified()
	{
		if(isset($this->headers['If-Modified-Since']))
			return $this->headers['If-Modified-Since'];
		
		return false;
	}
	
	private function getElement($array, $element)
	{
		$keys = explode('.', $element);
		
		foreach($keys as $key)
		{
			if(!isset($array[$key]))
				return null;

			$array = $array[$key];
		}
		
		return $array;
	}
}