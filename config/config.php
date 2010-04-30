<?php

sfPropelBehavior::registerMethods('workflowable', array(
  array('pmPropelWorkflowableBehavior', 'stepForward'),
  array('pmPropelWorkflowableBehavior', 'stepBackward')
));
