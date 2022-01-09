<?php
switch ($standing) {
    case 5:
        $standingClass = 'badge-error';
        break;
    case 10:
        $standingClass = 'badge-warning';
        break;
    default:
        $standingClass = 'badge-success';
        break;
}
?>
<span class="badge {{ $standingClass }}">{{ $standing }}</span>
