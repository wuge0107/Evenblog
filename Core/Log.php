<?php

namespace Core;

/**
 * 日志类
 */
class Log
{

    /**
     * 输出错误日志到文件
     */
    public function error($tag, $message)
    {
        $content = date('Y-m-d H:i:s') . " | ERROR | {$tag} | {$message}" . PHP_EOL;
        file_put_contents('Log/error.' . date('Ym') . '.log', $content, FILE_APPEND | LOCK_EX);
    }

    /**
     * 输出信息日志到文件
     */
    public function info($tag, $message)
    {
        $content = date('Y-m-d H:i:s') . " | INFO | {$tag} | {$message}" . PHP_EOL;
        file_put_contents('Log/info.' . date('Ym') . '.log', $content, FILE_APPEND | LOCK_EX);
    }

}
