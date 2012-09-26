<?php
/**
 * This is template for EAnnotationUtility
 * @see EAnnotationUtility::generateNetbeansHelpers()
 */
?>
tag.<?=$data->i?>.documentation=<?= str_replace("\n", '\r\n', str_replace("\r", "\n", str_replace("\r\n", "\n", $this->render('netbeansAnnotationsDescription', ['data' => $data], true)))) . "\r\n";?>
tag.<?=$data->i?>.insertTemplate=<?= $data->name;;?>('${param1}')
tag.<?=$data->i?>.name=<?= $data->insertTemplate . "\r\n";?>
tag.<?=$data->i?>.types=<?= implode(',', $data->targets) . "\r\n";?>