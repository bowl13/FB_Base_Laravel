window.main =
  init: () ->
    setTimeout ->
        $("html").addClass "init"
    , 10

    setTimeout ->
        $("html").addClass "init-1"
    , 200

    setTimeout ->
        $("html").addClass "init-2"
    , 700
    main.actions()
  actions: () ->
    $(".login_fb").click (e) ->
      e.preventDefault()
      main.loginFb()
      return
  loginFb: () ->
    if Modernizr.Detectizr.device.type is "desktop"
      FB.getLoginStatus (response) ->
        if response.status is "connected"
          access_token = response.authResponse.accessToken
          FB.api "/me", (response) ->
            main.userLogin response, access_token
        else
          FB.login ((response) ->
            if response.authResponse
              access_token = FB.getAuthResponse()["accessToken"]
              FB.api "/me", (response) ->
                main.userLogin response, access_token
          ),
            scope: "email,publish_stream"
    else
      cbFB = APP_URI + "usuarios/callback"
      location.href = 'https://www.facebook.com/dialog/oauth?client_id='+clientId+'&redirect_uri='+cbFB+'&scope=email,publish_actions&state='+Math.floor(Math.random()*11);

  userLogin: (user, token) ->
    #Revisar datos primarios
    if user.id isnt undefined and user.email isnt undefined and user.first_name isnt undefined
      #Enviar datos usuario registro
      $.ajax
        url: APP_URI + "/facebook/login"
        type: "POST"
        data: "email=" + user.email + "&first_name=" + user.first_name + "&gender=" + user.gender + "&id=" + user.id + "&last_name=" + user.last_name + "&link=" + user.link + "&locale=" + user.locale + "&name=" + user.name + "&timezone=" + user.timezone + "&updated_time=" + user.updated_time + "&username=" + user.username + "&access_token=" + token
        dataType: "json"
        beforeSend: ->

        success: (results) ->
          if results.state is 1 or results.state is 2
            location.href = APP_URI + '?login=ok'
          else
            alert "error al guardar"
    else
      main.loginFb()

$('document').ready ->
  window.main.init()
