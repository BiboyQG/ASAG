<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>;.

/**
 * @package     local_greetings
 * @copyright   2023 Banghao Chi <Banghao.Chi21@student.xjtlu.edu.cn>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/greetings/index.php'));
$PAGE->set_pagelayout('standard');

$PAGE->set_title($SITE->fullname);
$PAGE->set_heading(get_string('pluginname', 'local_greetings'));

$messageform = new \local_greetings\form\message_form();

echo $OUTPUT->header();

if (isloggedin()) {
    echo '<h3>What is the concept of Software Development Engineering, ' . fullname($USER) . '?</h3>';
} else {
    echo '<h3>What is the concept of Software Development Engineering, user?</h3>';
}

$messageform->display();

if ($data = $messageform->get_data()) {
    $message = required_param('message', PARAM_TEXT);

    $url = "47.113.186.195:5000/similarity";  // POST 请求的 URL
    $fields = array(                       // POST 请求的表单数据
        'stu' => $message,
        're' => 'Software engineering is the branch of computer science that deals with the design, development, testing, and maintenance of software applications.',
        'submit' => 'submit'
    );

    $ch = curl_init();  // 初始化 CURL
    curl_setopt($ch, CURLOPT_URL, $url);  // 设置请求的 URL
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);  // 设置 POST 请求的表单数据
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // 返回结果而不是直接输出
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  FALSE);

    $response = curl_exec($ch);  // 执行请求并获取响应
    curl_close($ch);
    preg_match("/Your score:\s*(\d+(\.\d+)?)/", $response,$match);
    $match[1] = (double)$match[1] * 100;
    echo $OUTPUT->heading("Your score: ". $match[1], 4);
}

echo $OUTPUT->footer();