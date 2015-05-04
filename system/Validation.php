<?php namespace App;

/**
* Validation
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class Validation extends \Facade
{
    /**
     * @var
     */
    private $data;

    /**
     * @var
     */
    private $rules;

    /**
     * @var
     */
    private $messages;

    /**
     * @var array
     */
    private $params = [];

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var array
     */
    protected $defaults = [
		'required' => 'The :name field required.',
		'email' => 'The :name field must be valid email address.',
		'min' => 'The :name field must be greather than :min.',
		'max' => 'The :name field must be greather than :max.',
	];

    /**
     * @param $data
     * @param $rules
     * @param $messages
     */
    function __construct($data, $rules, $messages)
	{
		$this->data = $data;
		$this->rules = $rules;
		$this->messages = $messages;
	}

    /**
     * @param string $data
     * @param array $rules
     * @param array $messages
     * @return Validation
     */
    public function make($data='', $rules=[], $messages=[])
	{
		$validation = new Validation($data, $rules, $messages);

		return $validation->validate();
	}

    /**
     * @return $this
     */
    public function validate()
	{
		foreach ($this->rules as $key => $value) {
			
			if (array_key_exists($key, $this->data)) {
				$parsedRules = explode('|', $value);

				foreach ($parsedRules as $rule) {
					$ruleSet = $this->setRule($rule);
					$params = array_merge(['key' => $key], $ruleSet['params']);

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

    /**
     * @return bool
     */
    public function fails()
	{
		return ! empty($this->errors);
	}

    /**
     * @return bool
     */
    public function passes()
	{
		return empty($this->errors);
	}

    /**
     * @param string $key
     * @return array
     */
    public function errors($key='')
	{
		return $this->errors;
	}

    /**
     * @param string $rule
     * @return array
     */
    public function setRule($rule='')
	{
		$parsedRule = explode(':', $rule);
        $method = $parsedRule[0]; 
        $params = isset($parsedRule[1]) ? explode(',', $parsedRule[1]) : [];

        return array(
        	'rule' => $method,
        	'params' => $params,
    	); 
	}

    /**
     * @param string $key
     * @param string $rule
     * @return mixed
     */
    public function setMessage($key='', $rule='')
	{
		if (isset($this->messages[$key][$rule])) {
			$message = $this->messages[$key][$rule];
		} else {
			$message = preg_replace('/:(\w+)/', $key, $this->defaults[$rule], 1);
		}
		return $message;
	}

    /**
     * @param string $key
     * @return bool
     */
    public function required($key='')
	{
		return $this->data[$key] == '';
	}

    /**
     * @param string $key
     * @return bool
     */
    public function email($key='')
	{
		return ! filter_var($this->data[$key], FILTER_VALIDATE_EMAIL);
	}

    /**
     * @param string $key
     * @param int $min
     * @return bool
     */
    public function min($key='', $min=0)
	{
		return strlen($this->data[$key]) < $min;
	}

    /**
     * @param string $key
     * @param int $max
     * @return bool
     */
    public function max($key='', $max=0)
	{
		return strlen($this->data[$key]) > $max;
	}
}