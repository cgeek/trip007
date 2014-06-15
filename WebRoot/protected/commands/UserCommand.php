<?php

class UserCommand extends CConsoleCommand
{
	public function actionTest()
	{
		echo 'test';
    }

    public function actionUserNameToFile()
    {
		$criteria = new CDbCriteria;
		$criteria->addBetweenCondition("user_id", 100, 7900);
		$users = User::model()->findAll($criteria);

        $file_name = 'aa.txt';
        $fopen = fopen($file_name, 'wb');
        foreach($users as $user) {
            echo $user->user_name . "\n";
            fputs($fopen, $user->user_name . "\n");
        }
        fclose($fopen);
    }
}
