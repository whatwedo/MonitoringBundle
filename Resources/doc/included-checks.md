# Included checks

The following checks are included by default.

| Check | Description |
|---|---|
| whatwedo\MonitoringBundle\Check\DoctrineConnectionCheck | Checks if every configured database server is reachable (Doctrine) |
| whatwedo\MonitoringBundle\Check\DoctrineMigrationCheck | Checks if all migrations are applied (Doctrine) |
| whatwedo\MonitoringBundle\Check\DoctrineSchemaSyncCheck | Checks if schema is in sync (Doctrine) |
| whatwedo\MonitoringBundle\Check\JMSJobStatusCheck | Checks if there is any failed JMS Job in the the last 3 hours |
 whatwedo\MonitoringBundle\Check\JMSJobStatusCheck | Checks if there is any failed JMS Job in the the last 3 hours |
| whatwedo\MonitoringBundle\Check\LdapToolsBundleConnectionCheck | Checks all LDAP connections of LdapToolsBundle |
| whatwedo\MonitoringBundle\Check\SymfonyRequirementsCheck | Check if all Symfony requirements are met |
