section_auto_height = ->
	if $(window).width() > 768
		$('.ah').height( $(window).height() )
	else
		$('.ah').css
			height: "auto"

resizeToCover = ->
	# set the video viewport to the window size
	jQuery(".video-index").width jQuery(window).width()
	jQuery(".video-index").height jQuery(window).height()

	# use largest scale factor of horizontal/vertical
	scale_h = jQuery(window).width() / vid_w_orig
	scale_v = jQuery(window).height() / vid_h_orig
	scale = (if scale_h > scale_v then scale_h else scale_v)

	# don't allow scaled width < minimum video width
	scale = min_w / vid_w_orig  if scale * vid_w_orig < min_w

	# now scale the video
	jQuery("video").width scale * vid_w_orig
	jQuery("video").height scale * vid_h_orig

	# and center it by scrolling the video viewport
	jQuery(".video-index").scrollLeft (jQuery("video").width() - jQuery(window).width()) / 2
	jQuery(".video-index").scrollTop (jQuery("video").height() - jQuery(window).height()) / 2

autoAjustLine = ->
	# funcion para autoajustar div con linea de colores inferior
	setTimeout ->
		$(".bg-footer-line").addClass "init"

		if ( $("main").height() > ( $(window).height() + 20 ) )
			$(".bg-footer-line").addClass "toggle"
		else
			$(".bg-footer-line").removeClass "toggle"
	, 300

adjustModalMaxHeightAndPosition = ->
	$(".modal-center").each ->
		$(this).show()  if $(this).hasClass("in") is false
		contentHeight = $(window).height() - 60
		headerHeight = $(this).find(".modal-header").outerHeight() or 2
		footerHeight = $(this).find(".modal-footer").outerHeight() or 2
		$(this).find(".modal-content").css "max-height": ->
			contentHeight

		$(this).find(".modal-body").css "max-height": ->
			contentHeight - (headerHeight + footerHeight)

		$(this).find(".modal-content .img").css "height": ->
			contentHeight - 80

		$(this).find(".modal-dialog").addClass("modal-dialog-center").css
			"margin-top": ->
				-($(this).outerHeight() / 2)

			"margin-left": ->
				-($(this).outerWidth() / 2)

		$(this).hide()  if $(this).hasClass("in") is false