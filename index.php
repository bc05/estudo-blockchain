<?php

/**
 * 
 */
class Blockchain
{
	
	private $block = [];

	private function hashBlock(Array $data, String $previus_hash)
	{
		$hash = '';
		$once = 1;
		$data_json = json_encode($data, true);

		while (!$this->isValidHash($hash)) {
			$block = "{$data_json}:{$previus_hash}:{$once}" ;
			$hash = utf8_encode(hash('sha256', $block));
			$once++;
		}

		$this->setBlock($hash, $data, $once);
	}

	private function isValidHash(String $hash)
	{
		return preg_match('/^00e/', $hash);
	}

	public function newBlock(Array $data)
	{		
		$previus_hash = $this->getLastHash();

		$this->hashBlock(
			$data,
			$previus_hash
		);
	}

	private function getLastHash()
	{
		return end($this->block)['hash'] ?? str_repeat('0', 64);
	}

	public function getBlock()
	{
		return $this->block;
	}

	private function setBlock(String $hash, Array $data, int $once)
	{
		array_push($this->block, [
			'previus' 	=> $this->getLastHash(),
			'hash'		=> $hash,
			'data'		=> $data,
			'once'		=> $once
		]);
	}
}

$blockchain = new Blockchain();

$blockchain->newBlock(
	[
		'cpf' 			=> 11981400036,
		'name' 			=> 'Odilon Garcez',
		'rg' 			=> '314003113',
		'birth_date' 	=> '1951-11-04'
	]
);

$blockchain->newBlock(
	[
		'cpf' 			=>	3775527559,
		'name' 			=> 'Silas Vasconcelos',
		'rg' 			=> '001001001001',
		'birth_date' 	=> '1990-06-20'
	]
);

echo '<pre>';

print_r(
	$blockchain->getBlock()
);
