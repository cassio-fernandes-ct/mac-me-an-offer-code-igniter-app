
<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/***

Run this script in cli 
1. For Create New Migration file for add blog table run following command
php index.php migrate/generate add_blog 
it will create new YYYYMMDDHHIISS_add_blog.php file in app/migration folder. please create migration folder before run this command
2. for made table in database run following command 
php index.php migrate
it will create blog table in database 

**/ 

class Migrate extends CI_Controller
{
 
    public function __construct()
    {
        parent::__construct();
        if (!$this->input->is_cli_request()) {
            show_error('You don\'t have permission for this action');
            return;
        }
        //  $this->load->library('migration');
    }
    public function index()
    {
        if(!$this->migration->latest()) 
        {
          show_error($this->migration->error_string());
        }
    }
    public function current()
    {
        var_dump($this->migration->current());

    }
    public function version($version)
    {
        $migration = $this->migration->version($version);
        if (!$migration) {
            echo $this->migration->error_string();
        } else {
            echo 'Migration done' . PHP_EOL;
        }
    }
 
    public function generate($name = false)
    {
        if(!$name)
        {
            echo "Please define migration name". PHP_EOL;
            return;
        }
 
        if (!preg_match('/^[a-z_]+$/i', $name)) {
            if (strlen($name) < 4) {
                echo "Migration must be at least 4 characters long" . PHP_EOL;
                return;
            }
            echo "Wrong migration name, allowed characters: a-z and _\nExample: first_migration" . PHP_EOL;
            return;
        }
        $filename = date('YmdHis') . '_' . $name . '.php';
        try {
            $folderPath = APPPATH . 'migrations';
            if (!is_dir($folderPath)) {
                try{
                    mkdir($folderPath);
                }
                catch(Exception $e) {
                    echo "Error:\n" . $e->getMessage() . PHP_EOL;
                }
            }
            $filepath = APPPATH . 'migrations/' . $filename;
            if (file_exists($filepath)) {
                echo "File allredy exists:\n" . $filepath . PHP_EOL;
                return;
            }
            $data['className'] = ucfirst($name);
            $template = $this->load->view('cli/migrations/migration_class_template', $data, TRUE);
            //Create file
            try{
                $file = fopen($filepath, "w");
                $content = "<?php\n" . $template;
                fwrite($file, $content);
                fclose($file);
            }
            catch(Exception $e){
                echo "Error:\n" . $e->getMessage() . PHP_EOL;
            }
            echo "Migration created successfully!\nLocation: " . $filepath . PHP_EOL;
        } catch (Exception $e) {
            echo "Can't create migration file!\nError: " . $e->getMessage() . PHP_EOL;
        }
    }
}