# include gulp
gulp           = require("gulp")

# include our plugins
sass           = require("gulp-sass")
plumber        = require("gulp-plumber")
notify         = require("gulp-notify")
minifycss      = require("gulp-minify-css")
autoprefixer   = require("gulp-autoprefixer")
concat         = require("gulp-concat")
rename         = require("gulp-rename")
uglify         = require("gulp-uglify")
coffee         = require("gulp-coffee")
clean          = require("gulp-clean")
#imagemin       = require("gulp-imagemin")
gulpStripDebug = require("gulp-strip-debug")
iconfontCss    = require("gulp-iconfont-css")
iconfont       = require("gulp-iconfont")
browserSync    = require("browser-sync")
lr             = require("tiny-lr")
livereload     = require("gulp-livereload")
server         = lr()

# paths
src            = "src"
dest           = "../public/assets"

#
#	 tasks
#  ==========================================================================

# copy vendor scripts
gulp.task "copy", ->
	gulp.src [
		src + "/vendor/scripts/jquery.js"
		src + "/vendor/scripts/modernizr.js"
		src + "/vendor/scripts/detectizr.js"
	]
	# .pipe uglify()
	.pipe gulp.dest dest + "/js"

# imagemin
gulp.task "imagemin", ->
	gulp.src dest + "/"
	.pipe coffee
		bare: true
	.pipe concat("scripts.js")
	.pipe gulp.dest dest + "/js"
	.pipe livereload(server)

# coffee
gulp.task "coffee", ->
	gulp.src src + "/scripts/**/*.coffee"
	.pipe coffee
		bare: true
	.pipe concat("scripts.js")
	.pipe gulp.dest dest + "/js"
	.pipe livereload(server)

# coffee-dist
gulp.task "coffee-dist", ->
	gulp.src src + "/scripts/**/*.coffee"
	.pipe coffee
		bare: true
	.pipe concat("scripts.js")
	.pipe uglify()
	.pipe gulp.dest dest + "/js"

# scripts
gulp.task "scripts",["coffee"], ->
	gulp.src [
		src + "/vendor/scripts/bootstrap/*.js"
		!src + "/vendor/scripts/plugins/_*.js"
		src + "/vendor/scripts/plugins/*.js"
		dest + "/js/scripts.js"
	]
	.pipe concat "scripts.js"
	.pipe gulp.dest dest + "/js"

# scripts-dist
gulp.task "scripts-dist",["coffee"], ->
	gulp.src [
		src + "/vendor/scripts/bootstrap/*.js"
		!src + "/vendor/scripts/plugins/_*.js"
		src + "/vendor/scripts/plugins/*.js"
		dest + "/js/scripts.js"
	]
	.pipe concat "scripts.js"
	.pipe gulpStripDebug()
	.pipe uglify()
	.pipe gulp.dest dest + "/js"

# styles
gulp.task "styles", ->
	gulp.src src + "/styles/styles.scss"
	.pipe plumber()
	.pipe sass
		sourceComments: "normal"
		errLogToConsole: false
		onError: (err) -> notify().write(err)
	.pipe autoprefixer("last 15 version")
	.pipe gulp.dest dest + "/css"
	.pipe livereload(server)

# styles-dist
gulp.task "styles-dist",  ->
	gulp.src src + "/styles/styles.scss"
	.pipe plumber()
	.pipe sass()
	.on "error", notify.onError()
	.on "error", (err) ->
		console.log "Error:", err
	.pipe autoprefixer("last 15 version")
	.pipe minifycss(
			keepSpecialComments: 0)
	.pipe gulp.dest dest + "/css"

# Proxy to existing vhost (version 0.7.0 & greater)
gulp.task "browser-sync", ->
	browserSync.init null,
		server:
			baseDir: "./dist"
		# proxy: "192.168.0.106:3002"

gulp.task 'watch', ->
	server.listen 35729, (err) ->
		return console.error(err)  if err

		gulp.watch [src + '/scripts/**/*.coffee'], ['scripts']
		gulp.watch [src + '/styles/**/*.scss'], ['styles']
		gulp.watch [src + "/vendor/scripts/plugins/*.js"], ['scripts']
		# gulp.watch("../*.php").on "change", (file) ->
		# 	livereload(server).changed file.path

gulp.task "iconfont", ->
	gulp.src dest + "/img/partials/svg/*.svg"
	.pipe(iconfontCss(
		fontName: "iconfont"
		path: src + "/styles/fonts/iconfont/template/_template.scss"
		targetPath: "../../_gulp/src/styles/fonts/iconfont/_iconfont.scss"
		fontPath: dest + "/fonts/iconfont"
	))
	.pipe iconfont
		fontName: "iconfont"
		normalize: true
	.pipe gulp.dest dest + "/fonts/iconfont"


#
#  main tasks
#	 ==========================================================================

gulp.task 'default', [
	"copy"
	"styles"
	"scripts"
	"watch"
]

# build wp task
gulp.task 'dist', [
	"styles-dist"
	"scripts-dist"
]






