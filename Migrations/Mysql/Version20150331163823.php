<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Add property "image" to "User" entity
 */
class Version20150331163823 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE typo3_viewhelpertest_domain_model_user ADD image VARCHAR(40) DEFAULT NULL");
		$this->addSql("ALTER TABLE typo3_viewhelpertest_domain_model_user ADD CONSTRAINT FK_9ED94BFC53D045F FOREIGN KEY (image) REFERENCES typo3_flow_resource_resource (persistence_object_identifier)");
		$this->addSql("CREATE INDEX IDX_9ED94BFC53D045F ON typo3_viewhelpertest_domain_model_user (image)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE typo3_viewhelpertest_domain_model_user DROP FOREIGN KEY FK_9ED94BFC53D045F");
		$this->addSql("DROP INDEX IDX_9ED94BFC53D045F ON typo3_viewhelpertest_domain_model_user");
		$this->addSql("ALTER TABLE typo3_viewhelpertest_domain_model_user DROP image");
	}
}