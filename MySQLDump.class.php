<?php
namespace PH7\SQL\Backup;

/**
 * @class MySQLDump Class
 * @access public
 * @desc
 * Backs up a database, creating a file for each day of the week,
 * using the mysqldump utility.
 * Can compress backup file with gzip of bzip2.
 * Intended for command line execution in conjunction with cron (e.g. cPanel, Plesk, ...).
 * Requires the user executing the script has permission to execute mysqldump.
 *
 * == Example of using this class ==
 *
 * - code -
 *
 * $oMySQLDump = new PH7\Framework\Mvc\Model\Engine\Util\Backup\MySQL('localhost', 'database_user', 'database_password', 'database_name', '/var/www/backup/sql/', 'bz2');
 * $oMySQLDump->backup();
 *
 * - code -
 *
 * @use Please run your php script using this class (~/MySQLDump.class.php) with a cron (e.g. cPanel, Plesk, ...) and execute this script at a time periodic.
 *
 * @author      SORIA Pierre-Henry
 * @email       pierrehs@hotmail.com
 * @link        http://github.com/pH-7
 * @copyright   Copyright pH7 Script All Rights Reserved.
 * @license     GNU GPL 3 (www.gnu.org/licenses/gpl-3.0.html)
 * @version     $Id: MySQLDump.class.php 1.2
 * @create 2012-02-13
 * @update 2012-04-21
 */

class MySQL {

  /**
   * @desc The backup command to execute
   * @access private
   * @var string $cmd
   */
  private $cmd;
  
  /**
   * @desc The extension allowed.
   * @access private 
   * @var array $aZipExt
   */
  private $aZipExt = array('gz'=>'gzip','bz2'=>'bzip2');

  /**
   * @desc MySQLDump constructor
   * @access public
   * @param string $sDbHost (MySQL Host Name)
   * @param string $sDbUser (MySQL User Name)
   * @param string $sDbPass (MySQL User Password)
   * @param string $sDbName (Database to select)
   * @param string $sDest (Full dest. directory for backup file)
   * @param string $sZip (Zip type; gz - gzip [default], bz2 - bzip)
   */
  public function __construct($sDbHost, $sDbUser, $sDbPass, $sDbName, $sDest, $sZip = 'gz')
  {
    $bZip = (array_key_exists($sZip, $this->aZipExt)) ? true : false;
    $sExt = ($bZip) ? PH7_DOT . $sZip : '';
    $sFileName = 'Periodic-database-update.' . date('Y-m-d') . '.sql' . $sExt;
    $sOptions = ($bZip) ?  ' | ' . $this->aZipExt[$sZip] : '';

    $this->cmd = 'mysqldump -h' . $sDbHost . ' -u' . $sDbUser . ' -p' . $sDbPass . ' ' . $sDbName . $sOptions . ' > ' . $sDest . $sFileName;
  }

  /**
   * @desc Runs the constructed command
   * @access public
   * @return void
   */
  public function backup()
  {
    $sError = '';
    system($this->cmd, $sError);
    if ($sError)
    {
      trigger_error('Backup failed: Command = ' . $this->cmd . ' Error = ' . $sError);
    }
  }

}
