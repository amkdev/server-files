<?php

/**
 * Server Files plugin for Craft CMS 3.1
 *
 * Retrieve a list of files based on a specified folder path.
 *
 * @author    You & Me Digital, Alexander M. Korn
 * @link      https://github.com/amkdev/server-files
 * @copyright Copyright (c) 2019 You & Me Digital, Alexander M. Korn
 */

namespace amkdev\serverfiles\services;

use amkdev\serverfiles\ServerFiles;
use Symfony\Component\Finder\Finder;

use Craft;
use craft\base\Component;

$exif = \PHPExif\Reader\Reader::factory(\PHPExif\Reader\Reader::TYPE_NATIVE);

/**
 * ServerFilesService Service
 *
 * @author    You & Me Digital, Alexander M. Korn
 * @package   ServerFiles
 * @since     1.0.0
 */
class ServerFilesService extends Component
{

    protected $pluginExif;

    public function __construct()
    {
        $this->pluginExif = \PHPExif\Reader\Reader::factory(\PHPExif\Reader\Reader::TYPE_NATIVE);
    }
    // public function getExifData($file)
    // {

    //     $exif = $this->reader->read($assetFilePath);
    // }
    function getStringBetween($content, $start, $end)
    {
        $r = explode($start, $content);
        if (isset($r[1])) {
            $r = explode($end, $r[1]);
            return $r[0];
        }
        return '';
    }

    public function list($settings): array
    {
        // get and set settings array
        $filePath = $settings['path'] ?? '';
        $filePattern = $settings['pattern'] ?? '*';
        $filePathFormat = $settings['pathformat'] ?? '2';
        $plugins = $settings['info'] ?? null;

        // $reader = 
        // if filePath is not empty...
        if ($filePath !== '') {

            // get full base path
            $fullPath = \Yii::getAlias(ServerFiles::getInstance()->getSettings()->publicRoot ?? '@webroot');

            $filePath=DIRECTORY_SEPARATOR.ltrim(rtrim($filePath,DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

   
            // set default value of output to prevent errors
            $output[] = '';
            $asset[] = '';

            // process options...
            $path = $fullPath . $filePath;
            $pattern = $filePattern;

            // start new finder instance
            $finder = new Finder();

            // filter results
            // https://symfony.com/doc/current/components/finder.html
            $finder
                ->files()
                ->in($path)
                ->name($filePattern)
                ->depth('== 0');
              
            $finder->sortByName();

            $info = array();

            $pluginsArray = explode(' ', $plugins);
            $pluginsOptions = array();

            foreach ($pluginsArray as $plugin) {
                $pluginsOptions[strtok(rtrim($plugin), "[")] = explode(',', $this->getStringBetween($plugin, '[', ']'));
            }
            
            // for each result, set output to filename
            foreach ($finder as $file) {
                $full = Craft::getAlias('@webroot').$filePath.$file->getFileName();

                if (is_file($full)) {

                    $info = array();

                    foreach ($pluginsOptions as $plugin => $options) {
                        switch ($plugin) {
                            case "exif":
                                $exif = $this->pluginExif->read($full);
                                $data = $exif->getData();
                                foreach ($options as $option) {
                                    $info[$plugin][$option] = array_key_exists($option, $data) ? $data[$option] : '';
                                }
                            break;
                        }
                    }

                    array_push($asset, array(
                        'name' => $file->getFileName(),
                        'file' =>  $filePath . $file->getFileName(),
                        'full' => $full,
                        'info' => $info
                    ));
                }
            }

            // remove empty array results
            $output = array_filter($asset);

            // output array
            return $output;
        }

        // otherwise return nothing
        return [];
    }
}
