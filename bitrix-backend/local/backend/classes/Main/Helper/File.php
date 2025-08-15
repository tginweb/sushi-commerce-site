<?php

namespace Main\Helper;

use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\IO;
use Bitrix\Main\UI\Viewer;
use Bitrix\Main\Web\HttpClient;
use CFile;
use CHTTP;
use CMain;
use COption;
use CUtil;
use function TG\Main\Helper\htmlspecialcharsbx;
use const TG\Main\Helper\LANGUAGE_ID;

class File
{
    public static function outputToClient($arFile, $arOptions = array())
    {
        $previewManager = new Viewer\PreviewManager();

        if ($previewManager->isInternalRequest($arFile, $arOptions)) {
            $previewManager->processViewByUserRequest($arFile, $arOptions);
        }


        /** @global CMain $APPLICATION */
        global $APPLICATION;

        $fastDownload = (COption::GetOptionString('main', 'bx_fast_download', 'N') == 'Y');

        $attachment_name = "";
        $content_type = "";
        $specialchars = false;
        $force_download = false;
        $cache_time = 10800;
        $fromClouds = false;
        $filename = '';
        $fromTemp = false;

        if (is_array($arOptions)) {
            if (isset($arOptions["content_type"]))
                $content_type = $arOptions["content_type"];
            if (isset($arOptions["specialchars"]))
                $specialchars = $arOptions["specialchars"];
            if (isset($arOptions["force_download"]))
                $force_download = $arOptions["force_download"];
            if (isset($arOptions["cache_time"]))
                $cache_time = intval($arOptions["cache_time"]);
            if (isset($arOptions["attachment_name"]))
                $attachment_name = $arOptions["attachment_name"];
            if (isset($arOptions["fast_download"]))
                $fastDownload = (bool)$arOptions["fast_download"];
        }

        if ($cache_time < 0)
            $cache_time = 0;

        if (is_array($arFile)) {
            if (isset($arFile['CONTENT'])) {
                $filename = '';
            } else if (isset($arFile["SRC"])) {
                $filename = $arFile["SRC"];
            } elseif (isset($arFile["tmp_name"])) {
                if (mb_strpos($arFile['tmp_name'], $_SERVER['DOCUMENT_ROOT']) === 0) {
                    $filename = '/' . ltrim(mb_substr($arFile['tmp_name'], mb_strlen($_SERVER['DOCUMENT_ROOT'])), '/');
                } elseif (defined('BX_TEMPORARY_FILES_DIRECTORY') && mb_strpos($arFile['tmp_name'], BX_TEMPORARY_FILES_DIRECTORY) === 0) {
                    $fromTemp = true;
                    $tmpPath = COption::GetOptionString('main', 'bx_tmp_download', '/bx_tmp_download/');
                    $filename = $tmpPath . ltrim(mb_substr($arFile['tmp_name'], mb_strlen(BX_TEMPORARY_FILES_DIRECTORY)), '/'); //nonexistent path
                }
            } else {
                $filename = CFile::GetFileSRC($arFile);
            }
        } elseif (($arFile = CFile::GetFileArray($arFile))) {
            $filename = $arFile['SRC'];
        }

        if ($filename == '' && !$arFile['CONTENT']) {
            return false;
        }


        if ($content_type == '' && isset($arFile["CONTENT_TYPE"])) {
            $content_type = $arFile["CONTENT_TYPE"];
        }

        //we produce resized jpg for original bmp
        if ($content_type == '' || $content_type == "image/bmp") {
            if (isset($arFile["tmp_name"])) {
                $content_type = CFile::GetContentType($arFile["tmp_name"], true);
            } else if ($filename) {
                $content_type = CFile::GetContentType($_SERVER["DOCUMENT_ROOT"] . $filename);
            }
        }

        if ($arFile["ORIGINAL_NAME"] <> '')
            $name = $arFile["ORIGINAL_NAME"];
        elseif ($arFile["name"] <> '')
            $name = $arFile["name"];
        else
            $name = $arFile["FILE_NAME"];
        if (isset($arFile["EXTENSION_SUFFIX"]) && $arFile["EXTENSION_SUFFIX"] <> '')
            $name = mb_substr($name, 0, -mb_strlen($arFile["EXTENSION_SUFFIX"]));

        $name = str_replace(array("\n", "\r"), '', $name);

        if ($attachment_name)
            $attachment_name = str_replace(array("\n", "\r"), '', $attachment_name);
        else
            $attachment_name = $name;

        if (!$force_download) {
            $isImage = CFile::IsImage($name, $content_type) || ($content_type === 'application/pdf');

            if (!$isImage) {
                //only valid images can be downloaded inline
                $force_download = true;
            }
        }

        $content_type = CFile::NormalizeContentType($content_type);

        if ($force_download) {
            $specialchars = false;
        }

        $src = null;
        $file = null;

        if (!$arFile['CONTENT']) {
            if ((mb_substr($filename, 0, 1) == '/') && !$fromTemp) {
                $file = new IO\File($_SERVER['DOCUMENT_ROOT'] . $filename);
            } elseif (isset($arFile['tmp_name'])) {
                $file = new IO\File($arFile['tmp_name']);
            }

            if ((mb_substr($filename, 0, 1) == '/') && ($file instanceof IO\File)) {
                try {
                    $src = $file->open(IO\FileStreamOpenMode::READ);
                } catch (IO\IoException $e) {
                    return false;
                }
            } else {
                if (!$fastDownload) {
                    $src = new HttpClient();
                } elseif (intval($arFile['HANDLER_ID']) > 0) {
                    $fromClouds = true;
                }
            }
        }


        $APPLICATION->RestartBuffer();

        $cur_pos = 0;
        $filesize = ($arFile["FILE_SIZE"] > 0 ? $arFile["FILE_SIZE"] : $arFile["size"]);
        $size = $filesize - 1;

        if (!$arFile['CONTENT']) {

            $p = mb_strpos($_SERVER["HTTP_RANGE"], "=");
            if (intval($p) > 0) {
                $bytes = mb_substr($_SERVER["HTTP_RANGE"], $p + 1);
                $p = mb_strpos($bytes, "-");
                if ($p !== false) {
                    $cur_pos = floatval(mb_substr($bytes, 0, $p));
                    $size = floatval(mb_substr($bytes, $p + 1));
                    if ($size <= 0) {
                        $size = $filesize - 1;
                    }
                    if ($cur_pos > $size) {
                        $cur_pos = 0;
                        $size = $filesize - 1;
                    }
                }
            }

            if ($file instanceof IO\File) {
                $filetime = $file->getModificationTime();
            } elseif ($arFile["tmp_name"] <> '') {
                $tmpFile = new IO\File($arFile["tmp_name"]);
                $filetime = $tmpFile->getModificationTime();
            } else {
                $filetime = intval(MakeTimeStamp($arFile["TIMESTAMP_X"]));
            }
        } else {
            $filetime = time();
        }


        $application = Application::getInstance();
        $response = Context::getCurrent()->getResponse();

        if ($_SERVER["REQUEST_METHOD"] == "HEAD") {
            $response->setStatus("200 OK")
                ->addHeader("Accept-Ranges", "bytes")
                ->addHeader("Content-Type", $content_type)
                ->addHeader("Content-Length", ($size - $cur_pos + 1));

            if ($filetime > 0) {
                $response->addHeader("Last-Modified", date("r", $filetime));
            }
        } else {
            $lastModified = '';

            $utfName = CHTTP::urnEncode($attachment_name, "UTF-8");
            $translitName = CUtil::translit($attachment_name, LANGUAGE_ID, array(
                "max_len" => 1024,
                "safe_chars" => ".",
                "replace_space" => '-',
                "change_case" => false,
            ));


            if ($force_download) {

                //Disable zlib for old versions of php <= 5.3.0
                //it has broken Content-Length handling
                if (ini_get('zlib.output_compression'))
                    ini_set('zlib.output_compression', 'Off');

                if ($cur_pos > 0) {
                    $response->setStatus("206 Partial Content");
                } else {
                    $response->SetStatus("200 OK");
                }

                $response->addHeader("Content-Type", $content_type)
                    ->addHeader("Content-Disposition", "attachment; filename=\"" . $translitName . "\"; filename*=utf-8''" . $utfName)
                    ->addHeader("Content-Transfer-Encoding", "binary")
                    ->addHeader("Content-Length", ($size - $cur_pos + 1));

                if (is_resource($src)) {
                    $response->addHeader("Accept-Ranges", "bytes");
                    $response->addHeader("Content-Range", "bytes " . $cur_pos . "-" . $size . "/" . $filesize);
                }
            } else {
                $response->addHeader("Content-Type", $content_type);
                $response->addHeader("Content-Disposition", "inline; filename=\"" . $translitName . "\"; filename*=utf-8''" . $utfName);
            }

            if ($cache_time > 0) {

            } else {
                $response->addHeader("Cache-Control", "no-cache, must-revalidate, post-check=0, pre-check=0");
            }

            $response->addHeader("Expires", "0");
            $response->addHeader("Pragma", "public");

            // Download from front-end
            if ($fastDownload) {
                if ($fromClouds) {
                    $filename = preg_replace('~^(http[s]?)(\://)~i', '\\1.', $filename);
                    $cloudUploadPath = COption::GetOptionString('main', 'bx_cloud_upload', '/upload/bx_cloud_upload/');
                    $response->addHeader('X-Accel-Redirect', $cloudUploadPath . $filename);
                } else {
                    $filename = CHTTP::urnEncode($filename, "UTF-8");
                    $response->addHeader('X-Accel-Redirect', $filename);
                }
                $response->writeHeaders();
                $application->terminate();
            } else {
                session_write_close();
                $response->writeHeaders();

                if ($specialchars) {
                    /** @var IO\File $file */
                    echo "<", "pre", ">";
                    if (is_resource($src)) {
                        while (!feof($src))
                            echo htmlspecialcharsbx(fread($src, 32768));
                        $file->close();
                    } else {
                        /** @var HttpClient $src */
                        echo htmlspecialcharsbx($src->get($filename));
                    }
                    echo "<", "/pre", ">";
                } else {
                    if ($arFile['CONTENT']) {
                        $fp = fopen("php://output", "wb");
                        fwrite($fp, $arFile['CONTENT']);
                        fclose($fp);
                    } else if (is_resource($src)) {
                        /** @var IO\File $file */
                        $file->seek($cur_pos);
                        while (!feof($src) && ($cur_pos <= $size)) {
                            $bufsize = 131072; //128K
                            if ($cur_pos + $bufsize > $size)
                                $bufsize = $size - $cur_pos + 1;
                            $cur_pos += $bufsize;
                            echo fread($src, $bufsize);
                        }
                        $file->close();
                    } else {
                        $fp = fopen("php://output", "wb");
                        /** @var HttpClient $src */
                        $src->setOutputStream($fp);
                        $src->get($filename);
                    }
                }
                @ob_flush();
                flush();
                $application->terminate();
            }
        }
        return true;
    }

}
