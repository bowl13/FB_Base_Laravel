<?php

class Usuario extends \Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'usuarios';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	// protected $hidden = array('rut', 'meta');

	public function existsByFuid($fbuid){
		return Usuario::where('fbuid', '=', $fbuid)->first();
	}

	public function saveUsuario($data){
		$usuario = new Usuario;

        foreach ($data as $key => $field) {
            $usuario->$key = $field;
        }

        $usuario->save();
        return $usuario->id;
	}
}