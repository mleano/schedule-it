<?php
    require('iCategoryComponent.php');
    require('abstractDatabaseConnection');
    class DatabaseCategory implements iCategoryComponent{

        private $tableName;
        private $uniqueId;

        // Stores the name of the table to access.
        function __construct($newTableName) {
          $this->tableName = $newTableName;

          $this->uniqueId = $newTableName.'_id';
        }

        public function add

    }
?>
