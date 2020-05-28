<?php
/**
 * @class Sort
 * properties - 5
 * methods - 9
 * MAIN FUNCTION:
 * - Load and process files into one array
 * - Implement sorting method to sort the array by given argument
 * - Format output and print to file and console
 */

class Sort
{
    // $files - contain all file names that will be loaded
    private array $files = ['comma.txt','pipe.txt','space.txt'];

    // $rows - Initial array with associative keys that will collect all the rows from all the files
    private array $rows;

    // $data - Array with indexed keys will collect the rows from $rows array after being processed
    private array $data;

    // $temp_data - Temporary array will be used to reset the sorting array to initial order
    private array $temp_data;

    // $output will be used to print the result into file and on the browser/console
    private string $output = "";


    /**
     * Sort constructor.
     * Opens each file from the $this->file array and collect the rows into $rows array
     * Then calls the processRows() method
     * @param array|null $files_array contain files to be processed
     * @throws Exception
     */
    public function __construct(array $files_array = null)
    {
        if($files_array === null || count($files_array) === 0) $files_array = $this->files;

        // absolute_path is used for the class to be implemented from any directory
        $absolute_path = substr(dirname(__FILE__), 0, -5);
        foreach ($files_array as $file) {
            if (file_exists($absolute_path . 'source/' . $file)) {
                $this->rows[$file] = array_filter(file($absolute_path .'source/' .
                    $file, FILE_SKIP_EMPTY_LINES));
            }
        }
        if(empty($this->rows)) throw new Exception("There are no loaded files");

        $this->processRows();
    }


    /**
     * @function processRows()
     * parses the rows to $this->data array property
     * $temp_data copies the $this->data array to be used for sorting method.
     */
    private function processRows(){

        //Iterate trough each group of the $this->rows array (from each file)
        foreach ($this->rows as $fileGroup=>$fileRows){
            foreach ($fileRows as $row) {
                //$fileGroup contain the name of the each file
                // Each group of rows from different files are processed differently
                // the rows from the first file - 'comma.txt' are processed
                if ($fileGroup === 'comma.txt') {

                    // each $row is converted to array then
                    // elements in the $row array are trimmed from empty spaces or new lines
                    $row = array_map('trim',explode(",", $row));

                    // 5th element (DateOfBirth) is moved to 4th position, 4th element becomes 5th
                    $row = $this->moveKeyUp($row, 4);

                // the rows from the second and third file - 'pipe.txt and space.txt' are processed
                } elseif ($fileGroup === 'pipe.txt' || $fileGroup === 'space.txt') {

                    $row = ($fileGroup === 'pipe.txt') ? explode("|", $row) : explode(" ", $row);
                    $row = array_map('trim', $row);

                    // 3th element in $row array (middleName) is been deleted - not needed for output
                    unset($row[2]);

                    // reset indexes in the $row array after deleting the 3th element in $row
                    $row = array_values($row);

                    ($fileGroup === 'pipe.txt') ? $row = $this->moveKeyUp($row, 4) : null;

                    //dateOfBirth in these files have different format and need to be reformatted
                    $row[3] = date_format(date_create_from_format('n-j-Y', $row[3]), 'n/j/Y');

                    // Setting full names from abbreviations for Male and Female
                    ($row[2] === 'M') ? $row[2] = 'Male' : null;
                    ($row[2] === 'F') ? $row[2] = 'Female' : null;

                }

                // $this->data will maintain original order
                $this->data[] = $row;

                // $this->temp_data array will be used for sorting
                $this->temp_data = $this->data;
            }
        }
    }

    /**
     * @function moveKeyUp() is used in $this->processRows() method to move the 4th
     * element of 'comma.txt' and 'pipe.txt' rows on 3th position in the $row array
     * @param $array - Array to be processed
     * @param $key - Key to be moved up
     * @return array
     */
    private function moveKeyUp($array, $key){
        if($key > 0 && $key < count($array)){
            $new_array = array_slice($array, 0, ($key-1),true);
            $new_array[] = $array[$key];
            $new_array[] = $array[$key-1];
            $new_array += array_slice($array, ($key+1), count($array), true);
            return ($new_array);
        }else{
            return $array;
        }
    }

    /** SORTING METHOD
     *
     * @function sortBy()
     * sorts the array $this->data by the given column name.
     * @param int $column Name of the column to sort
     * [0 -Last Name, 1- First Name, 2 - Gender, 3 - Date Of Birth, 4 - Favorite Color]
     * @param bool $desc [OPTIONAL]. Default: FALSE. Set to TRUE to sort in descend order
     * @throws Exception
     */

    public function sortBy($column = null, $desc = false)
    {
        if(!is_int($column)) throw new Exception("Parameter column MUST be integer");
        if(!is_bool($desc)) throw new Exception("Parameter desc MUST be boolean");
        if ($column === 3) {
            usort($this->temp_data, function ($x, $y) {
                return (strtotime($x[3]) > strtotime($y[3]));
            });
        }elseif($column >= 0 && $column < 5 && $column !== 3){
            usort($this->temp_data, function ($x, $y) use ($column, $desc) {
                if ($desc === true) {$temp = $x; $x = $y; $y = $temp;}
                return strcasecmp($x[$column], $y[$column]);
            });
        }
    }


    /**
     * @function createTable()
     * converts the array $this->temp_data into string $this->output
     * format the output
     */
    public function createTable()
    {
        $this->output .= str_repeat("-", 26) . "\n";

        foreach ($this->temp_data as $row){
            foreach ($row as $value){
                $this->output .= $value . str_repeat(" ", 16 - strlen($value));
            }
            $this->output .= "\n";
        }
        $this->output .= "\n" . str_repeat("-", 26) . "\n\n";

        // After output is created, array $this->temp_data is been reset to initial order
        $this->temp_data = $this->data;
    }


    /**
     * @function outputToFile() open or create the file $fileName in the source directory
     * and prints the content of $this->output into it.
     * @param string $fileName [OPTIONAL]. Name of the output file. Default: 'output.txt'.
     * @param string|null $message [OPTIONAL]. Additional text to append to output
     * @return array - single element array with key 'message' on success or 'error' on failure
     * @throws Exception
     */
    public function outputToFile($fileName = 'output.txt', string $message = null)
    {
        if($message !== null && trim($message) !=="") $this->output .= $message . "\n";
        if($fp = fopen('source/' . $fileName, 'w')){
            if(fwrite($fp, $this->output)){
                fclose($fp);
                return ["message"=>"Data successfully printed into: <a href='source/" .
                    $fileName . "'>'source/" . $fileName . "'</a>"];
            }else{
                fclose($fp);
                throw new Exception("Can not write to 'source/" . $fileName . "'");
            }
        }else{
            throw new Exception("File 'source/" . $fileName . "' can not be opened");
        }

    }

    /**
     * @function outputToBrowser() converts the empty space of $this->output
     * into HTML char codes for formatting the output
     * @return string|string[]
     */
    public function outputToBrowser()
    {
        return str_replace(" ", "&nbsp;", $this->output);
    }

    /**
     * $this->output getter
     * @return string
     */
    public function getOutput(){
        return $this->output;
    }


    /**
     * @function setOutput() filters the $text string and appends to $this->output
     * @param $text string
     */
    public function setOutput($text){
        $text = filter_var($text, FILTER_SANITIZE_STRING);
        $this->output .= $text . "\n";
    }

}