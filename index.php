<?php

/**
 * 
 */
class Blockchain
{
	
	private $block;
	private $initial_key;

	function __construct()
	{
		$this->block = [];
		$this->setGenesisBlock();
	}

	private function setGenesisBlock()
	{
		$data = 'first block';
		$previus_hash = $this->getLastHash();

		$this->hashBlock(
			$data,
			$previus_hash
		);
	}

	private function hashBlock(String $data, String $previus_hash)
	{
		$hash = '';
		$once = 1;

		while (!$this->isValidHash($hash)) {
			$block = "{$data}:{$previus_hash}:{$once}" ;
			$hash = utf8_encode(hash('sha256', $block));
			$once++;
		}

		$this->setBlock($hash);
	}

	private function isValidHash(String $hash)
	{
		return substr($hash, 0, 2) === '00';
	}

	public function newBlock(String $data)
	{
		$previus_hash = $this->getLastHash();

		$this->hashBlock(
			$data,
			$previus_hash
		);
	}

	public function getBlock()
	{
		return $this->block;
	}

	public function getLastHash()
	{
		return end($this->block)['hash'] ?? str_repeat('0', 64);
	}

	public function setBlock(String $hash)
	{
		array_push($this->block, [
			'previus' 	=> $this->getLastHash(),
			'hash'		=> $hash
		]);
	}
}

$blockchain = new Blockchain();

$blockchain->newBlock('teste');

echo '<pre>';
var_dump($blockchain->getBlock());
