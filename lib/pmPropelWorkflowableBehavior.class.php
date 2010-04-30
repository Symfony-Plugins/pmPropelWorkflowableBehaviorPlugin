<?php

class pmPropelWorkflowableBehavior
{
  protected static function getStatusAccessors($object)
  {
    $class = get_class($object);
    $status_column_name = sfConfig::get('propel_behavior_workflowable_'.$class.'_status_column_name', 'status');
    $status_column_setter = 'set'.sfInflector::camelize($status_column_name);
    $status_column_getter = 'get'.sfInflector::camelize($status_column_name);
    return array($status_column_setter, $status_column_getter);
  }

  protected static function getPossibleStatusValues($object)
  {
    $class = get_class($object);
    return sfConfig::get('propel_behavior_workflowable_'.$class.'_possible_status_values', array());
  }

  public static function canStepForward($object)
  {
    list($status_column_setter, $status_column_getter) = self::getStatusAccessors($object);
    $possible_status_values = self::getPossibleStatusValues($object);

    if (method_exists($object, 'canStepForward'))
    {
      return $object->canStepForward();
    }
    else
    {
      return in_array($object->$status_column_getter() + 1, $possible_status_values);
    }
  }

  public static function stepForward($object)
  {
    list($status_column_setter, $status_column_getter) = self::getStatusAccessors($object);
    $possible_status_values = self::getPossibleStatusValues($object);

    if (count($possible_status_values))
    {
      if (self::canStepForward($object))
      {
        $object->$status_column_setter($object->$status_column_getter() + 1);
      }
      else
      {
        throw new Exception('Object of class '.get_class($object).' cannot step forward.');
      }
    }
  }

  public static function canStepBackward($object)
  {
    list($status_column_setter, $status_column_getter) = self::getStatusAccessors($object);
    $possible_status_values = self::getPossibleStatusValues($object);

    if (method_exists($object, 'canStepBackward'))
    {
      return $object->canStepBackward();
    }
    else
    {
      return in_array($object->$status_column_getter() - 1, $possible_status_values);
    }
  }

  public static function stepBackward($object)
  {
    list($status_column_setter, $status_column_getter) = self::getStatusAccessors($object);
    $possible_status_values = self::getPossibleStatusValues($object);

    if (count($possible_status_values))
    {
      if (self::canStepBackward($object))
      {
        $object->$status_column_setter($object->$status_column_getter() - 1);
      }
      else
      {
        throw new Exception('Object of class '.get_class($object).' cannot step backward.');
      }
    }
  }
}
