<?php

class TestCommand extends CConsoleCommand
{
    public function run($args)
    {
        $table1=new Fee;
        
        $table->id=1;
        $table->user_id=1;
        $table->request_id=1;
        $table->mode=1;
        $table->cheque_no=10;
        $table->description='hai';
        $table->amount=100;
        $table->created_at=date('y-m-d:h-i-s');
        $table->save();
    }
}