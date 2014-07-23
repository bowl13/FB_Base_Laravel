@extends('layout')

@section('content')
<div class="welcome">
    <h1>Login</h1>
    <?php echo link_to('', 'Facebook Login', array('class' => 'login_fb')); ?>
</div>
@stop