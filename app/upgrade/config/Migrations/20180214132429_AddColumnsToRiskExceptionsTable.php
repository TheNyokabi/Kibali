<?php
use Phinx\Migration\AbstractMigration;

class AddColumnsToRiskExceptionsTable extends AbstractMigration
{

    public function up()
    {

        $this->table('risk_exceptions')
            ->addColumn('closure_date_toggle', 'boolean', [
                'after' => 'expired',
                'default' => '1',
                'length' => null,
                'null' => false,
            ])
            ->addColumn('closure_date', 'date', [
                'after' => 'closure_date_toggle',
                'default' => null,
                'length' => null,
                'null' => true,
            ])
            ->update();
    }

    public function down()
    {

        $this->table('risk_exceptions')
            ->removeColumn('closure_date_toggle')
            ->removeColumn('closure_date')
            ->update();
    }
}

