<?php


class Migration
{
    private $db = false;
    private $migrationsDirectory = false;
    private $seedsDirectory = false;
    private $currentMigrations = [];
    private $migrationFiles = [];

    private $currentSeeds = [];
    private $seedFiles = [];

    private $lastInsertedIndex = false;

    public function __construct($db,$migrationsDirectory,$seedsDirectory) {
        $this->migrationsDirectory = $migrationsDirectory;
        $this->seedsDirectory = $seedsDirectory;
        $this->setDbConnection($db);
    }
    public function resetEverything() {
        $query = "SET FOREIGN_KEY_CHECK = 0";
        //$this->db->query($query);

        $results = $this->db->query("SHOW TABLES")->fetchAll();
        for($i=0; $i<count($results); $i++) {
            foreach($results[$i] as $key=>$value) {
                $this->db->query("DROP TABLE " . $value);
            }
        }

    }
    private function setDbConnection($db) {
        $this->db = $db;
    }
    public function createMigrationsTable() {
        $query = "CREATE TABLE IF NOT EXISTS migrations (file_name VARCHAR(255) NOT NULL, createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";
        $this->db->query($query);
    }
    public function createSeedsTable() {
        $query = "CREATE TABLE IF NOT EXISTS seeds (file_name VARCHAR(255) NOT NULL, createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";
        $this->db->query($query);
    }

    public function migrate($seed = true) {
        $this->createMigrationsTable();
        $this->createSeedsTable();

        if($seed) {
            $this->getSeedClassFiles($this->seedsDirectory);
            ksort($this->seedFiles, SORT_NUMERIC);
        }

        $this->getMigrationClassFiles($this->migrationsDirectory . "/create/");
        ksort($this->migrationFiles,SORT_NUMERIC);
        if(count($this->migrationFiles) > 0) {

            $this->getCurrentMigrations();
            $this->performMigrations();
        } else { echo "No Create Migrations\n"; }
        $this->migrationFiles = [];

        $this->getMigrationClassFiles($this->migrationsDirectory . "/update/");
        ksort($this->migrationFiles,SORT_NUMERIC);
        if(count($this->migrationFiles) > 0) {
            $this->getCurrentMigrations();
            $this->performMigrations();
        } else { echo "No Create Migrations\n"; }

        if($seed && count($this->seedFiles) > 0) {
            $this->createSeedsTable();
            $this->getCurrentSeeds();
            $this->performSeeds();
        } else { echo "No Seeds"; }





    }

    private function performMigrations() {
        foreach($this->migrationFiles as $key => $filePath) {
            if(array_key_exists($key,$this->currentMigrations)) { continue; }
            $queries = include $filePath;
            foreach($queries as $idx=>$query) {
                $this->db->query($query);
            }
            $this->db->query('insert into migrations (file_name) values (?)',$key);
            echo "Migrated [" . $key . "]\n";
        }
    }
    private function performSeeds() {
        foreach($this->seedFiles as $key => $filePath) {
            if(array_key_exists($key,$this->currentSeeds)) { continue; }
            $this->performSeed($filePath);
            $this->db->query('insert into seeds (file_name) values (?)',$key);
            echo "Seeded [" . $key . "]\n";
        }
    }

    public function performSeed($filePath) {
        $queries = include $filePath;
        foreach($queries as $idx=>$query) {
            $this->db->query($query);
            $this->lastInsertedIndex = $this->db->insert_id();
        }

        echo "Seeded [" . $filePath . "]\n";
    }

    private function getCurrentMigrations() {
        $currentList = $this->db->query('select * from migrations order by createdAt asc')->fetchAll();
        foreach($currentList as $idx => $row) {
            $this->currentMigrations[$row['file_name']] = true;
        }
    }
    private function getCurrentSeeds() {
        $currentList = $this->db->query('select * from seeds order by createdAt asc')->fetchAll();
        foreach($currentList as $idx => $row) {
            $this->currentSeeds[$row['file_name']] = true;
        }
    }
    public function getSeedClassFiles($pathToClassFiles) {
        if ($handle = opendir($pathToClassFiles)) {

            while (false !== ($entry = readdir($handle))) {
                if($entry == '.' || $entry == '..') { continue; }
                $info = explode('.',$entry);
                if(array_key_exists($info[0],$this->seedFiles)) {
                    die($entry . " is a duplicate file name");
                }
                $this->seedFiles[$info[0]] = $pathToClassFiles . $entry;
            }
            closedir($handle);
        }
    }
    public function getMigrationClassFiles($pathToClassFiles) {
        if ($handle = opendir($pathToClassFiles)) {

            while (false !== ($entry = readdir($handle))) {
                if($entry == '.' || $entry == '..') { continue; }
                $info = explode('.',$entry);
                if(array_key_exists($info[0],$this->migrationFiles)) {
                    die($entry . " is a duplicate file name");
                }
                $this->migrationFiles[$info[0]] = $pathToClassFiles . $entry;
            }
            closedir($handle);
        }
    }
}