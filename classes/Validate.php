<?php 
class Validate {
	private $_passed = false,
	$_error = array(),
	$_db	= null;

	public function __construct(){
		$this->_db =DB::getInstance();		
	}

	public function cek($source, $items=array()){
		foreach ($items as $item => $rules) {
			
			foreach ($rules as $rule => $rule_value) {
				$value = trim($source[$item]);
				$item = escape($item);

				if($rule==='required' && empty($value)){
					$this->addError("{$item} is required");
				} else if(!empty($value)){
					switch ($rule) {
						case 'min':
							if(strlen($value)<$rule_value){
								$this->addError("{$item} minimal harus kurang dari {$rule_value}");
							}
							break;

						case 'max':
							if(strlen($value)>$rule_value){
								$this->addError("{$item} maksimal adalah {$rule_value}");
							}
							break;

						case 'matches':
							if($value != $source[$rule_value]){
								$this->addError("{$rule_value} harus sama sama dengan {$item}");
							}
							break;

						case 'unique':
							$cek = $this->_db->get($rule_value, array($item,'=',$value));
						

							if($cek->count()){
								$this->addError("{$item} sudah ada, silahkan gunakan yang lain.");
							}
							break;

						default:
							# code...
							break;
					}
				}
			}
		}

		if(empty($this->_error)){
			$this->_passed=true;
		}

		return $this;
	}

	private function addError($error){
		$this->_error[] = $error;
	}

	public function errors(){
		return $this->_error;
	}

	public function passed(){
		return $this->_passed;
	}
}