<?php


use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('user', ['id' => false, 'primary_key' => ['iduser']]);
        $table->addColumn('iduser', 'integer')
            ->addColumn('name', 'string')
            ->addColumn('login', 'string')
            ->addColumn('password', 'string')
            ->addColumn('aux_senha', 'string')
            ->addColumn('email', 'string')
            ->addIndex(['login'], [
                'unique' => true
            ])
            ->create();
    }
}
