<?php
// FROM HASH: 3fb821a2492a31cb2e41165c2c0c54a8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<p>
	prefered contact method: ' . $__templater->escape($__vars['contact']) . '
</p>
<table>
  <tr>
    <td>Request type</td>
    <td>Appointment</td>
  </tr>
  <tr>
    <td>Desired Date</td>
    <td>' . $__templater->escape($__vars['date']) . '</td>

  </tr>
  <tr>
    <td>Desired Time</td>
    <td>' . $__templater->escape($__vars['time']) . '</td>
  </tr>
  <tr>
    <td>Appointment Duration</td>
    <td>' . $__templater->escape($__vars['type']) . '</td>
  </tr>
  <tr>
    <td>Desired City</td>
    <td>' . $__templater->escape($__vars['city']) . '</td>
  </tr>
	  <tr>
    <td>Providers Rates</td>
    <td>' . $__templater->escape($__vars['rates']) . '</td>
  </tr>
  <tr>
    <td>P411 Promotion</td>
    <td>' . $__templater->escape($__vars['promotion']) . '</td>
  </tr>
</table>';
	return $__finalCompiled;
}
);