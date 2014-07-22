<?php

class UsuariosController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$fbSet = Config::get('app.fb');

		if (Session::has('access_token') === false)
		{
			Session::put('state', md5(uniqid(rand(), TRUE)));
			
            $urlAuthFb = 'https://www.facebook.com/dialog/oauth?client_id=' . $fbSet['APP_ID'] .
            				'&redirect_uri=' . urlencode( url('usuarios/callback') ) . 
            				'&scope=email,publish_stream&state=' . Session::get('state');
            // $this->set('urlAuthFb', $urlAuthFb);
            return View::make('Usuarios/index')->with('urlAuthFb', $urlAuthFb);
        }
        else
        {
            return Redirect::to('/');
        }
	}

	public function callback()
	{
		if (Request::isMethod('get'))
		{
			$fbSet = Config::get('app.fb');
            $urlToken = 'https://graph.facebook.com/oauth/access_token?client_id=' . $fbSet['APP_ID'] .
            '&redirect_uri=' . urlencode( url('usuarios/callback') ) . '&client_secret=' . $fbSet['APP_SECRET'] .
            '&code=' . Request::query('code');

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $urlToken);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            $output = curl_exec($ch);
            $meta = explode("&", $output);
            $access = explode("=", $meta[0]);
            curl_close($ch);


            $urlData = 'https://graph.facebook.com/me?access_token='.$access[1];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $urlData);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            $outputData = curl_exec($ch);
            $metaData = json_decode($outputData);
            $metaData->access_token = $access[1];
            curl_close($ch);

            #Obiene Access Token long-lived
            $longLivedToken = $this->getAccessTokenLongLived( $access[1] );

            $arrUserAdd =
                array(
                    "fbuid" => $metaData->id,
                    "firstname" => $metaData->first_name,
                    "lastname" => $metaData->last_name,
                    "email" => $metaData->email,
                    "genero" => $metaData->gender,
                    "ip" => Request::getClientIp(),
                    "complete" => 0,
                    "meta" => json_encode(array("link" => $metaData->link, "locale" => $metaData->locale, "name" => $metaData->name, "timezone" => $metaData->timezone, "updated_time" => $metaData->updated_time, "username" => $metaData->username)),
                    "access_token" => $longLivedToken["access_token"],
                    "expire_token" => $longLivedToken["expires"]
                );
            $response = $this->store($arrUserAdd);
            if ($response !== null)
            {
                Session::put('idFb', $metaData->id);
                Session::put('access_token', $metaData->access_token);
//                return Redirect::to($fbSet['FB_APP']);
                return Redirect::to('/');
            }
            else
            {
                App::abort(403, 'Error autorización');
            }
		} 
		else 
		{
			return Redirect::to('/');
		}
		die();

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($args)
	{
		if ( Request::isMethod('post') || $args != null) 
		{
            $response = array();
			#Crear instancia modelo usuario
			$usuario = new Usuario;
			
            #Si registra desde otro metodo
            #if($args) $this->request->data = $args['Usuarios'];
            $exists = $usuario->existsByFuid($args["fbuid"]);

            if($exists === null){
                $usuario_id = $usuario->saveUsuario( $args );
                if ( $usuario_id ) {
                    Session::put('uid', $usuario_id);
                    $response = array("state" => 1);
                } else {
                    $response = array("state" => 0);
                }
            }  else {
                $exists->updated_at = date('Y-m-d H:i:s');
                $exists->save();
                Session::put('uid', $exists->id);
                $response = array("state" => 2);
            }
            return $response;
		}
	}


    private function getAccessTokenLongLived( $access_token ){
        $fbSet = Config::get('app.fb');
        $arrAuth = array(
            "client_id" => $fbSet['APP_ID'],
            "client_secret" => $fbSet['APP_SECRET'],
            "grant_type" => 'fb_exchange_token',
            "fb_exchange_token" => $access_token
        );

        $urlAuth = 'https://graph.facebook.com/oauth/access_token';
        $curlAuth = curl_init();
        curl_setopt($curlAuth, CURLOPT_URL, $urlAuth);
        curl_setopt($curlAuth, CURLOPT_HEADER, false);
        curl_setopt($curlAuth, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlAuth, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlAuth, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curlAuth, CURLOPT_POST, true);
        curl_setopt($curlAuth, CURLOPT_POSTFIELDS, $arrAuth);
        curl_setopt($curlAuth, CURLOPT_FOLLOWLOCATION, 1);
        $responseAuth = curl_exec($curlAuth);
        if (!empty($responseAuth)) {
            parse_str($responseAuth, $parsedAuth);
            return $parsedAuth;
        } else {
            return "";
        }
    }
//	/**
//	 * Display the specified resource.
//	 *
//	 * @param  int  $id
//	 * @return Response
//	 */
//	public function show($id)
//	{
//		Session::put('fbuid', '123123123aaa');
//	}
//
//
//	/**
//	 * Show the form for editing the specified resource.
//	 *
//	 * @param  int  $id
//	 * @return Response
//	 */
//	public function edit($id)
//	{
//		//
//	}
//
//
//	/**
//	 * Update the specified resource in storage.
//	 *
//	 * @param  int  $id
//	 * @return Response
//	 */
//	public function update($id)
//	{
//		//
//	}
//
//
//	/**
//	 * Remove the specified resource from storage.
//	 *
//	 * @param  int  $id
//	 * @return Response
//	 */
//	public function destroy($id)
//	{
//		//
//	}


}