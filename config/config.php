<?php

sfPropelBehavior::registerMethods('workflowable', array(
  array('WorkflowableBehavior', 'stepForward'),
  array('WorkflowableBehavior', 'stepBackward')
));
