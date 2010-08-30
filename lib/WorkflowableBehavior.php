<?php

class WorkflowableBehavior extends Behavior
{
  protected $parameters = array(
    "status_column_name" => "status",
    "possible_status_values" => array()
  );

  public function objectMethods()
  {
    $script = "";
    $script .= $this->addCanStepForwardMethod();
    $script .= $this->addStepForwardMethod();
    $script .= $this->addCanStepBackwardMethod();
    $script .= $this->addStepBackwardMethod();

    return $script;
  }

  protected function getStatusAccessors()
  {
    $status_column_setter = "set".sfInflector::camelize($this->getParameter("status_column_name"));
    $status_column_getter = "get".sfInflector::camelize($this->getParameter("status_column_name"));

    return array($status_column_setter, $status_column_getter);
  }

  public function addCanStepForwardMethod()
  {
    list($status_column_setter, $status_column_getter) = $this->getStatusAccessors();
    $possible_status_values = $this->getParameter("possible_status_values");

    return "
/**
 * Returns true if the object can step forward.
 */
public function canStepForward()
{
  return in_array(\$this->$status_column_getter() + 1, array($possible_status_values));
}
";
  }

  public function addStepForwardMethod()
  {
    list($status_column_setter, $status_column_getter) = $this->getStatusAccessors();
    $possible_status_values = $this->getParameter("possible_status_values");

    return "
/**
 * Steps forward to the next status.
 */
public function stepForward()
{
  if (count(array($possible_status_values)))
  {
    if (\$this->canStepForward())
    {
      \$this->$status_column_setter(\$this->$status_column_getter() + 1);
    }
    else
    {
      throw new Exception(\"Object of class \".get_class(\$this).\" cannot step forward.\");
    }
  }
}
";
  }

  public function addCanStepBackwardMethod()
  {
    list($status_column_setter, $status_column_getter) = $this->getStatusAccessors();
    $possible_status_values = $this->getParameter("possible_status_values");

    return "
/**
 * Returns true if the object can step backward.
 */
public function canStepBackward()
{
  return in_array(\$this->$status_column_getter() - 1, array($possible_status_values));
}
";
  }

  public function addStepBackwardMethod()
  {
    list($status_column_setter, $status_column_getter) = $this->getStatusAccessors();
    $possible_status_values = $this->getParameter("possible_status_values");
    
    return "
/**
 * Steps backward to the next status.
 */
public function stepBackward()
{
  if (count(array($possible_status_values)))
  {
    if (\$this->canStepBackward())
    {
      \$this->$status_column_setter(\$this->$status_column_getter() - 1);
    }
    else
    {
      throw new Exception(\"Object of class \".get_class(\$this).\" cannot step backward.\");
    }
  }
}
";
  }
}