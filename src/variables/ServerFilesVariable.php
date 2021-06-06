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


namespace amkdev\serverfiles\variables;

use amkdev\serverfiles\ServerFiles;
use Craft;

class ServerFilesVariable
{

    public function config($settings = null)
    {
        return ServerFiles::$plugin->serverFilesService->list($settings);
    }

}
