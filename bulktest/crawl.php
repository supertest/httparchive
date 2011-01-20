<?php
/*
Copyright 2010 Google Inc.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/

require_once("./crawl_lib.inc");
require_once("./bootstrap.inc");

$pid_arr = array();
for ($i = 0; $i < 6; $i++) {
  $pid = pcntl_fork();
  if ($pid == -1) {
    die("cannot fork subprocesses ...");
  } else {
    if ($pid) {
      // Parent process
      $pid_arr[] = $pid;
    } elseif (0 == $i) {
      // Job submission process
      $unsubmitTests = ObtainTestsWithCode(0);
      if (!IsEmptyQuery($unsubmitTests)) {
        while ($row = mysql_fetch_assoc($unsubmitTests)) {
          //var_dump($row);
          SubmitTest($row);
        }
      }
      exit();
    } elseif (1 == $i) {
      $unsubmitted = ObtainTestsWithCode(0);
      $unfinished = ObtainTestsWithCode(1);
      if (IsEmptyQuery($unfinished) && IsEmptyQuery($unsubmitted)) {
        echo "Start loading URLS from file ...\r\n";
        LoadUrlFromFile();
      }
      exit();
    } elseif (2 == $i) {
      // Check the test status with WPT server
      CheckWPTStatus();
      exit();
    } elseif (3 == $i) {
      // Obtain XML result
      ObtainXMLResult();
      exit();
    } elseif (4 == $i) {
      // Download har file
      DownloadHar();
      exit();
    } elseif (5 == $i) {
      // Fill page table and request table
      FillTables();
      exit();
    }
  }
}

while (count($pid_arr) > 0) {
  $myId = pcntl_waitpid(-1, $status, WNOHANG);
  foreach ($pid_arr as $key => $pid) {
    if ($myId == $pid) {
      unset($pid_arr[$key]);
    }
  }
  usleep(100);
}
?>
