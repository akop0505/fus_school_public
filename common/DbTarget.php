<?php
namespace app\common;

use yii\helpers\VarDumper;
use yii\log\DbTarget as Base;

class DbTarget extends Base
{
	/**
	 * Stores log messages to DB.
	 */
	public function export()
	{
		$tableName = $this->db->quoteTableName($this->logTable);
		$sql = "INSERT INTO $tableName ([[level]], [[category]], [[log_time]], [[prefix]], [[message]])
                VALUES (:level, :category, FROM_UNIXTIME(:log_time), :prefix, :message)";
		$command = $this->db->createCommand($sql);
		foreach ($this->messages as $message)
		{
			list($text, $level, $category, $timestamp) = $message;
			if (!is_string($text)) {
                // exceptions may not be serializable if in the call stack somewhere is a Closure
                if ($text instanceof \Exception) {
                    $text = (string) $text;
                } else {
                    $text = VarDumper::export($text);
                }
            }
			$command->bindValues([
				':level' => $level,
				':category' => $category,
				':log_time' => $timestamp,
				':prefix' => $this->getMessagePrefix($message),
				':message' => $text,
			])->execute();
		}
	}

	/**
	 * @inheritdoc
	 */
	public static function filterMessages($messages, $levels = 0, $categories = [], $except = [])
	{
		$messages = parent::filterMessages($messages, $levels, $categories, $except);
		foreach($messages as $i => $message)
		{
			if('yii\web\HttpException:404' == $message[2] && (
				strpos($_SERVER['REQUEST_URI'], '.html') === false
				|| strpos($_SERVER['REQUEST_URI'], 'forum') !== false
			))
			{
				unset($messages[$i]);
			}
		}
		return $messages;
	}
}