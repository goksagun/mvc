<?php namespace App;

/**
* Validation
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class Validation extends \Facade
{
	private $data;

	private $rules;

	private $messages;

	protected $defaults = [
		'required' => 'The :name field required.',
		'email' => 'The :name field must be valid email address.',
		'min' => 'The :name field must be greather than :min.',
		'max' => 'The :name field must be greather than :max.',
	];

	private $params = [];

	protected $errors = [];
	
	function __construct($data, $rules, $messages)
	{
		$this->data = $data;
		$this->rules = $rules;
		$this->messages = $messages;
	}

	public function make($data='', $rules=[], $messages=[])
	{
		$validation = new Validation($data, $rules, $messages);

		return $validation->validate();
	}

	public function validate()
	{
		foreach ($this->rules as $key => $value) {
			
			if (array_key_exists($key, $this->data)) {
				$parsedRules = explode('|', $value);

				foreach ($parsedRules as $rule) {
					$ruleSet = $this->setRule($rule);
					$params = array_merge([$key], $ruleSet['params']);

					if (call_user_method_array($ruleSet['rule'], $this, $params)) {
						if ( ! array_key_exists($key, $this->errors)) {
							$this->errors[$key] = $this->setMessage($key, $ruleSet['rule']);
						}
					}
				}
			}	
		}

		return $this;
	}

	public function fails()
	{
		return ! empty($this->errors);
	}

	public function passes()
	{
		return empty($this->errors);
	}

	public function errors($key='')
	{
		return $this->errors;
	}

	public function setRule($rule='')
	{
		$parsedRule = explode(':', $rule);
		// $parsedRuleKeyValues = explode(':', 'max:6');
        // $parsedRuleKeyValues = explode(':', 'betwen:6,16');
        // $parsedRuleKeyValues = explode(':', 'required');
        $method = $parsedRule[0]; 
        $params = isset($parsedRule[1]) ? explode(',', $parsedRule[1]) : [];

        // return array_merge([$ruleKey], $ruleValues); 
        return array(
        	'rule' => $method,
        	'params' => $params,
    	); 
	}

	public function setMessage($key='', $rule='')
	{
		if (isset($this->messages[$key][$rule])) {
			$message = $this->messages[$key][$rule];
		} else {
			$message = preg_replace('/:(\w+)/', $key, $this->defaults[$rule], 1);
		}
		return $message;
	}

	public function required($key='')
	{
		return $this->data[$key] == '';
	}

	public function email($key='')
	{
		return ! filter_var($this->data[$key], FILTER_VALIDATE_EMAIL);
	}

	public function min($key='', $min=0)
	{
		// dd($key, $min);
		return strlen($this->data[$key]) < $min;
	}
}