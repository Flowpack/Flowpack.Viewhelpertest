<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Package has been renamed from "TYPO3.Viewhelpertest" to "Flowpack.Viewhelpertest" - This migration renames tables accordingly
 */
class Version20150902105421 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->connection->getSchemaManager()->renameTable('typo3_viewhelpertest_domain_model_invoice', 'flowpack_viewhelpertest_domain_model_invoice');
		$this->connection->getSchemaManager()->renameTable('typo3_viewhelpertest_domain_model_user', 'flowpack_viewhelpertest_domain_model_user');
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->connection->getSchemaManager()->renameTable('flowpack_viewhelpertest_domain_model_invoice', 'typo3_viewhelpertest_domain_model_invoice');
		$this->connection->getSchemaManager()->renameTable('flowpack_viewhelpertest_domain_model_user', 'typo3_viewhelpertest_domain_model_user');
	}
}