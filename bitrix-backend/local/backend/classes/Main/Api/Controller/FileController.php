<?php

namespace Main\Api\Controller;

use CFile;
use COption;
use Exception;

class FileController extends BaseController
{
    function __construct()
    {
        /*
        $router = $this->getRouter();

        $router->map('GET', '/server/restart', [$this, 'mutationServerRestart']);

        $router->map('POST', '/file/upload', [$this, 'mutationFileUpload']);
        $router->map('POST', '/file/remove', [$this, 'mutationFileRemove']);
        $router->map('GET', '/file/download', [$this, 'queryFileDownload']);
        $router->map('GET', '/file/generate', [$this, 'queryFileGenerate']);

        $router->map('GET', '/file/test', [$this, 'queryFileTest']);
        */
    }

    function queryFileTest()
    {
        die('aaa');
    }

    function queryFileGenerate()
    {
        $params = $_REQUEST;

        $response = $this->createResponse();

        try {

            $handlerName = $params['handler'];

            $entityType = $params['entityType'];
            $entityId = $params['entityId'];
            $field = $params['field'];
            $fileId = $params['fileId'];
            $format = $_REQUEST['format'];

            $handlers = $this->getHooks()->apply_filters('main:upload-handlers', []);

            $handler = $handlers[$handlerName];

            if (!$handler) {
                throw new Exception('Not found handler');
            }

            $handler['onGenerate']($entityType, $entityId, $field, $format);

        } catch (Exception $e) {
            $response->addException($e);
        }

        return $response->getRestJson();
    }

    function queryFileDownload()
    {
        $handlerName = $_REQUEST['handler'];
        $entityType = $_REQUEST['entityType'];
        $entityId = $_REQUEST['entityId'];
        $field = $_REQUEST['field'];
        $fileId = $_REQUEST['fileId'];
        $format = $_REQUEST['format'];

        $handler['onDownload']($entityType, $entityId, $field, $format);
    }

    function mutationFileRemove()
    {
        $params = $this->getRequestBodyJsonParsed();

        $response = $this->createResponse();

        try {
            $handlerName = $params['handler'];

            $entityType = $params['entityType'];
            $entityId = $params['entityId'];
            $field = $params['field'];
            $fileId = $params['fileId'];

            $handlers = $this->getHooks()->apply_filters('main:upload-handlers', []);

            $handler = $handlers[$handlerName];

            if (!$handler) {
                throw new Exception('Not found handler');
            }

            $arFile = CFile::GetByID($fileId)->Fetch();

            $handler['onRemove']($entityType, $entityId, $field, $arFile, $response);

        } catch (Exception $e) {
            $response->addException($e);
        }

        return $response->getRestJson();
    }

    function mutationFileUpload()
    {
        $response = $this->createResponse();

        try {
            $handlerName = $_REQUEST['handler'];
            $entityType = $_REQUEST['entityType'];
            $entityId = $_REQUEST['entityId'];
            $action = $_REQUEST['action'];
            $field = $_REQUEST['field'];
            $fileId = $_REQUEST['fileId'];

            $handlers = $this->getHooks()->apply_filters('main:upload-handlers', []);

            $handler = $handlers[$handlerName];

            if (!$handler) {
                throw new Exception('Not found handler');
            }

            $handler += [
                'filenameHash' => true,
                'filename' => '#FILENAME_BASE# - #RAND6#.#FILENAME_EXT#'
            ];

            if (is_uploaded_file($_FILES["file"]["tmp_name"])) {

                $filenameTmp = $_FILES["file"]["tmp_name"];
                $filenameOriginal = $_FILES["file"]["name"];

                $filenameResult = strtr($handler['filename'], [
                    '#FILENAME_TMP#' => $filenameTmp,
                    '#FILENAME#' => $filenameOriginal,
                    '#FILENAME_BASE#' => pathinfo($filenameOriginal, PATHINFO_FILENAME),
                    '#FILENAME_EXT#' => pathinfo($filenameOriginal, PATHINFO_EXTENSION),
                    '#RAND6#' => randString(6),
                ]);

                $filepathTmp = $_SERVER["DOCUMENT_ROOT"] . "/upload/tmp/" . $filenameResult;

                move_uploaded_file($filenameTmp, $filepathTmp);

                $arFile = CFile::MakeFileArray($filepathTmp);

                $arFile['name'] = $filenameOriginal;

                $arFile["MODULE_ID"] = "main";

                if (!$handler['filenameHash']) {

                    $optionSaveOriginalFileName = COption::GetOptionString("main", "save_original_file_name", "N");

                    COption::SetOptionString("main", "save_original_file_name", 'Y');

                    $fid = CFile::SaveFile($arFile, "main");

                    COption::SetOptionString("main", "save_original_file_name", $optionSaveOriginalFileName);
                } else {
                    $fid = CFile::SaveFile($arFile, "main");
                }

                if (intval($fid) > 0) {

                    $arFile = CFile::GetByID($fid)->Fetch();

                    $handler['onUpload']($entityType, $entityId, $field, $arFile, $response);

                    $arFile['SRC'] = CFile::GetFileSRC($arFile);

                    $response->setPayload($arFile);

                    // unlink($_SERVER["DOCUMENT_ROOT"] . "/upload/tmp/" . $filename);
                }
            }

        } catch (Exception $e) {
            $response->addException($e);
        }

        return $response->getGraphqlJson();
    }

    function mutationServerRestart()
    {

    }
}
