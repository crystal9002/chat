<?php

require_once 'ConnectionPool.php';

$server = new swoole_websocket_server("127.0.0.1", 9502);
$server->on('open', function ($server, $req) {
  $uri = trim($req->server['request_uri'], '/');
  list($tmp, $room_id) = explode('/', $uri);
  ConnectionPool::push($room_id, $req->fd);
});

$server->on('message', function ($server, $frame) {
  $params = json_decode($frame->data);
  $pool = ConnectionPool::getRoomFd($params->room_id);
  foreach ($pool as $fd) {
    $server->push($fd, $frame->data);
  }
});

$server->on('close', function ($server, $fd) {
  ConnectionPool::remove($fd);
});
$server->start();
