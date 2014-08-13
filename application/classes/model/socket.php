<?php
class model_Socket extends model
{
	protected $config = array(
		'host'			=> 'localhost',
		'protocol'		=> 'tcp',
		'port'			=> 80,
		'timeout'		=> 10,
		'persistent'	=> false,
	);
	private $connection = null;
	private $connected = false;
	private $error = array();

        public function set($key, $value){
            $this->config[$key] = $value;
            return $this;
        }
	/**
	 * @return object
	 */
	public function connect()
	{
		if ($this->connection != null) $this->disconnect();

		if ($this->config['persistent'] == true)
		{
			$tmp = null;
			$this->connection = @pfsockopen($this->config['host'], $this->config['port'], $errNum, $errStr, $this->config['timeout']);
		}
		else
		{
			$this->connection = fsockopen($this->config['host'], $this->config['port'], $errNum, $errStr, $this->config['timeout']);
		}

		if (!empty($errNum) || !empty($errStr))
		{
			$this->error = array('errorStr'=>$errStr,'errorNum'=>$errNum);
		}

		$this->connected = is_resource($this->connection);

		return $this->connected;
	}

	/**
	 * @return string
	 */
	public function error()
	{
		return $this->error;
	}

	/**
	 * @param string $data
	 * @return boolean
	 */
	public function write($data)
	{
		if (!$this->connected)
		{
			if (!$this->connect()) return false;
		}
		return fwrite($this->connection, $data, strlen($data));
	}

	/**
	 * @param integer $length
	 */
	public function read($length=1024)
	{
		if (!$this->connected)
		{
			if (!$this->connect()) return false;
		}

		if (!feof($this->connection)) return fread($this->connection, $length);
		else return false;
	}

	/**
	 * @return boolean
	 */
	public function disconnect()
	{
		if (!is_resource($this->connection))
		{
			$this->connected = false;
			return true;
		}
		$this->connected = !fclose($this->connection);

		if (!$this->connected)
		{
			$this->connection = null;
		}
		return !$this->connected;
	}

 	public function __destruct()
 	{
 		$this->disconnect();
 	}
}