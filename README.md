# Envalo_Backup - Magento module that extends the standard backup tool to include creation of MySQL Triggers
## WARNING - Use at your own risk. Always test before using in production.
## I accept no liability for damages or anything else caused by the use of this module.
### After all, I spent less than an hour on this.

Recently I discovered that Magento Backups (In the admin, System -> Tools -> Backups) do not add the CREATE TRIGGER sql as part of the export. Crazy, I know. (Thanks @molotovbliss)

This module is very simple. It rewrites the Mysql Resource Helper that is responsible for producing the backups. It injects new functionality as part of the creation of the Foreign Keys export. 

The database I tested against a clean install of Magento Community 1.8.1.0. That database does not use triggers. I created triggers and tested those. That worked. 

I have not yet tested Enterprise. When I do I will update this with the results.

Licensed under the MIT License. 