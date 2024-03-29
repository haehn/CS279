<?php

// modified for CS279 commandmaps

/**
 *
 *            sSSs   .S    S.    .S_sSSs     .S    sSSs
 *           d%%SP  .SS    SS.  .SS~YS%%b   .SS   d%%SP
 *          d%S'    S%S    S%S  S%S   `S%b  S%S  d%S'
 *          S%S     S%S    S%S  S%S    S%S  S%S  S%|
 *          S&S     S%S SSSS%S  S%S    d* S  S&S  S&S
 *          S&S     S&S  SSS&S  S&S   .S* S  S&S  Y&Ss
 *          S&S     S&S    S&S  S&S_sdSSS   S&S  `S&&S
 *          S&S     S&S    S&S  S&S~YSY%b   S&S    `S*S
 *          S*b     S*S    S*S  S*S   `S%b  S*S     l*S
 *          S*S.    S*S    S*S  S*S    S%S  S*S    .S*P
 *           SSSbs  S*S    S*S  S*S    S&S  S*S  sSS*S
 *            YSSP  SSS    S*S  S*S    SSS  S*S  YSS'
 *                         SP   SP          SP
 *                         Y    Y           Y
 *
 *                     R  E  L  O  A  D  E  D
 *
 * (c) 2012 Fetal-Neonatal Neuroimaging & Developmental Science Center
 *                   Boston Children's Hospital
 *
 *              http://childrenshospital.org/FNNDSC/
 *                        dev@babyMRI.org
 *
 */

// prevent direct calls
if(!defined('__MINDMARGIN_ENTRY_POINT__')) die('Invalid access.');


/**
 *
 * The database connector.
 *
 */
class DB {

  /**
   *
   * The instance reference for the singleton pattern.
   *
   * @var DB $instance
   */
  private static $instance = null;

  /**
   * The link to the MySQL database.
   *
   * @var mysqli|null $link
   */
  private $link = null;

  /**
   * The constructor which also opens a connection to the database.
   *
   * This constructor is private and can not be called. All access must
   * happen through the static DB::getInstance() method to apply the singleton
   * pattern.
   *
   * The login credentials are defined in the ChRIS config.inc.php file.
   *
   * @throws Exception An exception if the database connection fails.
   */
  private function __construct() {

    $link = new mysqli(SQL_HOST, SQL_USERNAME, SQL_PASSWORD, SQL_DATABASE);

    if ($link->connect_errno) {

      throw new Exception('Failed to connect to database: '.$link->connect_error);

    }

    // store the link
    $this->link = $link;

  }

  /**
   * Get the instance of the database connector. This always creates a valid
   * instance by either creating a new one or by returning an existing one.
   *
   * @return DB The instance to use.
   */
  public static function getInstance() {

    if (!self::$instance) {

      // first call, create an instance
      self::$instance = new DB();

    }

    // return the new or existing instance
    return self::$instance;

  }

  /**
   * Lock a table in the database.
   *
   * @param[in] $tablename table to be locked
   * @param[in] $type lock type (READ or WRITE)
   *
   * @return DB The result of the query.
   */
  public function lock($tablename, $type) {
    return $this->link->query('LOCK TABLES '.$tablename.' '.$type.';');
  }

  /**
   * Unlock a table in the database.
   *
   * @return DB The result of the query.
   */
  public function unlock() {
    return $this->link->query('UNLOCK TABLES;');
  }

  /**
   * Execute an SQL query as a prepared statement. This protects against SQL injections.
   *
   * <i>Example usage</i>:
   * <pre>
   * DB::getInstance()->execute('SELECT * FROM patient WHERE id=(?)',array(0=>$id));
   * </pre>
   * In this case, the (?) question mark gets replaced by the value of the $id variable. The
   * type of the $id variable gets automatically detected based on its php type.
   *
   * @param string $query The SQL query to execute.
   * @param array|null $variables An array of variables to bind as parameters in the SQL query.
   *                              The type of the variables gets automatically detected.
   * @return An array of rows representing each resulting dataset. This can be an empty array,
   *               if the query does not result in any datasets.
   * @throws Exception An exception if the query can not be prepared or executed.
   */
  public function execute($query, $variables=null) {

    //echo $query;

    $link = $this->link;

    // prepare the query
    if (!($statement = $link->prepare($query))) {

      throw new Exception('Failed to prepare query: '.$link->error);

    }

    // bind the parameters
    if ($variables != null) {
      $types = '';
      $temp = array();
      foreach($variables as $variable) {
        // detect the type and store the first letter
        // i for integer
        // d for double
        // s for string etc.
        $type = gettype($variable);
        $types .= $type{0};
      }

      // bind_names[0] == 'sssid'
      $bind_names[] = $types;
      // update bind_names[1], bind_names[2] with corresponding value
      for ($i=0; $i<count($variables);$i++) {
        $bind_names[] = &$variables[$i];
      }

      call_user_func_array(array($statement,'bind_param'),$bind_names);
    }

    // execute the query
    $statement->execute();

    // -1 = select because select doesnt affect rows
    $queryType = $statement->affected_rows;

    // return last inserted
    // returns 0 for update and delete
    if($queryType >= 0)
    {
      return $statement->insert_id;
    }

    // grab the meta data of the query
    $result = $statement->result_metadata();
    // check which fields are expected
    $fields = array();
    $resultFields = array();
    $count = 0;
    while ($field = $result->fetch_field()) {

      $tmp_field = $field->name;

      if (in_array($tmp_field, $fields)) {
        $tmp_field .= '-'.$count;
        $count++;
      }

      $fields[] = $tmp_field;
      $resultFields[] = &${
        $tmp_field};
    }

    // call $statement->bind_result for each of the expected fields
    call_user_func_array(array($statement, 'bind_result'), $resultFields);

    // grab the results
    $results = array();
    $i = 0; // results counter
    while ($statement->fetch()) {
      $j = 0;
      // loop through all fields for each result
      foreach($fields as $field){

        // save field name
        $results[$i][$j][0] = $field;
        //save field value
        $results[$i][$j][1] = ${
          $field};
          $j++;
      }
      $i++;
    }

    // return the results
    return $results;

  }

}

?>