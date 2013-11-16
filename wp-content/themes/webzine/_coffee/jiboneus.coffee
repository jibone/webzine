###
#
# Jiboneus.js
#
###

"use strict"

# avoide console.log error on IE
unless window.console
  window.console =
    log: ->

# on document load
$(document).ready ->

  bla = $("#heroTitle")
  console.log bla.length
  unless bla.length is 0
    console.log "do scroll check"
    $(window).scroll () ->
      scrollCheck()
  else
    console.log "dont do scroll check"
    $('.top-bar-container').addClass "top-bar-container-white"

scrollCheck = () ->
  window_top = $(window).scrollTop()
  div_top = $("#heroTitle").offset().top
  if window_top > div_top
    $('.top-bar-container').addClass "top-bar-container-white"
  else
    $('.top-bar-container').removeClass "top-bar-container-white"
###
    var window_top = $(window).scrollTop();
		var div_top = $("#sticky-anchor").offset().top;
		var div_bottom = $("#sticky-bottom").offset().top;
		
		if(window_top > div_top && window_top < div_bottom) {
			$('#sticky').addClass('stick');
		} else {
			$('#sticky').removeClass('stick');
		}
###
