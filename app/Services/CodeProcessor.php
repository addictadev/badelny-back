<?php
namespace App\Services;

class CodeProcessor
{
    private $codeLength = 6;

	 /**
     * Singleton instance
     * @var
     */
    private static $instance;

	/**
     * CodeProcessor constructor
     */
    private function __construct()
    {
        $this->codeLength = env('SMS-VERIFICATIONS_CODE_LENGTH', $this->codeLength);
    }

    /**
     * Singleton
     * @return CodeProcessor
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function generateCode()
    {
		$randomFunction = 'random_int';
		$code = $randomFunction(pow(10, $this->codeLength - 1), pow(10, $this->codeLength) - 1);
		return $code;
    }
}
