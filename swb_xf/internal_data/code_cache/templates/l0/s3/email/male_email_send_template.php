<?php
// FROM HASH: 75d4f9b98e9801b9d1fdf96952747014
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<style>
table {
  border-collapse: collapse;
  width: 100%;
}

td {
  border: 1px solid #dddddd;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
<mail:subject>
	' . 'Appointment Request' . '
</mail:subject>
<p>
	' . 'Preferred contact method' . ' : ' . $__templater->escape($__vars['contact']) . '
</p>
<table>
  <tr>
    <td>' . 'Request type' . ' </td>
    <td>' . 'Appointment' . '</td>
  </tr>
  <tr>
    <td> ' . 'Desired Date' . '</td>
    <td>' . $__templater->escape($__vars['date']) . '</td>

  </tr>
  <tr>
    <td> ' . 'Desired Time' . '</td>
    <td>' . $__templater->escape($__vars['time']) . '</td>
  </tr>
  <tr>
    <td> ' . 'Appointment Duration' . '</td>
    <td>' . $__templater->escape($__vars['duration']) . '</td>
  </tr>
	  <tr>
    <td> ' . 'Appointment Type' . '</td>
    <td>' . $__templater->escape($__vars['type']) . '</td>
  </tr>
  <tr>
    <td>' . ' Desired City' . '</td>
    <td>' . $__templater->escape($__vars['city']) . '</td>
  </tr>
</table>';
	return $__finalCompiled;
}
);