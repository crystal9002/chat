<?php

class ConnectionPool
{
  private static $pool = [];
  private static $list = [];

  public static function push($room_id, $fd)
  {
    self::$pool[$room_id][$fd] = $fd;
    self::$list[$fd] = $room_id;
  }

  public static function remove($fd)
  {
    if (isset(self::$list[$fd])) {
      $room_id = self::$list[$fd];
      unset(self::$pool[$room_id][$fd]);
      unset(self::$list[$fd]);
    }
  }

  public static function getRoomFd($room_id)
  {
    if (isset(self::$pool[$room_id])) {
      return self::$pool[$room_id];
    }
    return [];
  }
}