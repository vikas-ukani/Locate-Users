<?php
namespace App\Traits;


trait FileUsers {

    /**
     * Get the user from mock.txt file.
     *
     * @return void
     */
    public function getUsersFromFile()
    {
        $file = public_path('/mock.txt');
        // $fileData = file_get_contents($file, true);
        $fileData = fopen($file, "r");

        /** Get all Key Names */
        $keys = explode(',', trim(fgets($fileData)));
        $allResults = [];

        /** read Each rows from file */
        while (!feof($fileData)) {
            $recordArray = [];
            $row = trim(fgets($fileData));

            /** Check if row string values are not empty */
            if (isset($row) && strlen($row) > 0) {
                $valueArray = explode(",", $row);
                foreach ($valueArray as $key => &$value) {
                    if($keys[$key] == 'lat' || $keys[$key] == 'lon') {
                        $value = $value * 1 ;
                    }
                    $recordArray[$keys[$key]] = $value;
                }
                array_push($allResults, $recordArray);
            }
        }
        fclose($fileData);
        return $allResults;
    }
}
