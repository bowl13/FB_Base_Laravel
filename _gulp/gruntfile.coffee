"use strict"

module.exports = (grunt) ->
	grunt.loadNpmTasks('grunt-webfont')
	grunt.loadNpmTasks('grunt-favicons')

	src = ".."
	dist = "dist"

	grunt.initConfig
		webfont:
			icons:
				src: "../img/partials/svg/*.svg"
				dest: "../fonts/webfont_test"
				options:
					engine: "node"
					font: "webfont"
					hashes: false
					stylesheet: "scss"
					startCodepoint: "0xF000"
					destCss: "styles/fonts/webfont/"
					relativeFontPath: "../fonts/webfont/"
					# styles: false
					templateOptions:
						baseClass: 'icon'
						classPrefix: 'icon-'
						mixinPrefix: 'icon-'

		favicons:
			options:
				trueColor: true
				precomposed: false
				appleTouchBackgroundColor: "#ffffff"
				windowsTile: true
				tileBlackWhite: false
				tileColor: "auto"
				html: ""
				HTMLPrefix: ""

			icons:
				src: "../img/partials/favicon.png"
				dest: "../img/partials/favicons/"

	grunt.registerTask "default", [
	]

